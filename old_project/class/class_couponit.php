<?php
/**
 * Couponit class function
 * 
 * Funzioni generiche per il parserizzatore di coupon della MS3
 */

class Couponit extends GenericRssUtility  {
	
	public 	$modelCouponit,
			$connect_db,
			$feedback,
			$ay_deals,
			$number_deal,
			$card_name;
	
	public function __construct() {
		$this->modelCouponit = new Model_Couponit();
		$this->connect_db = new mysql_db();
		$this->connect_db->throwException(true);
		$this->ay_deals = array();
		$this->number_deal = 0;
		
		
	}
	
	public function __destruct() {
		$this->ay_deals = array();
		$this->feedback = '';
		$this->card_name = '';
	}
	
	
	public function get_crawler_url() {
		//recupero l'ultimo id analizzato dalla tabella urlCrawlerSuffix attraverso la tabella Log
		$urlCrawlerSuffixId = $this->modelCouponit->get_last_urlCrawlerSuffixId();
		
		if (!$urlCrawlerSuffixId) {
			//recupero i dati per comporre il primo feedrss
			$ay_url_feed = $this->modelCouponit->get_url_crawler();	
		} else {
			//recupero il feedrss successivo
			$ay_url_feed = $this->modelCouponit->get_url_crawler($urlCrawlerSuffixId);
		}
		
		return $ay_url_feed;
	}
	
	/**
	 * recupera il coupon da analizzare in base alla lettura del log
	 * 
	 * @return $url_feed_rss
	 * 
	 */
	
	public function get_feedrss()  {
		//controllo quale � stato l'ultimo rss_feed analizzato oggi
		$urlFeedSuffixId = $this->modelCouponit->get_last_urlFeedSuffixId();
		if (!$urlFeedSuffixId) {
			//recupero i dati per comporre il primo feedrss
			$ay_url_feed = $this->modelCouponit->get_url_feed();		
		} else {
			//recupero il feedrss successivo
			$ay_url_feed = $this->modelCouponit->get_url_feed($urlFeedSuffixId);
		}
		
		return $ay_url_feed;
	}
	
	/**
	 * controlla se un prodotto � scaduto e in caso affermativo aggiorna la data di importazione ad oggi per risolvere il problema dei prodotti 
	 * attualmente attivi su letsbonus ma che per via delle loro porcate non sono realmente attivi.
	 *
	 * @param unknown_type $id_product
	 */
	
	public function control_and_refresh_importedTime($id_product) {
		
		//lancio una query per verificare che il prodotto sia scaduto o meno
		$query = "SELECT * FROM ".table_products." AS p WHERE ((p.importedTime+CEILING(p.expirationTime/1000)) > UNIX_TIMESTAMP() OR ((p.expirationTime = 0) AND (p.importedTime+172800 >= UNIX_TIMESTAMP() ))) AND id = '".$this->format_string($id_product)."' ";
		$result = $this->connect_db->send_query($query, db_name);
		
		if ($result['tot_result']==0) {
			//il prodotto � scaduto e quindi mi appresto ad aggiornare la data di importazione ad oggi
			$query_update = "UPDATE ".table_products." SET importedTime = UNIX_TIMESTAMP() WHERE id = '".$this->format_string($id_product)."' ";
			$result_update = $this->connect_db->send_query($query_update, db_name);
			if ($result_update['esito']==1){
				return true;
			} else {
				throw new Exception($result_update['feedback']);
			}
		} else {
			return false;
		}
	}
	
