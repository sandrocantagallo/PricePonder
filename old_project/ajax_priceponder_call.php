<?php
/**
 * PRICEPONDER
 * 
 * Homepage
 * 
 * @version 1.8.1
 * - modifica alla chiamata API di Magic Market. Adesso la chiamata non recupera il prezzo medio ma semplicemente il prezzo più basso di acquisto
 * - modificato il recupero delle ultime carte ricercate. Adesso vengono prese le ultime carte e non le prime, inoltre da 100 risultati siamo scesi a 30
 *
 * @version 1.8
 * 
 * - modifica alla struttura del database
 * - implementazione del multilingua nella selezione del nome della carta
 * - fixing dell'importazione su magic corner
 * - utilizzo delle api di magic market per il recupero del prezzo delle carte
 * - utilizzo della chiamata file_get_contents invece delle librerie curl su determinati portali
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
	$cd_mtg_sets->connect_db->send_query('SET NAMES utf8', db_name);
	$cd_mtg_sets->connect_db->send_query('SET CHARACTER SET utf8', db_name);
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
		
     
			switch ($site) {
        case 'Ebay':
        
        $app_name = 'SandroCa-ebaypric-PRD-d2466ad44-30d6d5dd'; // Enter your app name API credentials here.
        $ebay = new eBay($app_name);
        
        
        // Perform a FindingService call:
        $search_result = $ebay->finding->findItemsByKeywords(
            array(
                'keywords' => urlencode("mtg ".$keys), // Your search terms.
                'affiliate.networkId' => '9', // 9 = eBay Partner Network.
                'affiliate.trackingId' => '5338050500', // eBay Partner Network Campaign ID.
                'paginationInput.entriesPerPage' => '3', // Number of results to display on a single page.
                'sortOrder' => 'PricePlusShippingLowest', // Sort, showing lowest priced items first.
                //'itemFilter(0).name' => 'ListingType',
                //'itemFilter(0).value' => 'AuctionWithBIN', // Only show "Buy It Now" listings.
                'categoryId' => '139973' // Only search the Video Games category
            )
        );
        
        $best_price[0] = $ebay->returnBestPrice($search_result);
        
        
        $ay_card = $cd_mtg_sets->get_card($keys);
             	$ay_price['eBay'] = $best_price;
						$ay_cards_price = array (
								'multiverseid'	=>	$ay_card['multiverseid'],
								'shop_code'		=>	$site,
								'price'			=>	$best_price[0]['save_best_price'],
						);
						
						
        if ( (is_numeric($ay_cards_price['multiverseid'])) AND (!empty($ay_cards_price['price'])) )
				$cd_mtg_sets->ins_data('mtg_cards_price', $ay_cards_price);
				
       
        break;
				case 'MagicMarket':
					//controllo che la keys rappresenti realmente una carta presente nel portale
					$ay_price = array();
					//setto in una determinata variabile della classe il nume della carta
					$ay_card = $cd_mtg_sets->get_card($keys);
					$ccp->card_name = $ay_card['name'];
					//includo il file di configurazione per Magic Market
					require_once 'includes/conf'.$site.'.php';
					//controllo se il prezzo è già stato recuperato nell'arco delle 24h e in tal caso riutilizzo quel valore
					$best_price = $cd_mtg_sets->get_last_price($ay_card['multiverseid'], $site, true);
						//$best_price = false;
					
					if ((!empty($keys)) AND ($cd_mtg_sets->check_double_content(table_cards, 'name', $ay_card['name'])) AND ($best_price == false)) {
						
					/*	echo "------------------------------------------- ".shell_hv;
						echo "- Price Ponder ".shell_hv;
						echo "- Execution: ".date("d-m-Y", time())." ".shell_hv;
						echo "------------------------------------------- ".shell_hv;*/
						
						//setto in una variabile la URL che la libreria auth di magic market deve invocare
						$url = "https://www.mkmapi.eu/ws/v1.1/products/".urlencode($ay_card['name'])."/1/1/1";
						
						//$url = str_replace("%2C", ",", $url);
						
						//echo $url;
						//includo la libreria auth in maniera tale da ricevere in cambio il flusso XML corretto
						require_once 'mmApi/auth.php';
						
						//ora dovrei avere una variabile con all'interno i dati della carta e posso procedere alla loro lettura
						//echo "voglio star con tigo!";
						
						//echo $decoded->product[0]->priceGuide->TREND;
						/*echo "<pre>";
						print_r($decoded);
						echo "</pre>";*/
            
						$ay_price['MagicMarket'] = array	(
								0	=> array(
										'save_best_price'	=>	$decoded->product[0]->priceGuide->LOW,
										'url'				=>	"https://it.magiccardmarket.eu".$decoded->product[0]->website,
								),
								);
						$ay_cards_price = array (
								'multiverseid'	=>	$ay_card['multiverseid'],
								'shop_code'		=>	$site,
								'price'			=>	$decoded->product[0]->priceGuide->LOW,
						);
						
						if ((isset($decoded->product[0]->priceGuide->TREND)) AND (!empty($decoded->product[0]->priceGuide->TREND)) AND ($decoded->product[0]->priceGuide->TREND > 0)) {
							$cd_mtg_sets->ins_data('mtg_cards_price', $ay_cards_price);
						}
					} else {
						
						
						foreach ($ay_link as $key=>$link) {
							$url_key = str_replace(" ", $link['sep-key'], $ay_card['name']);
							$href = $ccp->get_web_page($link['url'].$url_key);
							$ay_price[$key][0]['save_best_price'] = $best_price;
							$ay_price[$key][0]['url'] = $href['url'];
						}
					}
					
					if ((!isset($ay_price[$key][0]['save_best_price'])) OR ($ay_price[$key][0]['save_best_price'] == 0)) {
						//controllo se esiste la carta nel bd
						$ay_price = array();
						//includo il file di configurazione per Magic Market
						require_once 'includes/conf'.$site.'.php';
						//setto in una determinata variabile della classe il nume della carta
						$ay_card = $cd_mtg_sets->get_card($keys);
						$ccp->card_name = $ay_card['name'];
							
						//controllo se il prezzo è già stato recuperato nell'arco delle 24h e in tal caso riutilizzo quel valore
						$best_price = $cd_mtg_sets->get_last_price($ay_card['multiverseid'], $site, true);
							
							
						if (((!empty($keys)) AND ($cd_mtg_sets->check_double_content(table_cards, 'name', $ay_card['name']))) AND ($best_price == false)) {
								
							echo "------------------------------------------- ".shell_hv;
							echo "- Price Ponder ".shell_hv;
							echo "- Execution: ".date("d-m-Y", time())." ".shell_hv;
							echo "------------------------------------------- ".shell_hv;
								
							if ((isset($ay_link)) AND (is_array($ay_link))) {
								foreach ($ay_link as $key=>$link) {
										
									$url_key = str_replace(" ", $link['sep-key'], $ay_card['name']);
										
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
									if ($site == 'MagicCorner') {
										//uso la chiamata file_get_contents al posto delle librerie curl perchè altrimenti non ottengo la parte centrale del portale
										$content = file_get_contents($href['url']);
									} else {
										$content = curl_exec($ch);
									}
										
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
									$ay_price[$key][0]['url'] =  $href['url'];
						
									//salvo il dato in DB
									$ay_cards_price = array (
											'multiverseid'	=>	$ay_card['multiverseid'],
											'shop_code'		=>	$site,
											'price'			=>	$ay_price[$key][0]['save_best_price'],
									);
									
									if (!empty($ay_cards_price['price'])) {
									
										$cd_mtg_sets->ins_data('mtg_cards_price', $ay_cards_price);
						
									}
								}
						
							}
							if (status_project != 'sviluppo') {
								//mostro in output tutto quello che viene ora
								ob_end_clean();
							}
								
								
								
							$best_price = false;
								
							if (isset($ay_price['MagicMarket'])) {
									
								foreach ($ay_price['MagicMarket'] as $key=>$price) {
									if (isset($price['valid'])) {
										if ($price['valid'] != 1) {
											unset($ay_price['MagicMarket'][$key]);
										} else {
											if ((!$best_price) OR ($price['save_best_price'] < $best_price)) {
												$best_price = $price['save_best_price'];
											}
										}
									}
										
									if (empty($price['save_best_price'])) {
										unset($ay_price['MagicMarket'][$key]);
									}
								}
						
						
								//echo "il migliore prezzo &egrave: ".$best_price." <br />";
						
								unset($ay_price['MagicMarket']);
						
								$ay_price['MagicMarket'] = array	(
										0	=> array(
												'save_best_price'	=>	$best_price,
										),
								);
							}
								
							asort($ay_price);
						
						} else {
							foreach ($ay_link as $key=>$link) {
								$url_key = str_replace(" ", $link['sep-key'], $ay_card['name']);
								$href = $ccp->get_web_page($link['url'].$url_key);
								$ay_price[$key][0]['save_best_price'] = $best_price;
								$ay_price[$key][0]['url'] = $href['url'];
							}
						}
					}
					
				break;
				default:
					//controllo se esiste la carta nel bd
					$ay_price = array();
					//includo il file di configurazione per Magic Market
					require_once 'includes/conf'.$site.'.php';
					//setto in una determinata variabile della classe il nume della carta
					$ay_card = $cd_mtg_sets->get_card($keys);
					$ccp->card_name = $ay_card['name'];
					
					//controllo se il prezzo è già stato recuperato nell'arco delle 24h e in tal caso riutilizzo quel valore
					$best_price = $cd_mtg_sets->get_last_price($ay_card['multiverseid'], $site, true);
					
					
					if (((!empty($keys)) AND ($cd_mtg_sets->check_double_content(table_cards, 'name', $ay_card['name']))) AND ($best_price == false)) {
							
						echo "------------------------------------------- ".shell_hv;
						echo "- Price Ponder ".shell_hv;
						echo "- Execution: ".date("d-m-Y", time())." ".shell_hv;
						echo "------------------------------------------- ".shell_hv;
							
						if ((isset($ay_link)) AND (is_array($ay_link))) {
							foreach ($ay_link as $key=>$link) {
									
								$url_key = str_replace(" ", $link['sep-key'], $ay_card['name']);
									
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
								if ($site == 'MagicCorner') {
									//uso la chiamata file_get_contents al posto delle librerie curl perchè altrimenti non ottengo la parte centrale del portale
									$content = file_get_contents($href['url']);
								} else {
									$content = curl_exec($ch);
								}
					
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
								$ay_price[$key][0]['url'] =  $href['url'];
								
								//salvo il dato in DB
								$ay_cards_price = array (
															'multiverseid'	=>	$ay_card['multiverseid'],
															'shop_code'		=>	$site,
															'price'			=>	$ay_price[$key][0]['save_best_price'],
														);
								
								if (!empty($ay_cards_price['price'])) {
									$cd_mtg_sets->ins_data('mtg_cards_price', $ay_cards_price);
								}
								
							}
								
						}
						if (status_project != 'sviluppo') {
							//mostro in output tutto quello che viene ora
							ob_end_clean();
						}
					
					
							
						$best_price = false;
					
						if (isset($ay_price['MagicMarket'])) {
					
							foreach ($ay_price['MagicMarket'] as $key=>$price) {
								if (isset($price['valid'])) {
									if ($price['valid'] != 1) {
										unset($ay_price['MagicMarket'][$key]);
									} else {
										if ((!$best_price) OR ($price['save_best_price'] < $best_price)) {
											$best_price = $price['save_best_price'];
										}
									}
								}
					
								if (empty($price['save_best_price'])) {
									unset($ay_price['MagicMarket'][$key]);
								}
							}
								
								
							//echo "il migliore prezzo &egrave: ".$best_price." <br />";
								
							unset($ay_price['MagicMarket']);
								
							$ay_price['MagicMarket'] = array	(
									0	=> array(
											'save_best_price'	=>	$best_price,
									),
							);
						}
					
						asort($ay_price);

					} else {
						
						//devo ricavare la url del prodotto
						foreach ($ay_link as $key=>$link) {
							$url_key = str_replace(" ", $link['sep-key'], $ay_card['name']);
							$href = $ccp->get_web_page($link['url'].$url_key);
						}
						
						foreach ($ay_link as $key=>$link) {
							$ay_price[$key][0]['save_best_price'] = $best_price;
							$ay_price[$key][0]['url'] = $href['url'];
						}
					} 
						
				break;
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