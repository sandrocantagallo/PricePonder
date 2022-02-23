<?php
/**
 * Importatore automatico delle carte di Magi The Gathering partendo dal file xml di cockatrice
 * 
 * 
 */

try {
	//includo il file di configurazione
	require_once dirname(dirname(__FILE__)).'/includes/config.inc.php';

	function queryToArray($qry)
	{
		$result = array();
		//string must contain at least one = and cannot be in first position
		if(strpos($qry,'=')) {
	
			if(strpos($qry,'?')!==false) {
				$q = parse_url($qry);
				$qry = $q['query'];
			}
		}else {
			return false;
		}
	
		foreach (explode('&', $qry) as $couple) {
			list ($key, $val) = explode('=', $couple);
			$result[$key] = $val;
		}
	
		return empty($result) ? false : $result;
	}
	
	//carico il model per la gestione del db
	$cd_mtg_sets = new Model_mtg_sets();
	//carico il file xml nella variabile
	$xml = simplexml_load_file(base_path.xml_path.xml_name_file);
	
	$i = 0;
	//recupero il set di cui devo importare le carte
	$set_name = $cd_mtg_sets->get_set_to_import();
	if ($set_name) {
	//cerco tutti i set presenti nel file
	foreach ($xml->xpath("cards/card[set=\"".$set_name['name']."\"]") as $card) {
		
		//echo "<pre>";
		//print_r($card);
		//echo "</pre>";
		
		//controllo che la carta non sia già presente nel sistema
		if (!$cd_mtg_sets->check_double_content(table_cards, 'name', $card->name)) {
			
		
				
				
			
				$tot_set = count($card->set);
				
				
				
				if ($tot_set > 1) {
				
				for ($sets=0;$sets<$tot_set;$sets++) {
					
					
					
					
					
					
					$attrs = $card->set[$sets]->attributes()->{'muId'};
					$ay_pic_url = parse_url($attrs[0]);
						
					
						
					$ay_set[$sets]['code'] = $card->set[$sets]->__toString();
					$ay_set[$sets]['multiverseid'] = $ay_pic_url['path'];
						
				}
			
			} else {
				$ay_set[0]['code'] = $card->set->__toString();
				$attrs = $card->set[0]->attributes()->{'muId'};
				$ay_pic_url = parse_url($attrs[0]);
				$ay_set[0]['multiverseid'] = $ay_pic_url['path'];
			}
			
			
			
			if (!isset($card->pt)) {
				$card->pt = '';
			}
			
			
			
			//$multiverseid = $cd_mtg_sets->format_string($ay_pic_url['path']);
			
			
			//echo $multiverseid." <br />";
			
			
			//if ($multiverseid != 0) {
			
				/*$ay_data = array 	(
						'multiverseid'	=>	$cd_mtg_sets->format_string($ay_pic_url['path']),
						'name'			=>	$cd_mtg_sets->format_string($card->name),
						'set'			=>	$cd_mtg_sets->format_string(json_encode($ay_set)),
						'color'			=>	$cd_mtg_sets->format_string(json_encode($card->color)),
						'manacost'		=>	$cd_mtg_sets->format_string($card->manacost),
						'type'			=>	$cd_mtg_sets->format_string($card->type),
						'pt'			=>	$cd_mtg_sets->format_string($card->pt),
						'tablerow'		=>	$cd_mtg_sets->format_string($card->tablerow),
						'text'			=>	$cd_mtg_sets->format_string($card->text),
						'img'			=>	$cd_mtg_sets->format_string('http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid='.$ay_pic_url['path'].'&type=card'),
				
				);*/
				
				$ay_data = array 	(
						//'multiverseid'	=>	$ay_pic_url['path'],
						'name'			=>	$card->name,
						//'set'			=>	json_encode($ay_set),
						'color'			=>	json_encode($card->color),
						'manacost'		=>	$card->manacost,
						'type'			=>	$card->type,
						'pt'			=>	$card->pt,
						'tablerow'		=>	$card->tablerow,
						'text'			=>	$card->text,
						'img'			=>	'http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid='.$ay_pic_url['path'].'&type=card',
				
				);
			
			//} else {
				
				//echo "Skypped: ".$card->name." <br /> ";
			//}
			
			
			
			if ((isset($ay_data)) and (is_array($ay_data))) {
				
				if (!$cd_mtg_sets->check_double_content(table_cards, 'name', mysql_real_escape_string($ay_data['name']))) {
				
					//echo "questo è l'array ai_data che voglio caricare <br />";

					$cd_mtg_sets->ins_card_data(table_cards, $ay_data, $ay_set);
				
				}
				unset($ay_set);
			}
			
			$i++;
		}
	}
		//setto il fatto che ho parserizzato il SET di carte preso in esame
		$ay_data_set = array	(
				'mtg_sets_id'	=>	$set_name['id'],
		);
		$cd_mtg_sets->ins_data(table_imported_sets, $ay_data_set);
		$feedback =  "Imported ".$i." cards of set: ".$set_name['name'];
	} else{
		$feedback =  "No set to import.";
	}
	
	require_once 'tpl/tpl_xml_cards_importer.php';
	
} catch (Exception $e) {
	$feedback = $e->getMessage();
}

//echo $feedback;

?>