	public function excute_rule($html_code, $rule) {
		
		$data_array = array();
		if (is_array($rule['find'])) {
			foreach ($rule['find'] as $key=>$find) {
				
				$new_rule = array	(	
										'title_rule'	=>	$rule['title_rule'],
										'find'			=>	$find,
										'extract'		=>	$rule['extract'],
										'action'		=>	$rule['action'],
									);
				
				
				
				$ay_data[] = $this->excute_rule($html_code, $new_rule);

				
			}
			
			
			if (is_array($ay_data)) {
			
				foreach ($ay_data as $key=>$data) {
					
					if (is_array($data)) {
						
						foreach ($data as $new_data) {
							
							if (!empty($new_data)) {
								$ay_new_data[] = $new_data;
							}
							
							
						}
						
					}
					
					
				}
				
				echo "<pre>";
				print_r($ay_new_data);
				echo "</pre>";
				
				return $ay_new_data;
			}
		} else {
			
			//eseguo la ricerca e salvo il risultato ottenuto in un array
			foreach ($html_code->find($rule['find']) as $data) {
				//controllo se � stato anche salvato il cosa si vuole estrarre dal componente ricarcato
				if ((isset($rule['extract'])) and (!empty($rule['extract']))) {
					//lancio il case che esegue la funzione in base a il valore della regola
					switch ($rule['extract']) {
						case 'innertext':
							$data_array[] = $data->innertext;
						break;
						case 'src':
							$data_array[] = $data->src;
						break;
						case 'data-original':
							$data_array[] = $data->attr['data-original'];
						break;
						case 'href':
							$data_array[]	= $data->href;
						break;
						case 'title':
							$data_array[]	=	$data->title;
						break;
					}
				}
			}
			if ((is_array($data_array)) AND (count($data_array) > 0)) {
				
				return $data_array;
			} 
		}
		
	}
	
	public function save_title($ay_data) {
		$title	=  '';
		//conto il numero totale di risultati ottenuti
		$tot_result = count($ay_data);
		//se trova un solo risultato sono certo che quello che sto cercando � stato trovato correttamente e mando avanti
		if ($tot_result == 1) {
			//devo ripulire il dato ottenuto da ogni possibile imperfezione o codice html			
			$title  = $this->remove_html_tags_from_text($ay_data[0]);
		} else {
			//mando in errore perch� ho trovato pi� dati di quello che mi aspettavo e non posso procedere con certezza assoluta
			foreach ($ay_data as $data_error_report) {
				$error_text_report .= "Trovato Elemento: ".$data_error_report." - ";
			}
			//avviso del motivo per il quale non ho salvato l'elemento nell'array
			$this->feedback .= "Errore. Ho trovato troppi elementi identificati come Titolo Delas: ".$error_text_report." <br />";
		}
		
		if (empty($title)) {
			$title = strip_tags($ay_data[0]);
		}
	
		return $title;
	}
	
	public function save_summary($ay_data) {
		$error_text_report = '';
		$summary = '';
		//conto il numero totale di risultati ottenuti
		$tot_result = count($ay_data);
		
		if ($tot_result == 1) {
			
			$summary  = $this->remove_html_tags_from_text($ay_data[0]);
			
		} else {
			
			foreach ($ay_data as $data_error_report) {
				$error_text_report .= "Trovato Elemento: ".$data_error_report." - ";
			}
			//avviso del motivo per il quale non ho salvato l'elemento nell'array
			$this->feedback .= "Errore. Ho trovato troppi elementi identificati come Sommari Delas: ".$error_text_report." <br />";
			
		}
		
		return $summary;
		
	}
	
	public function save_old_price($ay_data) {
		
		$old_price = '';
		$error_text_report = '';
		
		//conto il numero totale di risultati ottenuti
		$tot_result = count($ay_data);
		
		if ($tot_result == 1) {
			
			//devo ripulire il dato ottenuto da ogni possibile imperfezione o codice html
			$old_price = $this->get_only_number($ay_data[0]);
			
		} else {
			
			foreach ($ay_data as $data_error_report) {
				$error_text_report .= "Trovato Elemento: ".$data_error_report." - ";
			}
			
			//avviso del motivo per il quale non ho salvato l'elemento nell'array
			$this->feedback .= "Errore. Ho trovato troppi elementi identificati come Prezzo Pieno : ".$error_text_report." \n";
			
		}
		
		return $old_price;
		
	}
	
	public function save_perc($ay_data) {
		
		$perc =  '';
		$error_text_report = '';
		
		//conto il numero totale di risultati ottenuti
		$tot_result = count($ay_data);
		
		if ($tot_result == 1) {
			
			//devo ripulire il dato ottenuto da ogni possibile imperfezione o codice html
			$perc = $this->get_only_number($ay_data[0]);
			
		} else {
			
			foreach ($ay_data as $data_error_report) {
				$error_text_report .= "Trovato Elemento: ".$data_error_report." - ";
			}
			//throw new Exception("Errore. Ho trovato troppi elementi identificati come Percentuale Di Sconto : ".$error_text_report);
			$this->feedback .= "Errore. Ho trovato troppi elementi identificati come Percentuale Di Sconto : ".$error_text_report." \n";
		}
		
		return $perc;
		
	}
	
