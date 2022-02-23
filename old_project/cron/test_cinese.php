<?php
header('Content-type: text/plain; charset=utf-8');
echo "<pre>";
try {

	//includo il file di configurazione
	require_once dirname(dirname(__FILE__)).'/includes/config.inc.php';

	
$cd_mtg_sets = new Model_mtg_sets();
$cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
$cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);
$test = $cd_mtg_sets->get_data(table_cards_name, 'multiverseid', 'DESC', 1, 0, 'WHERE language = \'Japanese\' ');

echo $test[0]['name'];

} catch (Exception $e) {
	
	print_r($e);
	
}

echo "</pre>";
?>