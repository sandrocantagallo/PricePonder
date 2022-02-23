<?php
/**
 * Script di importazione totale utilizzando i file json forniti dal portale http://mtgjson.com
 * 
 * - Scaricare sempre il file http://mtgjson.com/json/AllSetFiles-x.zip per avere l'ultima versione aggiornata del DB in formato JSON
 * - Forzare tutta la codifica carattere in UTF8: ->connect_db->send_query('SET CHARACTER SET utf8', db_name);
 * 
 */



try {


	header('Content-type: text/plain; charset=utf-8');
	
	echo "<pre>";
	
	
	
	//includo il file di configurazione
	require_once dirname(dirname(__FILE__)).'/includes/config.inc.php';
	
	echo "------------------------------------------- ".shell_hv;
	echo "- Price Ponder ".shell_hv;
	echo "- AllSetFiles Importer Script ".shell_hv;
	echo "- Execution: ".date("d-m-Y", time())." ".shell_hv;
	echo "------------------------------------------- ".shell_hv;
	
	//carico il model per la gestione del db
	$cd_mtg_sets = new Model_mtg_sets();
	//scansiono la directory contenente tutti i file relativi ai SET di mtg
	$ay_files = scandir(dirname(dirname(__FILE__)).'/xml/AllSetFiles');
	//avvio la transaction
	$cd_mtg_sets->connect_db->start_transaction();
	//forzo la codifica in utf8
	$cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
	$cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);
	//setto in una variabile il numero di files che lo script deve analizzare per volta
	$tot_files = 1;
	//setto una variabile in cui conterò il numero di cicli che lo script esegue
	$i = 0;
	
	
	/**
	 * @todo modificare il metodo con cui si sceglie quale sia il file da importare
	 * si consiglia di recuperare dal db TUTTI i nomi dei SET importati controllare l'array dei file
	 * e creare un array con i file che non sono presenti nei SET importati e da questo ARRAY recuperare 
	 * semplicemente il primo della fila.
	 */
	
	//recupero l'ultimo SET importato per capire quale file devo eseguire per procedere con ordine
	/*$last_imported_set_code = $cd_mtg_sets->get_last_imported_set();
	
	if ($last_imported_set_code) {
		$new_set_to_import = $cd_mtg_sets->new_set_to_import($ay_files, $last_imported_set_code);
	} else {
		$new_set_to_import = '10E-x.jsons';
	}*/
	
	$ay_set_imported = $cd_mtg_sets->get_data(table_sets, 'id', 'DESC', '900', '0', '');
	
	$ay_files = $cd_mtg_sets->rewrite_files_list($ay_set_imported, $ay_files);
	
	if (is_array($ay_files)) {
		
		foreach ($ay_files as $file) {
			
			if (($file != '.') AND ($file != '..')) {
				
				if (($i < $tot_files)) {
				
					//tento la lettura del file
					$str = file_get_contents(dirname(dirname(__FILE__)).'/xml/AllSetFiles/'.$file);
					$json = json_decode($str, true);

					if (is_array($json)) {
						
						echo "- Try to Import SET: ".$json['name'].shell_hv;
						
						//controllo la presenza del SET all'interno del DB
						if (!$cd_mtg_sets->check_double_content(table_sets, 'code', $json['code'])) {
							//se non è presente nel db procedo con l'inserimento del dato
							$ay_data = array	(
									'code'			=>	$cd_mtg_sets->format_string($json['code']),
									'name'			=>	$cd_mtg_sets->format_string($json['name']),
									'type'			=>	$cd_mtg_sets->format_string($json['type']),
									'releaseDate'	=>	$cd_mtg_sets->format_string($json['releaseDate']),
							);
							
							$cd_mtg_sets->ins_data(table_sets, $ay_data);
							// recupero del set l'id di inserimento
							$id_set = mysql_insert_id();
							echo "- Set imported. Try to import all cards of set ".shell_hv;
							//controllo se ci sono carte da importare
							if (is_array($json['cards'])) {
								//print_r($json['cards']);
								
								foreach ($json['cards'] as $card) {
									echo "- Check ".$card['name']." with multiverseid ".$card['multiverseid']." ".shell_hv;
									//controllo che il multiverseid non sia già stato caricato
									if (!$cd_mtg_sets->check_double_content(table_cards, 'multiverseid', $card['multiverseid'])) {
										// preparo l'array con i dati da inserire nel db
										$ay_data_card = array	(
																	'multiverseid'	=>	$card['multiverseid'],
																	'layout'		=>	$card['layout'],
																	'type'			=>	$card['type'],
																	'name'			=>	$card['name'],
																	'originalType'	=>	$card['originalType'],
																	'cmc'			=>	$card['cmc'],
																	'rarity'		=>	$card['rarity'],
																	'artist'		=>	$card['artist'],
																	'power'			=>	$card['power'],
																	'toughness'		=>	$card['toughness'],
																	'manaCost'		=>	$card['manaCost'],
																	'text'			=>	$card['text'],
																	'originalText'	=>	$card['originalText'],
																	'flavor'		=>	$card['flavor'],
																	'number'		=>	$card['number'],
																	'imageName'		=>	$card['imageName'],
																); 
										
										$cd_mtg_sets->ins_data(table_cards, $ay_data_card);
										
										if (is_array($card['colors'])) {
											foreach ($card['colors'] as $color) {
												$ay_data_colors = array (
																			'multiverseid'	=>	$card['multiverseid'],
																			'color'			=>	$color,
																		);
												$cd_mtg_sets->ins_data('mtg_cards_colors', $ay_data_colors);
												unset($ay_data_colors);
											}
										}
										
										if (is_array($card['types'])) {
											foreach ($card['types'] as $type) {
												$ay_data_types = array (
														'multiverseid'	=>	$card['multiverseid'],
														'type'			=>	$type,
												);
												$cd_mtg_sets->ins_data('mtg_cards_types', $ay_data_types);
												unset($ay_data_types);
											}
										}
										
										if (is_array($card['foreignNames'])) {
											foreach ($card['foreignNames'] as $foreignNames) {
												$ay_data_foreignNames = array (
														'multiverseid'	=>	$card['multiverseid'],
														'language'			=>	$foreignNames['language'],
														'name'			=>	$foreignNames['name'],
														
												);
												$cd_mtg_sets->ins_data('mtg_cards_foreignnames', $ay_data_foreignNames);
												unset($ay_data_foreignNames);
											}
										}
										
										if (is_array($card['rulings'])) {
											foreach ($card['rulings'] as $ruling) {
												$ay_data_rulings = array (
														'multiverseid'	=>	$card['multiverseid'],
														'text'			=>	$ruling['text'],
														'date'			=>	$ruling['date'],
										
												);
												$cd_mtg_sets->ins_data('mtg_cards_rulings', $ay_data_rulings);
												unset($ay_data_rulings);
											}
										}
										
										if (is_array($card['legalities'])) {
											foreach ($card['legalities'] as $where=>$legalities) {
												$ay_data_legalities = array (
														'multiverseid'	=>	$card['multiverseid'],
														'where'			=>	$where,
														'legal'			=>	$legalities,
										
												);
												$cd_mtg_sets->ins_data('mtg_cards_legalities', $ay_data_legalities);
												unset($ay_data_legalities);
											}
										}
										
										//associo la carta al set importato
										$ay_card_in_set = array (
																	'id_set'	=>	$id_set,
																	'multiverseid'	=> $card['multiverseid'],
																);
										$cd_mtg_sets->ins_data('mtg_cards_in_set', $ay_card_in_set);
										unset($ay_card_in_set);
										unset($ay_data_card);
									} else {
										echo "- Card already imported ".shell_hv;
										//associo però la carta al set appena creato
										$ay_card_in_set = array (
												'id_set'	=>	$id_set,
												'multiverseid'	=> $card['multiverseid'],
										);
										$cd_mtg_sets->ins_data('mtg_cards_in_set', $ay_card_in_set);
										unset($ay_card_in_set);
									}
									
								}
								
							}
							
							// setto come importato il set per l'eseguzione corretta del prossimo lancio dello script
							unset($ay_data);
							$ay_data = array (
												'mtg_sets_id'	=>	$id_set,			
									);
							$cd_mtg_sets->ins_data(table_imported_sets, $ay_data);
							
						}
						
					}
					
					$i++;
					unset($str);
					unset($json);
				} else {
					
				}
				
				
				
			}
		}
		
	}
	
	unset($ay_files);

	//eseguo l'invio di tutte le query di inserimento eseguite
	$cd_mtg_sets->connect_db->commit_transaction();
	
} catch (Exception $e) {
	//eseguo il rollback delle query eseguite
	
	  
	print_r($e);
	
}


echo "</pre>";


?>

<html>
<head>
<META http-equiv="refresh" content="10;URL=http://<?php echo $_SERVER['HTTP_HOST']?>/priceponder/cron/AllSetFiles.php">
</head>

<body>
	Sto per ricaricare la pagina
</body>
</html>