<?php
/*
 * Importa i SET di magic the gathering partendo dal file XML generato
 * 
 */
try {
//includo il file di configurazione
require_once dirname(dirname(__FILE__)).'/includes/config.inc.php';

//carico il model per la gestione del db
$cd_mtg_sets = new Model_mtg_sets();
//carico il file xml nella variabile
$xml = simplexml_load_file(base_path.xml_path.xml_name_file);
$i = 0;
//cerco tutti i set presenti nel file
foreach ($xml->xpath("sets/set") as $set) {
	//controllo che il set non sia già salvato nel db
	if (!$cd_mtg_sets->check_double_content(table_sets, 'name', $set->name)) {	
		//se non è presente nel db procedo con l'inserimento del dato
		$ay_data = array	(
								'name'		=>	$cd_mtg_sets->format_string($set->name),
								'longname'	=>	$cd_mtg_sets->format_string($set->longname),
							);
		$cd_mtg_sets->ins_data(table_sets, $ay_data);
		$i++;
	} 
}
echo "Imported ".$i." sets";
} catch (Exception $e) {
	echo "<pre>";
	print_r($e);
	echo "</pre>";
}
?>