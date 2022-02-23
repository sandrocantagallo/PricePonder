<?php

try {

	$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : false;

	//includo il file di configurazione
	require 'includes/config.inc.php';
	//istanzio la classe generica Couponit
	$ccp = new Couponit();
	//istanzio la clase di comunicazione con il db delle carte mtg importate
	$cd_mtg_sets = new Model_mtg_sets();

	$cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
	$cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);

	//recupero tutte le carte del set
	$ay_set_cards = $cd_mtg_sets->get_set_cards($id);
	
	echo json_encode($ay_set_cards);
	


} catch (Exception $e) {
	echo "- Method Lunched: generate_log  - ".shell_hv;
	echo "------------------------------------------- ".shell_hv;
	echo $e->getMessage()." ".shell_hv;
	echo $e->getFile()." ".shell_hv;
	echo $e->getLine()." ".shell_hv;

}



?>
