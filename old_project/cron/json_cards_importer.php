<?php
/*
 * 
 */


try {
	
	//includo il file di configurazione
	require_once dirname(dirname(__FILE__)).'/includes/config.inc.php';
	//carico il model per la gestione del db
	$cd_mtg_sets = new Model_mtg_sets();
	
	$feedback = '';
	
	$str = file_get_contents('../xml/AllCards-x.json');
	
	$json = json_decode($str, true);
	
	$tot_cards = count($json);
	
	$start = (isset($_GET['start'])) ? $_GET['start'] : '0';
	$limit = (isset($_GET['limit'])) ? $_GET['limit'] : '30';
	$i = 0;
	$key = 0;
	
	$feedback .=  "<pre>";
	
	$feedback .=  "Total number to analisys is: ".$tot_cards." <br />";
	
	foreach ($json as $object) {
		
		if (($key >= $start) AND ($i <= $limit)) {
			$feedback .=  "Name Card: ".$object['name']." key: ".$key." <br />";
			
			if ($cd_mtg_sets->check_double_content(table_cards, 'name', $object['name'])) {
				
				//la carta è presente ne devo recuperare il multiverseid
				$ay_card = $cd_mtg_sets->get_card($object['name']);
				
				$feedback .=  "MultiverseId: ".$ay_card['id']." <br />";
				
				//preparo l'array da passare al metodo per il salvataggio dei nomi in multilingua se questa carta non è già stata presa in esame
				
				if (!$cd_mtg_sets->check_double_content(table_cards_name, 'id_card', $ay_card['id'])) {
					
					//preparo l'inserimento dei nomi in multilingua
					if (is_array($object['foreignNames'])) {
						
						foreach ($object['foreignNames'] as $ay_language_name) {
							
							$ay_data = array 	(
													'id_card'	=>	$ay_card['id'],
													'language'		=>	$ay_language_name['language'],
													'name'			=>	$ay_language_name['name'],
												);
							
							$cd_mtg_sets->ins_data(table_cards_name, $ay_data);
							
							$feedback .=  "Saved multi language name <br />";
							$feedback .=  "language: ".$ay_language_name['language']."<br />";
							$feedback .=  "name: ".$ay_language_name['name']."<br />";
						}
						
						
						
					}
					
					
					
				} else {
					$feedback .=  "Card already multi-language <br />";
				}
				
				$feedback .=  "----------------------------------- <br />";
			} else {
				$feedback .=  "Card not present in DB skipped <br />";
			}
			
			$feedback .=  "----------------------------------- <br />";
			$i++;
		}
		$key++;
	}
	
	$feedback .=  "<pre>";
	
} catch (Exception $e) {
	$feedback .=  "<pre>";
	print_r($e);
	$feedback .=  "</pre>";
}


?>

<html>
<head>
<META http-equiv="refresh" content="30;URL=http://localhost/priceponder/priceponder/cron/json_cards_importer.php?start=<?=($start+$limit)?>"> 
</head>

<body>
	<?=$feedback?>
</body>
</html>