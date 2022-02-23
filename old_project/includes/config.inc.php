<?php
/**
 * PRICEPONDER
 * Configurazion File
 *
 * @author Cantagallo Sandro
 * @version 1.0
 *
 */
header('Content-type: text/html; charset=utf-8');
ini_set("memory_limit", "240M");
// costanti di configurazione
define("status_project", "produzione"); //sostituire il valore sviluppo con produzione quando si vogliono sostituire le path di test locale con quelle del server di produzione
if (status_project == 'sviluppo') {
	define ("base_path", dirname(dirname(__FILE__)));
	define	("db_user",		"");		
	define	("password",	"");		
	define	("db_name",		"");
	define	("db_host",		"localhost");
  	define	("base_url", "");
} else {
	define ("base_path", dirname(dirname(__FILE__)));
	define	("db_user",		"");		
	define	("password",	"");		
	define	("db_name",		"");
	define	("db_host",		"localhost");
	define	("base_url", "");
}

//cartelle del progetto	
define	("class_path", "/class/");
define	("models_path", "models/");
define	("include_path", "/includes/");
define	("xml_path", "/xml/");
define	("xml_name_file", "cards.xml");

$ay_admin_email = array	(
							0 => '',
						);

// costanti utili quando si sviluppa il feedback per linea di comando
//define ("shell_hv", "\n");
define ("shell_hv", "<br />");

define("table_sets", "mtg_sets"); //tabella contenenete i SET di carte di magic the gathering
define ("table_imported_sets", "mtg_imported_sets"); //tabella log contenente i SET di cui priceponder ha importato i dati
define ("table_cards", "mtg_cards"); //tabella delle carte di magic the gathering
define ("table_cards_name", "mtg_cards_foreignnames"); //tabella con i nomi in multilingua delle carte

define ("PRICE_PONDER_TOT_SITE", "5");

//array contenenti i set di gioco di Magic The Gathering
$ay_legal = array 	(
		0	=>	'Legacy',
		1	=>	'Vintage',
		2	=>	'Classic',
		3	=>	'Commander',
		4	=>	'Modern',
		5	=>	'Standard',
);
	

//array contenente i massaggi di errori custom delle classi
$msg_error		=	array 
					(
						0 => "Errore nella connessione. non sono riuscito a connettermi al server", 																	// 0
						1 => "Errore nella selezione del database. Il database",																						// 1
						2 => "Errore la query non ha prodotto risultati",																								// 2
						3 => "Errore la funzione send_dinamic_query non ha ricevuto un array valorizzato correttamente",												// 3
						4 => "Errore il metodo richiesto non &egrave; accettato dal metodo send_dinamic_query. I medoti accettati sono INSERT e REPLACE",				// 4
						5 => "Errore impossibile eseguire la query richiesta controllare i dati inseriti: ",																	// 5
						6 => "Query generata: ",																															// 6
						7 => "Errore Mysql: ",
					);				
					
//array contenente i messaggi di successo di alcune classi				
$msg_succes		=	array
					(
						0 => "Complimenti i dati sono stati salvati correttamente nel database",																// 0
						1 => "Linee interessate: ",																										// 1
						2 => "Query eseguita: ",	
					);
					
//inclusioni delle classi necessarie al funzionamento dello script
require (base_path.class_path."mysql_class.php");
require (base_path.class_path."class_generic_rss.php");
require (base_path.class_path."simple_html_dom.php");
require (base_path.class_path."class_couponit.php");
require (base_path.class_path.models_path."class_model_couponit.php");
require (base_path.class_path.models_path."class_model_mtg_sets.php");
require (base_path."/languages/it/translation.php");
require (base_path.class_path.models_path."class_EbayAPI.php");
?>
