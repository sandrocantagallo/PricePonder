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
	if (status_project != 'sviluppo') { 
		//rendo invisibili tutti gli echo che lo script lancia durante l'esecuzione
		ob_start();
	}
	//includo il file di configurazione
	require 'includes/config.inc.php';
	//istanzio la classe generica Couponit
	$ccp = new Couponit();
	//istanzio la clase di comunicazione con il db delle carte mtg importate
	$cd_mtg_sets = new Model_mtg_sets();
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
	if ((!empty($keys)) AND ($cd_mtg_sets->check_double_content(table_cards, 'name', $keys))) {
	
		echo "------------------------------------------- ".shell_hv;
		echo "- Price Ponder ".shell_hv;
		echo "- Execution: ".date("d-m-Y", time())." ".shell_hv;
		echo "------------------------------------------- ".shell_hv;
		
		if ((isset($ay_link)) AND (is_array($ay_link))) {
			foreach ($ay_link as $key=>$link) {
				
				$url_key = str_replace(" ", $link['sep-key'], $keys);
				
				echo "- Analizzo Portale: ".$key." ".shell_hv;
				echo "- Ricerco la stringa: ".$url_key." ".shell_hv;
				echo "- Url generata: ".$link['url'].$url_key.shell_hv;
				echo "------------------------------------------- ".shell_hv;
				
				//recupero il vero link da analizzare attraverso la libreria CURL
				$href = $ccp->get_web_page($link['url'].$url_key);
				
				if (((isset($href['redirect_url']))) and (!empty($href['redirect_url']))) {
					$new_url = $href['redirect_url'];
					unset($href);
					$href = $ccp->get_web_page($new_url);
				}
				
				
				$agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0';
				//setto le opzioni curl per il recupero del codice
				curl_setopt($ch, CURLOPT_URL, $href['url']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, $agent);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				
				echo "- Method Lunched: curl_exec  ".shell_hv;
				echo "------------------------------------------- ".shell_hv;
	
				//eseguo curl e salvo il contenuto html della pagina in una variabile
				$content = curl_exec($ch);
				
				echo "- Method Lunched: read_rules  - ".shell_hv;
				echo "------------------------------------------- ".shell_hv;
				
				//Lancio il lettore delle regole mandandogli in pasto le regole e il contenuto della pagina HTML
				$ay_price[$key] = $ccp->read_rules($ay_conf[$key], $content);
				$ay_price[$key][0]['url'] =  $link['url'].$url_key;
				
			}
		
		}
		if (status_project != 'sviluppo') {
			//mostro in output tutto quello che viene ora
			ob_end_clean();
		}
		
		asort($ay_price);
		
		//recupero anche il dettaglio della carta
		$ay_card = $cd_mtg_sets->get_card($keys);
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