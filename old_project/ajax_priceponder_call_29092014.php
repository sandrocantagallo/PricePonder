<?php
/**
 * PRICEPONDER
 * 
 * Homepage
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
	
	
	
	//includo il file di configurazione
	require 'includes/config.inc.php';

	
	
	if (status_project != 'sviluppo') { 
		//rendo invisibili tutti gli echo che lo script lancia durante l'esecuzione
		ob_start();
	}
	
	//istanzio la classe generica Couponit
	$ccp = new Couponit();
	//istanzio la clase di comunicazione con il db delle carte mtg importate
	$cd_mtg_sets = new Model_mtg_sets();
	//recupero il numero totale di sets che il sistema conosce
	$tot_sets = $cd_mtg_sets->tot_data(table_sets, 'id');
	//recupero il numero totale di carte
	$tot_cards = $cd_mtg_sets->tot_data(table_cards, 'multiverseid');
	//istanzio le librerie curl
	$ch = curl_init();
	$ay_link = array();
	$ay_conf = array();
	
	//recupero le keywords che mi occorrono per completare il link
	$keys = ((isset($_REQUEST['keys'])) AND (!empty($_REQUEST['keys']))) ? $_REQUEST['keys'] : '';
	$action = ((isset($_REQUEST['action'])) AND (!empty($_REQUEST['action']))) ? $_REQUEST['action'] : 'show_card';
	$site = ((isset($_REQUEST['site'])) AND (!empty($_REQUEST['site']))) ? $_REQUEST['site'] : 'MagicMarket';
	
	switch ($action) {
		case 'show_card': 
			//recupero anche il dettaglio della carta
			$ay_card = $cd_mtg_sets->get_card($keys);
		break;
		case 'show_price':
			//controllo se esiste la carta nel bd
			$ay_price = array();
			//includo il file di configurazione per Magic Market
			require_once 'includes/conf'.$site.'.php';
			//setto in una determinata variabile della classe il nume della carta
			$ccp->card_name = $keys;
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
						if (($site == 'Lurkoneshop') AND ((isset($href['redirect_url']))) and (!empty($href['redirect_url']))) {
							curl_setopt($ch, CURLOPT_URL, $href['redirect_url']);
						} else  {
							curl_setopt($ch, CURLOPT_URL, $href['url']);
						}
						
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_USERAGENT, $agent);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
						echo "- Method Lunched: curl_exec  ".shell_hv;
						echo "------------------------------------------- ".shell_hv;
			
						//eseguo curl e salvo il contenuto html della pagina in una variabile
						$content = curl_exec($ch);
						
						curl_close($ch);
						
						if (($site == 'MagicMarket') OR ($site == 'Lurkoneshop')) {
							function TagliaStringa($stringa, $max_char){
								if(strlen($stringa)>$max_char){
									$stringa_tagliata=substr($stringa, 0,$max_char);
									$last_space=strrpos($stringa_tagliata," ");
									$stringa_ok=substr($stringa_tagliata, 0,$last_space);
									return $stringa_ok;
								}else{
									return $stringa;
								}
							}
							
							$content = TagliaStringa($content, '80000');
							
						}
						
						
						
						echo "- Method Lunched: read_rules  - ".shell_hv;
						echo "------------------------------------------- ".shell_hv;
			
						echo "<pre>";
						print_r($ay_conf);
						echo "</pre>";
						
						//Lancio il lettore delle regole mandandogli in pasto le regole e il contenuto della pagina HTML
						$ay_price[$key] = $ccp->read_rules($ay_conf[$key], $content);
						$ay_price[$key][0]['url'] =  $link['url'].$url_key;
			
					}
			
				}
				if (status_project != 'sviluppo') {
					//mostro in output tutto quello che viene ora
					ob_end_clean();
				}
			
				foreach ($ay_price as $key=>$price) {
					if (isset($price['valid'])) {
						if (!$price['valid']) {
							unset($ay_price[$key]);
						}
					}
				}
				
				asort($ay_price);
			}
		break;
	}
	
	
	
	//includo il tpl del portale
	require_once 'tpl/tpl_ajax_priceponder_call.php';
	
} catch (Exception $e) {
	echo "- Method Lunched: generate_log  - ".shell_hv;
	echo "------------------------------------------- ".shell_hv;
	echo $e->getMessage()." ".shell_hv;
	echo $e->getFile()." ".shell_hv;
	echo $e->getLine()." ".shell_hv;
	
}

?>