	public function check_card_name($ay_data) {
		
		echo "- controllo che il nome della carta sia: ".$this->card_name." <br />";
		
		if (is_array($ay_data)) {
			foreach ($ay_data as $data) {
				$data = strip_tags($data);
				if (strtolower($data) == strtolower($this->card_name)) {
					echo "- il nome della carta corrisponde <br />";
					return true;
				} else {
					echo "- il nome ".$data." non corrisponde <br />";
					return false;
				}
			}
		}
		
	}
	
	public function save_best_price_lurkone ($ay_data) {
		$price = $this->get_only_number($ay_data[0], false);
		
		return $price;
	}
	
	public function save_best_price_ebay ($ay_data) {
	
	
		if (is_array($ay_data)) {
			
			$price = $this->get_only_number($ay_data[0], true);
			
			
			
			return $price;
			
		}
	
	}
	
	public function save_best_price_magiccorner($ay_data) {
		if (is_array($ay_data)) {
			foreach ($ay_data as $key=>$data) {
				$check = $this->get_only_number($data, true);
				if (!empty($check)) {
					return $data;
				}
				if (empty($ay_data[$key])) {
					unset($ay_data[$key]);
				}
			}
				
				
			sort($ay_data);
				
			return $ay_data[0];
		}
	}
	
	public function save_best_price($ay_data) {
		
		
		if (is_array($ay_data)) {
			
			echo "<pre>";
			print_r($ay_data);
			echo "</pre>";
			
			foreach ($ay_data as $key=>$data) {
				
				echo "Il prezzo da ripulire &egrave; : ".$data." <br />";
				
				$ay_data[$key] = $this->get_only_number($data, false);
				
				echo "il prezzo &egrave; : ".$ay_data[$key]." <br />";
				
				if (empty($ay_data[$key])) {
					unset($ay_data[$key]);
				}
			}
			
			
			sort($ay_data);
			
			return $ay_data[0];
		}
		
	}
	
	public function save_newprice($ay_data, $old_price=0) {
		

		//se � un array quello che mi viene passato come primo argomento	
		if (is_array($ay_data)) {
		
			$error_text_report = '';
			$new_price = '';
			//conto il numero totale di risultati ottenuti
			$tot_result = count($ay_data);
			
			if ($tot_result == 1) {
				
				//devo ripulire il dato ottenuto da ogni possibile imperfezione o codice html
				$new_price  = $this->get_only_number($ay_data[0]);
				
				/**
				 * @todo non capisco perchè ho scritto questo codice probabilmente per una regola del portale glamoo che applico solamente se ho il prezzo vecchio
				 */
				if (($new_price>$old_price) and ($old_price != 0)) {
	
					$new_price = number_format(str_replace(".", "", $new_price)/100, 2, '.', '');
					
				}
				
			} else {
				
				foreach ($ay_data as $data_error_report) {
					$error_text_report .= "Trovato Elemento: ".$data_error_report." - ";
				}
				//throw new Exception("Errore. Ho trovato troppi elementi identificati come Percentuale Di Sconto : ".$error_text_report);
				$this->feedback .= "Errore. Ho trovato troppi elementi identificati come Percentuale Di Sconto : ".$error_text_report;
			}
		} else {
			
			//ricevo come argomento una stringa a questo punto lancio solo il metodo get_only_number
			$new_price = $this->get_only_number($ay_data, false);
			
		}
		
		return $new_price;
		
	}
	
