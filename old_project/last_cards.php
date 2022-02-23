<?php
/**
 * Script che genera un array JSON con le ultime carte recuperate dal portale e cercate dai cliente mostrando quindi subito
 * il loro prezzo sui portali parserizzati
 * 
 */

try {
	//includo il file di configurazione
	require_once dirname(__FILE__).'/includes/config.inc.php';
	//carico il model per la gestione del db
	$cd_mtg_sets = new Model_mtg_sets();

	$ay_data = $cd_mtg_sets->get_last_cards_price();

	/*foreach ($ay_data as $key=>$data) {
		$ay_data[$key] = $data;
	}*/

	echo json_encode($ay_data);
} catch (Exception $e) {
	echo "<pre>";
	print_r($e);
	echo "</pre>";
}

?>