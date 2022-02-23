<?php

try {
	//includo il file di configurazione
	require_once dirname(__FILE__).'/includes/config.inc.php';
	//carico il model per la gestione del db
	$cd_mtg_sets = new Model_mtg_sets();
	
	$ay_data = $cd_mtg_sets->get_cards($_REQUEST['query']);
	
	foreach ($ay_data as $key=>$data) {
		$ay_data[$key] = $data;
	}
	
	echo json_encode($ay_data);
} catch (Exception $e) {
	echo "<pre>";
	print_r($e);
	echo "</pre>";
}




?>