	public function save_img($ay_data, $url_suffix = '') {
		
		//listo tutti i risultati ottenuti
		foreach ($ay_data as $data) {
		
			//controllare l'estenzione di una immagine far andare bene tutto tranne le gif
			$ch = curl_init($url_suffix.$data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   			curl_exec($ch);
			$ay_info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			
			if ($ay_info != 'image/gif') {
	   			$test = @getimagesize($url_suffix.$data);
				if (is_array($test)) {
					//controllare se il link della immagine esiste.
					$local_img = $this->wget_img($url_suffix.$data, '', '', '600', null);
					return $local_img;
				} else {
					//non sono in presenza di una immagine valida e quindi mi salvo in feedback il link che mi ha dato problemi
					$this->feedback .= "- Errore. Impossibile recuperare immagine dal seguente url: ".$ay_data[0]." \n";
				}
			}
		
		}
		
	}
	
	public function save_long_description($ay_data) {

		$description = '';
		//conto il numero totale di risultati ottenuti
		$tot_result = count($ay_data);
		
		if ($tot_result == 1) {
			
			$description	=	strip_tags($ay_data[0]);
			//$description	=	$this->get_innertext_in_tag_p($ay_data[0]);
			//$description	=	strip_tags($description);
			
		} else {
			foreach ($ay_data as $data_error_report) {
				$description .= trim(strip_tags($data_error_report));
			}
			//avviso del motivo per il quale non ho salvato l'elemento nell'array
			//$this->feedback .= "Errore. Ho trovato troppi elementi identificati come Sommari Delas: ".$error_text_report." <br />";
			
		}
		
		return $description;
		
	}
	
	public function excute_action($action, $ay_data, $number, $url_suffix = '') {
		
		if ((!empty($action)) AND (is_array($ay_data))) {
			
			switch ($action) {
					case 'save_title':		
						$this->ay_deals[$this->number_deal]['title'] = trim($this->save_title($ay_data));
						echo "- Titolo Recuperato: ".$this->ay_deals[$this->number_deal]['title']." \n";
					break;
					case 'check_card_name':
						$this->ay_deals[$this->number_deal]['valid'] = $this->check_card_name($ay_data);
					break;
					case 'save_summary':												
						$this->ay_deals[$this->number_deal]['summary'] = $this->save_summary($ay_data);
					break;
					case 'save_old_price':												
						$this->ay_deals[$this->number_deal]['old_price'] = $this->save_old_price($ay_data);
					break;
					case 'save_perc':												
						$this->ay_deals[$this->number_deal]['perc'] = $this->save_perc($ay_data);
					break;
					case 'save_best_price':
						$this->ay_deals[$this->number_deal]['save_best_price'] = $this->save_best_price($ay_data);
					break;
					case 'save_best_price_ebay':
						$this->ay_deals[$this->number_deal]['save_best_price'] = $this->save_best_price_ebay($ay_data);
					break;
					case 'save_best_price_lurkone':
						$this->ay_deals[$this->number_deal]['save_best_price'] = $this->save_best_price_lurkone($ay_data);
						break;
					case 'save_best_price_magiccorner':
						$this->ay_deals[$this->number_deal]['save_best_price'] = $this->save_best_price_magiccorner($ay_data);
						break;
					case 'save_newprice':	
						if (isset($this->ay_deals[$this->number_deal]['old_price']))	{									
							$this->ay_deals[$this->number_deal]['newprice'] = $this->save_newprice($ay_data, $this->ay_deals[$number]['old_price']);
						} else {
							$this->ay_deals[$this->number_deal]['newprice'] = $this->save_newprice($ay_data, 0);
						}
					break;
					case 'save_img':												
						$this->ay_deals[$this->number_deal]['img'] = $this->save_img($ay_data, $url_suffix);
					break;
					case 'save_expirationTime':
						//prendo come valore utlizzabile solo il primo risultato dell'array ne recupero solo il dato numerico e lo moltiplico per i millesimi di secondo che compongono un giorno
						$day = $this->get_only_number($ay_data[0]);
						echo "- Giorni alla scadenza: ".$day." \n";
						$time = $day*86400; //da giorni in secondi
						$time  = $time*1000; //millessimi di secondi per la scadenza
						$this->ay_deals[$this->number_deal]['expirationTime'] = $time;
					break;
					case 'save_expirationTime_Balinea':
						/**
						 * Mi aspetto come stringa qualcosa del genere: RŽservation possible jusqu'au : 31/07/2013
						 * Devo eliminare dalla stringa qualsiasi cosa che non sia una data. Una volta ottenuta
						 * la data precisa la converto in millesimi di secondo di scadenza rispetto ad oggi.
						 */
						//$date = str_replace("RŽservation possible jusqu'au : ", "", $ay_data[0]);
						$ay_date = explode(":", $ay_data[0]);
						$date = trim($ay_date[1]);						
						//ottenuta la data la trasformo in unixtime
						$ay_date = explode("/", $date);
						$date	= mktime('0', '0', '0', $ay_date[1], $ay_date[0], $ay_date[2]);
						//$date = strtotime($date);
						$today = time();
						$expirationtime = $date-$today;
						if ($expirationtime>0) {
							$expirationtime = $expirationtime*1000; //converto i secondi in millesimi di secondo
						} else {
							$expirationtime = 0;
						}
						$this->ay_deals[$this->number_deal]['expirationTime'] = $expirationtime;						
					break;
					case 'save_link':
						//controllo se il link ha un formato valido
						$url = $ay_data[0];
						$ch = curl_init($url); //inizializzo lÕoggetto curl
						curl_setopt($ch, CURLOPT_NOBODY, 1); // dico che voglio in risposta solo gli header
						curl_exec($ch); //eseguo
						$resp = curl_getinfo($ch, CURLINFO_HTTP_CODE); //memorizzo la risposta
						echo $ay_data[0]."\n";
						if ((int)$resp==200) { //analizzo la risposta
						// va bene
							$this->ay_deals[$this->number_deal]['link'] = $ay_data[0];
						} else {
							// non va bene devo associare l'url base del portale ma come ?
							//$this->ay_deals[$this->number_deal]['link'] = $ay_data[0];
							$this->ay_deals[$this->number_deal]['link'] = $url_suffix.$ay_data[0];
			
						}
					break;
					case 'save_long_description':
						$this->ay_deals[$this->number_deal]['description'] = $this->save_long_description($ay_data);
					break;
			}
		}
		
	}
	
	public function read_rules($ay_rules, $content, $max_deals_limit = 10) {
		//$this->feedback .= "- Check If argument of method are right configured  ";
		echo "- Controllo se sono stati passati al metodo dei validi argomenti: ".shell_hv;
		if ((is_array($ay_rules)) AND (!empty($content))) {
						
			//$this->feedback .= "- Ok (ay_rules is a array and content isn't empty and number is numeric) \n";
			echo "- Ok (ay_rules e un array, content non e vuoto) ".shell_hv;
			
			if (is_array($content)) {
				
				//$this->feedback .= "- var content is an array of html code so i list this array and recall read_rules method \n";
				//$this->feedback .= "------------------------------------------- \n";
				echo "- La var content e un array composto da codici html devo quindi listare l'array richiamando per ogni record il metodo read_rules ".shell_hv;
				
				//� arrivato come argomento non l'intera pagina html ma un array contenente porzioni di codice html e quindi li devo analizzare tutti e per ogni porzione devo eseguire la regola indicata
				foreach ($content as $num=>$data) {
					if ($this->number_deal <= $max_deals_limit) {
						//$this->feedback .= "- get html_code number: ".$num." \n";
						echo "- Recupero il record numero: ".$num." contenente html_code  ".shell_hv;
						//ridondo la classe richiamando se stessa ma con la singola porzione di html da analizzare
						//$this->feedback .= "- call read_rules with the last rules and html_code number: ".$num." \n";
						echo "- Richiamo il metodo read_rules con l'ultimo array di regole e il record singolo number: ".$num." contenente html_code ".shell_hv;
						$this->read_rules($ay_rules, $data, $max_deals_limit);
						//$this->feedback .= "------------------------------------------- \n";
					}
				}
				
			} else {
				//$this->feedback .= "- launch all rules on selected html_code \n";
				echo "- Listo tutto le regole sul record html_code selezionato ".shell_hv;
				//inizio la lettura delle regole
				foreach ($ay_rules as $rule) {
					//$this->feedback .= "- create an object of html doom parser for selected html_code \n";
					echo "- istanzio nella classe html_doom_parser il record contenente html_code ".shell_hv;
					//mando al parserizzatore html doom il codice html dell'intera pagina html
					$html_code = str_get_html($content);
					//controllo se nell'array � realmente presente una ricerca da compiere
					if ((isset($rule['find'])) and (!empty($rule['find']))) {
						
						if (($rule['title_rule'] == 'Ricerca Del Titolo Del Deals') OR ($rule['title_rule'] == 'Controllo corrispondenza sul titolo')) {
							
							$this->number_deal++;
							
							echo "------------------------------------------- ".shell_hv;
							echo "- Analizzo un Deals e lo inserisco come record numero: ".$this->number_deal." ".shell_hv;
							
						}
						
						//$this->feedback .= "- launch rule: ".$rule['title_rule']." \n";
						echo "- Lancio la regola: ".$rule['title_rule']." ".shell_hv;
						//eseguo il metodo di ricerca impostato sull'array delle regole
						$ay_data = $this->excute_rule($html_code, $rule);
						
						//a termine dell'esecuzione della regola mi accerto che quanto ottenuto sia un array valido
						if ((is_array($ay_data)) AND (count($ay_data) > 0)) {
							
							//controllo se devo eseguire delle action sui contenuti ottenuti
							if ((isset($rule['action'])) and (!empty($rule['action']))) {
								
								//$this->feedback .= "- an action is required. Launch action: ".$rule['action']." ";
								echo "- Lancio l'azione: ".$rule['action']." verra salvata nel deal numero ".$this->number_deal." ";
								//lancio il metodo per eseguire l'action indicata
								if (($rule['action'] == 'save_link') OR ($rule['action'] == 'save_img')) {
									//mando come argomento anche la url originale del portale che sto analizzando
									$this->excute_action($rule['action'], $ay_data, $this->number_deal, $rule['url_suffix']);
								} else {
									$this->excute_action($rule['action'], $ay_data, $this->number_deal);
								}
								
								echo "- Ok ".shell_hv;
							}

							//controllo se devo eseguire delle sotto regole della regola appena lanciata
							if ((isset($rule['rules'])) and (count($rule['rules'])>0)) {
								//$this->feedback .= "- Find subrules recall read_rules() method with this subrules \n";
								echo "- Ho trovato delle sotto regole devo lanciare nuovamente il metodo read_rules associato lo stesso record numero: ".$this->number_deal." contenente html_code e il nuovo array di regole. ";
								//ridondo la classe richiamanto se stessa ma con la sotto regole prese in esame
								
								echo "<pre>";
									print_r($ay_data);
								echo "</pre>";
								
								echo "- Numero massimo di prezzi che recupero: ".$max_deals_limit;
								
								$this->read_rules($rule['rules'], $ay_data, $max_deals_limit);
								//$this->feedback .= "------------------------------------------- \n";
							}
						} else {
							//$this->feedback .= "- Error. No html data found with this rules. So no action and no subrules can be launced \n ";
							echo "- Errore. La ricerca non ha prodotto nessuna porzione di codice html valido da analizzare. Annullo tutte le azioni e le regole associate a questa porzione di codice html.";
						}
					} else {
						
						//$this->feedback .= "- launch rule: ".$rule['title_rule']." \n";
						echo "- Lancio la regola: ".$rule['title_rule']." ".shell_hv;
						//controllo se invece di una ricerca � stata richiesta una acquisizione di dati da un nuovo link hmtl
						if ((isset($rule['get_htmlcode'])) and (!empty($rule['get_htmlcode']))) {
							$ch = curl_init();
							//recupero il varo link della pagina
							echo "- Recupero interno contenuto della pagina web: ".$this->ay_deals[$this->number_deal][$rule['get_htmlcode']]."  ".shell_hv;
							$href = $this->get_web_page($this->ay_deals[$this->number_deal][$rule['get_htmlcode']]);
							//setto le opzioni curl per il recupero del codice
							curl_setopt($ch, CURLOPT_URL, $href['url']);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							//eseguo curl e salvo il contenuto html della pagina in una variabile
							$htmlcode_content = curl_exec($ch);
							//ora che ho nuovamente del codice html valido da analizzare richiamo in maniera ridondante nuovamente il metodo read_rules con il nuovo contenuto e le sotto regole
							if ((isset($rule['rules'])) and (count($rule['rules'])>0)) {
								//$this->feedback .= "- Find subrules recall read_rules() method with this subrules \n";
								echo "- Ho trovato delle sotto regole richiamo il metodo read_rules con l'array di sotto regole nuove e il vecchio codice html associato al deals: ".$this->number_deal." ".shell_hv;
								//ridondo la classe richiamanto se stessa ma con la sotto regole prese in esame
								$this->read_rules($rule['rules'], $htmlcode_content, $max_deals_limit);
								//$this->feedback .= "------------------------------------------- \n";
							}
						}
					}
				}	
			}
			
		} else {
			throw new Exception('il medoto lanciato necessita di un array e di una variabile contenente del codice html per funzionare');
		}
		return $this->ay_deals;
	}
	
	public function save_crawler_deals($ay_deals, $url) {
		
		echo "- Controllo che al metodo save_crawler_deals sia stato passato un array \n";
		
		if (is_array($ay_deals)){
			
			foreach ($ay_deals as $deal) {
				if (isset($deal['title'])) {
					echo "- Controllo se il deal avente titolo: ".$deal['title']." e gia presente nel sistema \n";
					if ($this->modelCouponit->check_double_content($deal['title'])) {
						echo "- deal gia presente nel sistema controllo se devo associarlo alla categoria analizzata \n";
						//recupero id del prodotto giˆ presente nel sistema
						$ay_product = $this->modelCouponit->get_product($deal['title'], 'title');
						echo "- Associo il prodotto alla categoria id: ".$url['categoryId']." \n";
						$this->modelCouponit->ins_categoryAssociation($ay_product['id'], $url['categoryId']);
					} else {
						echo "- Deal non presente nel sistema procedo al salvataggio \n";
						if (!isset($deal['expirationTime'])) {
							//imposto una scadenza forfettaria di una settimana
							$deal['expirationTime'] = (((60*60)*24)*7)*1000;
						}
						$ay_product = array	(
										'partnerId'			=>	$url['partnerId'],
										'importedTime'		=>	time(),
										'expirationTime'	=>	$deal['expirationTime'],
										'title'				=>	$this->format_string($deal['title']),
										'link'				=>	$this->format_string($deal['link']),
										'description'		=>	$this->format_string($deal['description']),
										'price'				=>	$deal['newprice'],
										'price_old'			=>	$deal['old_price'],
										'summary'			=>	$this->format_string($deal['summary']),
										'img'				=>	$this->format_string($deal['img']),
									);
						$this->modelCouponit->ins_product(false, false, $ay_product);
						//recupero l'id del prodotto appena inserito
						$id_product = mysql_insert_id();
						echo "- Deal inserito correttamente id associato: ".$id_product;
						echo "- Associo il deal importato all'interno della categoria di riferimento \n";
						//inserisco la relazione tra categoria e prodotto
						$this->modelCouponit->ins_categoryAssociation($id_product, $url['categoryId']);
						echo "- Associo il deal alle sottocategorie presenti \n";
						$this->modelCouponit->ins_sub_categoryAssociation($id_product, $deal, $url);
						
					}
				} else {
					echo "- Del deals non e stato recuperato il titolo non procedo con il suo salvataggio. \n";
					//print_r($deal);
				}
			}
			
		} else {
			throw new Exception('il metodo save_crawler_deals necessita di un array passato come argomento per funzionare.');
		}
		
	}
	
	public function get_all_PostParam($id) {
		//controllo che al metodo sia stato inviato un valore numerico
		if (is_numeric($id)) {
			
			//lancio il model per recuperare i dati dal db
			$ay_db_data = $this->modelCouponit->get_PostParam($id);
			//trasformo l'array ottenuto in un array di regole leggiibili per il crawler
			if (is_array($ay_db_data)) {
				
				foreach ($ay_db_data as $data) {
					$ay_param[$data['paramName']] = urlencode($data['paramValue']);
				}
				
			}
			
			return $ay_param;
			
		} else {
			throw new Exception('il metodo get_all_PostParam necessita di un numero rappresentante ID di un suffisso carwler per funzionare');
		}
	}
	
}
?>