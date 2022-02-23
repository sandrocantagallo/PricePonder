<?php

/**
 * PRICEPONDER
 * 
 * Homepage
 * 
 * @version 3.0
 * - inclusione del portale Lurkone Shop all'interno di priceponder
 * - modificato importatore carte adesso esegue tutto in automatico richiamando a browser la pagina
 * 
 * @version 2.5
 * - modificato l'ajax_caller adesso per il portale MagicMarket in caso di html di troppe dipensioni prima di passarlo al parserizzatore ne taglia il contenuto
 * - inserito tracciamento di google analytcs
 * 
 * @version 2.1
 * - tramite jquery viene evidenziato con classe active il prezzo più conveniente recuperato dai siti vendor conosciuti
 * 
 * @version 2.0
 * - implementazione chiamate Ajax per l'analisi dei portali analizzati
 * - generazione del risultati a blocchi sequenziali
 * - creazione dello script jQuery perla gestione delle chiamate Ajax
 * - creazione popup modal boostrap con barra di caricamento
 * 
 * @version 1.7
 * - miglioramento dell'autocompletamento nome carta
 * - visualizzazione del dettaglio della carta ricercata nel caso in cui si trovino risultati
 * - blocco dello script di parserizzazione nel caso in cui la carta non sia presente nel sistema
 * - visualizzazione delle statistiche di base del portale
 * - visualizzazione di un messaggio di benvenuto
 * - ottimizzazione bootstrap delle aree della pagina
 * - creazione del menu social per poter dare i like e sharare il portale sui social network
 * - avviso di cortesia di nessun risultato ottenuto se non ci sono carte in vendita con quel nome o se semplicemente la carta non esiste
 * 
 * @version 1.5
 * - implementazione autocompletamento automatico della carta in lingua inglese
 * - caricamento dell'intero file XML di Cockatrice aggiornato a BOG
 * - modifica della URL di MagicMarket per evitare nella ricerca di far comparire prodotti che non siano carte
 * - modifica del motore di ricerca Ebay adesso la ricerca viene effettuata sulla categoria Magic Card - La ricerca ignora i titoli delle carte con scritto " NO " - Recupera solo il primo risultato visto che la URL imposta ad eEbay già la visualizzazione dell'articolo più economico.
 * 
 * @version 1.0 
 * 
 * - creazione frontend con maschera di ricerca boostrap 3.0 enable
 * - controllo su tre portali di vendita online del mercato italino
 * 		- magic market
 * 		- deck tutor
 * 		- ebay
 */



try {
	
	$multiverseid = (isset($_REQUEST['multiverseid'])) ? $_REQUEST['multiverseid'] : false;

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";*/
	
	
	
	if (status_project != 'sviluppo') { 
		//rendo invisibili tutti gli echo che lo script lancia durante l'esecuzione
		//ob_start();
	}
	//includo il file di configurazione
	require 'includes/config.inc.php';
	//istanzio la classe generica Couponit
	$ccp = new Couponit();
	//istanzio la clase di comunicazione con il db delle carte mtg importate
	$cd_mtg_sets = new Model_mtg_sets();
	
	$cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
	$cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);
	
	//recupero tutte le informazioni riguardanti la carta
	$ay_card_info = $cd_mtg_sets->get_data(table_cards, 'multiverseid', 'DESC', '1', '0', 'WHERE multiverseid = \''.mysql_real_escape_string($multiverseid).'\' ');
	//recupero il nome della carta in tutte le lingue conosciute
	$ay_card_language = $cd_mtg_sets->get_data('mtg_cards_foreignnames', 'multiverseid', 'DESC', '100', '0', 'WHERE multiverseid = \''.mysql_real_escape_string($multiverseid).'\' ');
	//recupero le regole della carta
	$ay_card_rules = $cd_mtg_sets->get_data('mtg_cards_rulings', 'multiverseid', 'DESC', '100', '0', 'WHERE multiverseid = \''.mysql_real_escape_string($multiverseid).'\' ');
	//recupero la legalità della carta
	$ay_card_legality = $cd_mtg_sets->get_data('mtg_cards_legalities', 'multiverseid', 'DESC', '100', '0', 'WHERE multiverseid = \''.mysql_real_escape_string($multiverseid).'\' ');
	//recupero i tipi della carta
	$ay_card_types = $cd_mtg_sets->get_data('mtg_cards_types', 'multiverseid', 'DESC', '100', '0', 'WHERE multiverseid = \''.mysql_real_escape_string($multiverseid).'\' ');
	//adesso cerco di recuperare tutte le edizioni in cui la carta è stata stampata
	$ay_same_cards = $cd_mtg_sets->get_data(table_cards, 'multiverseid', 'DESC', '100', '0', 'WHERE name = \''.mysql_real_escape_string($ay_card_info[0]['name']).'\' ');
	//recupero gli ultimi prezzi della carta salvati dentro al DB
	$ay_price_card = $cd_mtg_sets->get_data('mtg_cards_price', 'shop_code', 'ASC', '5', '0', 'WHERE multiverseid= \''.mysql_real_escape_string($multiverseid).'\' GROUP BY shop_code ');
	
	/*echo "<pre>";
	print_r($ay_price_card);
	echo "</pre>";*/
	
	if (is_array($ay_same_cards)) {
		foreach ($ay_same_cards as $key=>$same_card) {
			
			//recupero il nome della espanzione della carta
			$set = $cd_mtg_sets->get_card_set($same_card['multiverseid']);
			$ay_set_name[$key] = $set[0];
			
		}
	}
	
	/*echo "<pre>";
	print_r($ay_card_info);
	print_r($ay_card_language);
	print_r($ay_card_rules);
	print_r($ay_card_legality);
	print_r($ay_card_types);
	print_r($ay_same_cards);
	print_r($ay_set_name);
	echo "</pre>";*/
	
	//recupero il numero totale di sets che il sistema conosce
	$tot_sets = $cd_mtg_sets->tot_data(table_sets, 'id');
	//recupero il numero totale di carte
	$tot_cards = $cd_mtg_sets->tot_data(table_cards, 'multiverseid');
	//recupero l'ultimo set importato nel db
	$ay_last_set = $cd_mtg_sets->get_data(table_sets, 'releaseDate', 'DESC', '1', '0', '');
	$last_set = $ay_last_set[0]['name'];
	//istanzio le librerie curl
	$ch = curl_init();
	$ay_link = array();
	$ay_conf = array();
	//includo il file di configurazione per Magic Market
	require_once 'includes/confMagicMarket.php';
	require_once 'includes/confDeckTutor.php';
	require_once 'includes/confEbay.php';
	//recupero le keywords che mi occorrono per completare il link
	$keys = ((isset($_REQUEST['keys'])) AND (!empty($_REQUEST['keys']))) ? $_REQUEST['keys'] : '';
	//controllo se esiste la carta nel bd
	$ay_price = array();

	if (status_project != 'sviluppo') {
		//mostro in output tutto quello che viene ora
		//ob_end_clean();
	}
		
	//includo il tpl del portale
	require_once 'tpl/tpl_index.php';
	
} catch (Exception $e) {
	echo "- Method Lunched: generate_log  - ".shell_hv;
	echo "------------------------------------------- ".shell_hv;
	echo $e->getMessage()." ".shell_hv;
	echo $e->getFile()." ".shell_hv;
	echo $e->getLine()." ".shell_hv;
	
}

?>
