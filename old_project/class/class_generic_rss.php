<?php
/**
 * 
 */

class GenericRssUtility {
	
	public	$connect_db;
	
	/**
	 * Enter description here...
	 *
	 */
	
	public function __construct() {

		$this->connect_db = new mysql_db();
		$this->connect_db->throwException(true);
		
		
		
	}
	
	/**
	 * funzione per reiterare la classe
	 *
	 * @return $instance
	 */
	public static function getInstance() {
		static $instance;
		if (!$instance) {
			$instance = new self();
		}
		return $instance;
	}
	
	/**
	 * distruttore della classe
	 *
	 */
	public function __destruct() {
		
	}
	
	/**
	 * Funzione per attivare la segnalazione di eccezioni.
	 *
	 * @param unknown_type $throwException
	 * @return unknown
	 */
	public function throwException($throwException) {
		$this->throwException = $throwException;
		return $this;
	}
	
	/**
	 * Funzione per formattare gli argomenti pervenuti nei vari metodi della classe per evitare errori o possibili attacchi informatici
	 *
	 * @param unknown_type $string
	 * @return unknown
	 */
	public function format_string($string) {
		$this->connect_db->connetti();
		$string = mysql_real_escape_string($string);
		return $string;
		
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $url
	 * @return unknown
	 */
	public function get_web_page( $url ) 
	{ 
	    $options = array( 
	        CURLOPT_RETURNTRANSFER => 	true,     // return web page 
	        CURLOPT_HEADER         => 	true,    // return headers 
	        CURLOPT_FOLLOWLOCATION => 	true,     // follow redirects 
	        CURLOPT_ENCODING       => 	"",       // handle all encodings 
	        CURLOPT_USERAGENT      => 	"Mozilla/5.0 (Windows NT 6.2; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0", // who am i 
	        CURLOPT_AUTOREFERER    => 	true,     // set referer on redirect 
	        CURLOPT_CONNECTTIMEOUT => 	120,      // timeout on connect 
	        CURLOPT_TIMEOUT        => 	120,      // timeout on response 
	        CURLOPT_MAXREDIRS      => 	10,       // stop after 10 redirects 
	    	CURLOPT_SSL_VERIFYPEER	=>	false,
	    ); 
	    
	    
	    
	    $ch      = curl_init( $url ); 
	    curl_setopt_array( $ch, $options ); 
	    $content = curl_exec( $ch ); 
	    $err     = curl_errno( $ch ); 
	    $errmsg  = curl_error( $ch ); 
	    $header  = curl_getinfo( $ch ); 
	    curl_close( $ch ); 
	
	    //$header['errno']   = $err; 
	   // $header['errmsg']  = $errmsg; 
	    //$header['content'] = $content; 
	    //print($header[0]); 

	    return $header; 
	}  
	
	/**
	 * ritorna il testo formattato correttamente con codice html entities presenti
	 *
	 * @param unknown_type $text
	 */
	
	public function format_utf8_text($text, $clear = true) {
		if ($text != '') {
			
			
			$search = '/<img.*?>/i';
			$replace = '';
			$output = preg_replace ($search, $replace, $text);
			
			if ($clear) {
				//elmino i tag html eventualmente presenti nel testo che invece deve essere completamente pulito
				$formated_text = strip_tags($output);
			}
			//decodifico gli eventuali html entities presenti nel testo trasformandoli nei loro caratteri
			$formated_text = utf8_encode(html_entity_decode($formated_text));
			
			//formatto tutti i caratteri speciali in entity html
			$formated_text = htmlentities($formated_text, ENT_QUOTES, 'UTF-8');
			
			

			return $formated_text;
			
		} else {
			throw new Exception('Errore. Il metodo necessita di una stringa per funzionare.');
		}
	}
	
	public function remove_html_tags_from_text($text) {
		
		if (is_string($text)) {
			//rimuovo i tag html che contengono anche del testo come i link o gli elenchi puntati
			$formated_text = $this->strip_tags_content($text);
			//rimuovo gli eventuali tag html singoli come le immagini rimaste
			$formated_text = strip_tags($formated_text);
			
			if ($formated_text == '') {
				//se il testo � vuoto significa che ho strippato troppo o che il testo principale � tra due tag e quindi devo ripulire in maniera inprecisa il testo
				$formated_text = strip_tags($text);
			}
			
			//elimino i fastidiosi caratteri - che Letsbonus inserisce di continuo nei suoi prodotti
			$formated_text = str_replace("-", " ", $formated_text);
			
			return $formated_text;
			
		} else {
			throw new Exception('Errore. il metodo remove_html_tags_form_text necessita di una stringa passata come metodo per funzionare.');
		}
	}
	
	public function get_final_img_content($ay_images, $base_url, $custom_prefix='', $width='150', $height='150' ) {
		if (is_array($ay_images)) {
			//una volta recuperato un array con le immagini procedo a caricare queste immagini sul mio server
			$local_img = false;
			foreach ($ay_images as $img) {
				if (!empty($img) and (!$local_img)) {
					//lancio il metodo che salva l'immagine da remoto e la inserisce nella mia cartella immagini in locale
					$local_img = $this->wget_img($img, $base_url, $custom_prefix, $width, $height);
					//$local_img = $img;
				} 
			}
		} else {
			$local_img = '';
		}
		if (!$local_img) {
			$local_img	= '';
		}
		return $local_img;
	}
	
	public function get_final_text_content($ay_content, $clean = true) {
		$testo_completo = '';
		if (is_array($ay_content)) {	
			//genero il corpo dell'articolo utilizzando i valori presenti nell'array contenuto
			foreach ($ay_content as $paragrafo) {
				if (!empty($paragrafo)) {
					$testo_completo .= $paragrafo;
				}
			}
			if ($clean) {
				$testo_completo = strip_tags($testo_completo);
			}
		} else {
			$testo_completo = '';
		}
		
		return $testo_completo;
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $content
	 * @param unknown_type $case
	 */
	
	public function get_content_from_portal($content, $case, $custom_prefix='') {
		
		if ((!empty($content)) AND (!empty($case))) {
			
			switch ($case) {
				case 'http://elle.abril.com.br':
					//mando in pasta al Simply DOOM html il codice Html della pagina recuperata
					$html = str_get_html($content);
					
					//ricerco nella pagina html il div contenitore dell'articolo
					/*
					 * CERCARE ALL'INTERNO DEL DIV
					 * <div id="conteudoTexto"></div>
					 * RECUPERARE TUTTO IL TESTO INSERITO ALL'INTERNO DEL TAG <p></p>
					 * RECUPERARE SEMPRE ALL'INTERNO DI QUEL DIV TUTTE LE IMMAGINI <img \>
					 */
					
					foreach ($html->find('#conteudoTexto') as $a) {
						$contenudoText = $a->innertext;
					}
					
					$html_contenudoText = str_get_html($contenudoText);
					//una volta rippato il div del contenuto vado a cercare il testo della notizia
					foreach ($html_contenudoText->find('p') as $b) {
						$contenuto[] = $b->innertext;
						
					}
					
					//ottengo il testo completo dell'articolo includendo tutto il testo contenuti nei fari TAG P in un solo unico testo
					$testo_completo = $this->get_final_text_content($contenuto);
					if ($testo_completo != '') {
						//pulisco da eventuali codici html il testo dell'articolo che non voglio che ne contenga
						$testo_completo = $this->format_utf8_text($testo_completo);
					}
					//cerco all'interno del corpo dell'articolo anche la presenza eventuale di una immagine da rippare
					foreach ($html_contenudoText->find('img') as $ay_immagini) {
						$url_img[] = $ay_immagini->src;
						
					}
					
					$local_img = $this->get_final_img_content($url_img, $case);
					
					$ay_content['text'] = $testo_completo;
					$ay_content['local_img'] = $local_img;
					return $ay_content;
					
					break;
				case 'http://modaspot.abril.com.br':
					echo "- Recupero il testo completo della notizia \n";
					//forzo e modifico la class del div che devo cercare perch� contiene uno spazio di merda
					$content = str_replace("texto fader", "texto_fader", $content);
					//mando in pasta alla classe che gestisce il parser del codice html la pagina appena trovata
					$html = str_get_html($content);
					//criteri di ricerca che saranno utilizzati per cercare l'articolo completo da rippare
					/**
					 * cercare il DIV contenitore <div class="texto fader">
					 * al suo interno cercare tutti i TAG <p> per comporre il testo
					 * cercare il tag img per vedere se al suo interno sono presenti delle immagini
					 */
					foreach ($html->find('.texto_fader') as $a) {
						$textofader = $a->innertext;
					}
					$html_textofader = str_get_html($textofader);
					
					
					if (is_object($html_textofader)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($html_textofader->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}
						//ottengo il testo completo dell'articolo includendo tutto il testo contenuti nei fari TAG P in un solo unico testo
						$testo_completo = $this->get_final_text_content($contenuto);
						/*if ($testo_completo != '') {
							//pulisco da eventuali codici html il testo dell'articolo che non voglio che ne contenga
							//$testo_completo = $this->format_utf8_text($testo_completo);
						}*/
						echo "- testo completo:".$testo_completo." \n";
						
						echo "- Cerco le immagini dell'articolo \n";
						//cerco all'interno del corpo dell'articolo anche la presenza eventuale di una immagine da rippare
						foreach ($html_textofader->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
							
						}
						echo "- Fine recupero link immagini ora ne importo una \n";
						$local_img = $this->get_final_img_content($url_img, $case);
						
					
						
						$ay_content['text'] = $testo_completo;
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['text'] = '';
						$ay_content['local_img'] = '';
					}
					
					return $ay_content;
					
					break;
				case 'http://www.naturacabelos.com':
					
					//mando in pasta alla classe che gestisce il parser del codice html la pagina appena trovata
					$html = str_get_html($content);
					//criteri di ricerca che saranno utilizzati per cercare l'articolo completo da rippare
					/**
					 * cercare il DIV contenitore <div class="texto fader">
					 * al suo interno cercare tutti i TAG <p> per comporre il testo
					 * cercare il tag img per vedere se al suo interno sono presenti delle immagini
					 */
					foreach ($html->find('#post') as $a) {
						$textofader = $a->innertext;
					}
					$html_textofader = str_get_html($textofader);
					
					if (is_object($html_textofader)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($html_textofader->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}
						
						//ottengo il testo completo dell'articolo includendo tutto il testo contenuti nei fari TAG P in un solo unico testo
						$testo_completo = $this->get_final_text_content($contenuto);
	
						//cerco all'interno del corpo dell'articolo anche la presenza eventuale di una immagine da rippare
						foreach ($html_textofader->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
							
						}
						
						$local_img = $this->get_final_img_content($url_img, $case);
						
						$ay_content['text'] = $testo_completo;
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['text'] = '';
						$ay_content['local_img'] = '';
					}
					return $ay_content;
					
					
					break;
				case 'http://www.style.it':
					/**
					 * catturare tutto l'html
					 * modificare il class articolo_top clearfix in articolo_top_clearfix
					 * 
					 * cercare all'interno di <div class="articolo_top_clearfix">
					 * recuperare il testo inserito all'interno dei tag <p></p>
					 * recuperare le immagini eventualmente presenti cercand il tag <img />
					 * eventualmente nelle immagini cercare l'attributo diffurl per recuperare il link all'immagine originale che si deve rippare
					 */
					//modifico il nome della classe associata al div che contiene la notizia che devo prelevare
					$content = str_replace("articolo_top clearfix", "articolo_top_clearfix", $content);
					//parserizzo il codice html generato
					$html = str_get_html($content);
					foreach ($html->find('.contenuto') as $a) {
						$testo_articolo = $a->innertext;
					}
					//parserizzo il codice HTML presente all'interno del div appena creato
					//@todo inserire un controllo per essere certi di non mandare in pasto al parserizzatore un array
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}
						
						//ottengo il testo completo dell'articolo includendo tutto il testo contenuti nei fari TAG P in un solo unico testo
						$testo_completo = $this->get_final_text_content($contenuto);
						
						//cerco il div contenente le foto
						foreach ($html->find('.foto') as $a) {
							$immagini = $a->innertext;
						}
						$HTMLimmagini = str_get_html($immagini);
						//cerco all'interno del corpo dell'articolo anche la presenza eventuale di una immagine da rippare
						if (is_object($HTMLimmagini)) {
							foreach ($HTMLimmagini->find('img') as $ay_immagini) {
								$url_img[] = $ay_immagini->src;
								
							}
							
							$local_img = $this->get_final_img_content($url_img, $case);
							$ay_content['local_img'] = $local_img;
						} 
						if ($ay_content['local_img'] == '') {
							//cerco il div contenente le foto
							foreach ($html->find('#thumbs') as $a) {
								$immagini = $a->innertext;
							}
							$HTMLimmagini = str_get_html($immagini);
							//cerco all'interno del corpo dell'articolo anche la presenza eventuale di una immagine da rippare
							if (is_object($HTMLimmagini)) {
								foreach ($HTMLimmagini->find('img') as $ay_immagini) {
									$url_img[] = str_replace("0x98", "650x0", $ay_immagini->src);
									
								}
								
								$local_img = $this->get_final_img_content($url_img, $case);
								$ay_content['local_img'] = $local_img;
							} else {
								$ay_content['local_img'] = '';
							}
						}
						
						$ay_content['text'] = $testo_completo;
						
					} else {
						$ay_content['text'] = '';
						$ay_content['local_img'] = '';
					}
					return $ay_content;
					break;
				case 'http://www.mymovies.it':
					/**
					 * Cercare il macro div recensione <div id="recensione"> al suo interno cercare tutti i TAG p 
					 */
					$html = str_get_html($content);
					//parserizzo il codice html generato
					$html = str_get_html($content);
					foreach ($html->find('#recensione') as $a) {
						$testo_articolo = $a->innertext;
					}
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}
						
						$testo_completo = $this->get_final_text_content($contenuto);
						
						foreach ($HTMLtesto_articolo->find('img') as $ay_immagini) {
								$url_img[] = $ay_immagini->src;
						}
						
						$local_img = $this->get_final_img_content($url_img, $case, $custom_prefix, '150', '150');
						$ay_content['local_img'] = $local_img;
						$ay_content['text'] = $testo_completo;
						
						
					} else {
						$ay_content['local_img'] = '';
						$ay_content['text'] = '';
					}
					$html = '';
					return $ay_content;
					break;
				case 'http://boaforma.abril.com.br':
					$html = str_get_html($content);
					//ricerca del testo dell'articolo all'interno del div avente id pagination_0 e all'interno di questo div recupero tutti i tag p presenti
					foreach ($html->find('#pagination_0') as $a) {
						$testo_articolo = $a->innertext;
					}
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}	
						$testo_completo = $this->get_final_text_content($contenuto, false);
						$ay_content['text'] = $testo_completo;
					} else {
						$ay_content['text'] = '';
					}
					$HTMLtesto_articolo = ''; //scarico l'oggetto contenente il div dell'articolo per recuperare memoria
					//ricerca delle immagini dell'articolo
					foreach ($html->find('#SlideContent') as $a) {
						$immagini_articolo = $a->innertext;
					}
					$HTMLimmagini_articolo = str_get_html($immagini_articolo);
					if (is_object($HTMLimmagini_articolo)) {
						foreach ($HTMLimmagini_articolo->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
						}	
						$local_img = $this->get_final_img_content($url_img, $case);
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['local_img'] = '';
					}
					$HTMLimmagini_articolo = ''; //scarico l'object per recuperare memoria
					return $ay_content;
					break;
				case 'http://boaforma.abril.com.br/fitness':
					$html = str_get_html($content);
					//ricerca del testo dell'articolo all'interno del div avente id pagination_0 e all'interno di questo div recupero tutti i tag p presenti
					foreach ($html->find('.txtinfo') as $a) {
						$testo_articolo = $a->innertext;
					}
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}	
						$testo_completo = $this->get_final_text_content($contenuto, false);
						$ay_content['text'] = $testo_completo;
					} else {
						$ay_content['text'] = '';
					}
					$HTMLtesto_articolo = ''; //scarico l'oggetto contenente il div dell'articolo per recuperare memoria
					//ricerca delle immagini dell'articolo
					foreach ($html->find('#cpImg') as $a) {
						$immagini_articolo = $a->innertext;
					}
					$HTMLimmagini_articolo = str_get_html($immagini_articolo);
					if (is_object($HTMLimmagini_articolo)) {
						foreach ($HTMLimmagini_articolo->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
						}
						$base_url = str_replace("/fitness", "", $case);
						$local_img = $this->get_final_img_content($url_img, $base_url);
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['local_img'] = '';
					}
					$HTMLimmagini_articolo = ''; //scarico l'object per recuperare memoria
					return $ay_content;
					break;
				case 'http://www.groupon.it':
					
					$html = str_get_html($content);
					$price = '';

					foreach ($html->find('aside#contentBoxLeftContainer') as $a) {
						$price_html_content .= $a->innertext;
					}
										
					$price_html  = str_get_html($price_html_content);
					
					if (is_object($price_html)) {
						//cerco lo span con classe price
						/**
						 * @version 23/06/2013 span.price
						 * @version 24/06/2013 div.price
						 */
						foreach ($price_html->find('div.price') as $a) {
							//salvo il valore solo finche non ne trovo uno
							$price = $a->innertext;
							
						} 

						//recupero esclusivamente la cifra
						$ay_content['price'] = $this->get_only_number($price);
												
						foreach ($price_html->find('div.savings2') as $table) {
							foreach ($table->find('span') as $td_table) {
								if (strpos($td_table->innertext, "&euro;") === false) {
									$ay_content['price_old'] = 0;
								} else {
									$price_old = $this->get_only_number($td_table->innertext);
									$price_old = $ay_content['price']+$price_old;
									$ay_content['price_old'] = $price_old+$price;
								}
							}
						}
	
						unset($price_html);
					}
					//cerco l'immagine da importare contentDealDescription
					foreach ($html->find('#contentDealDescription') as $a) {
						$immagini_articolo = $a->innertext;
					}
					$HTMLimmagini_articolo = str_get_html($immagini_articolo);
					if (is_object($HTMLimmagini_articolo)) {
						foreach ($HTMLimmagini_articolo->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
						}	
						$local_img = $this->get_final_img_content($url_img, $case, '', '450', '300');
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['local_img'] = '';
					}
					$HTMLimmagini_articolo = ''; //scarico l'object per recuperare memoria
					
					//adesso cerco il contenuto dell'oggeto
					/**
					 * @version 23/06/2013 .contentBoxNormalLeft
					 * @version 24/06/2013 div.contentDealDetails
					 */
					foreach ($html->find('div.contentDealDetails') as $a) {
						$testo_articolo = $a->innertext;
					}
					/*$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}	
						$testo_completo = $this->get_final_text_content($contenuto, false);
						//rimuovo eventuale codice html presente nella stringa
						$testo_completo = $this->remove_html_tags_from_text($testo_completo);
						$ay_content['text'] = $testo_completo;
						
					} else {
						$ay_content['text'] = '';
					}*/
					
					echo $testo_articolo;
					echo "\n";
					
					
					$testo_completo = $this->remove_html_tags_from_text($testo_articolo);
					
					echo $testo_completo." \n";
					
					$ay_content['text'] = $testo_completo;
					
					$HTMLtesto_articolo = ''; //scarico l'oggetto contenente il div dell'articolo per recuperare memoria
					//devo recuoperare quanto tempo manca alla scadenza
					foreach ($html->find('.jcurrentTimeLeft')as $time) {
						$tempo_rimanente = $time->value;
					}
					$ay_content['expirationTime'] = $tempo_rimanente;
					
					return $ay_content;
					break;
				case 'http://it.letsbonus.com':
					$html = str_get_html($content);
					$price = '';
					//cerco lo span con classe price
					foreach ($html->find('span.priceNew') as $a) {
						//salvo il valore solo finche non ne trovo uno
						$price = $a->innertext;
					}
					
					//recupero esclusivamente la cifra
					$ay_content['price'] = $this->get_only_number($price, true);
				
					//recupero il prezzo originale del prodotto
					foreach ($html->find('div.discOldPrice') as $a) {
						//controllo se ha trovato realmente il prezzo originale del prodotto
						$prezzo_originale = $a->innertext;
						
					}

					$prezzo_originale_hmtl = str_get_html($prezzo_originale);

					if (is_object($prezzo_originale_hmtl)) {
						
						foreach ($prezzo_originale_hmtl->find('span') as $ay_price) {
							
							$prezzo_originale = $ay_price->innertext;
							
							if (strpos($prezzo_originale, "%") === false) {
								
								//$prezzo_originale_new = ereg_replace("[^0-9]", "", $prezzo_originale);
								$prezzo_originale_new = $this->get_only_number($prezzo_originale, true);
								if (isset($ay_content['price_old']) and ($ay_content['price_old']) < $prezzo_originale_new) {
									$ay_content['price_old']  = $prezzo_originale_new;		
								} elseif (!isset($ay_content['price_old'])) {
									$ay_content['price_old'] = $prezzo_originale_new;
								}
							}
						}
					}
					
					//cerco l'immagine da importare contentDealDescription
				
					foreach ($html->find('div.detailImages') as $a) {
						$immagini_articolo = $a->innertext;
					}
					$HTMLimmagini_articolo = str_get_html($immagini_articolo);
					if (is_object($HTMLimmagini_articolo)) {
						foreach ($HTMLimmagini_articolo->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
						}	
						$local_img = $this->get_final_img_content($url_img, $case, '', '450', null);
						$ay_content['local_img'] = $local_img;
					} else {
						//$ay_content['local_img'] = '';
						
						//tento l'alternativa cercando di recuperare l'immagine avente id image1
						foreach ($html->find('img#image1') as $a) {
							$link_immagine[] = $a->src;
						}
						
						if ((isset($link_immagine)) AND (is_array($link_immagine))) {
							$local_img = $this->get_final_img_content($link_immagine, $case, '', '450', null);
							$ay_content['local_img'] = $local_img;
						} else {
							//deals con immagine in posizione sconosciuta
							$ay_content['local_img'] = '';
						}
						
						
					}
					$HTMLimmagini_articolo = ''; //scarico l'object per recuperare memoria
					
					//adesso cerco il contenuto dell'oggeto
					foreach ($html->find('div#tab1') as $a) {
						$testo_articolo = $a->innertext;
					}
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}	
						$testo_completo = $this->get_final_text_content($contenuto, false);
						//rimuovo eventuale codice html presente nella stringa
						$testo_completo = $this->remove_html_tags_from_text($testo_completo);
						$ay_content['text'] = $testo_completo;
					} else {
						$ay_content['text'] = '';
					}
					$HTMLtesto_articolo = ''; //scarico l'oggetto contenente il div dell'articolo per recuperare memoria
					//devo recuoperare quanto tempo manca alla scadenza
					/*foreach ($html->find('div.countdown')as $time) {
						$tempo_rimanente = $time->innertext;
					}
					
					//recupero solo i numeri dalla stringa che rappresenta il tempo rimanente
					$tempo_rimanente = $this->get_only_number($tempo_rimanente);
					//provo a esplodere l'array
					$ay_tempo_rimanente = explode(" ", $tempo_rimanente);
					if (is_array($ay_tempo_rimanente)) {
						$giorni = 0;
						$ore = 0;
						$minuti = 0;
						$secondi = 0;
						foreach ($ay_tempo_rimanente as $key=>$tempo) {
							if ($tempo > 0) {
								
								switch ($key) {
									case 0:
										//sono i giorni
										$giorni = $tempo*86400000;
									break;
									case 1:
										//sono le ore
										$ore = $tempo*3600000;
										break;
									case 2:
										//sono i minuti
										$minuti = $tempo*60000;
									break;
									case 3:
										//sono i secondi
										$secondi = $tempo*1000;
									default:
										
									break;
								}
								
								
							}
						}
						
						$tempo_rimanente = $giorni+$ore+$minuti+$secondi;
					
					} else {
						//piazzo il tempo in maniera forfettaria a un giorno
						$tempo_rimanente = 8640000;
					}
					$ay_content['expirationTime'] = $tempo_rimanente;*/
					
					//recupero brutalmente il valore della variabile javascript all'interno dell'html
					$test = explode("var countdownSwitch = \"", $content);
					$test = explode("\";", $test[1]);
					$ay_content['expirationTime'] = $test[0];
					
					
					return $ay_content;
					break;
				case 'http://www.poinx.it':
					//cerco il prezzo dell'oggetto
					$html = str_get_html($content);
					$price = '';
					//cerco lo span con classe price
					foreach ($html->find('span#px_prezzoint2') as $a) {
						//salvo il valore solo finche non ne trovo uno
						$price = $a->innertext;
					}
					//recupero esclusivamente la cifra
					$ay_content['price'] = $this->get_only_number($price);
					
					//metodo per recuperare il prezzo iniziale del prodototto poinx
					$ay_value_price = explode("ivalue", $content);
					if (is_array($ay_value_price)) {
						$ay_price_old = explode(";", $ay_value_price[1]);
						$price_old = $ay_price_old[0];
						//$price_old_new = ereg_replace("[^0-9-]", "", $price_old); 
						$price_old_new = $this->get_only_number($price_old);
						$ay_content['price_old'] = $price_old_new;
					}
					
					//cerco il contenuto dell'oggetto
					foreach ($html->find('div.DescriptionDeal') as $a) {
						$testo_articolo = $a->innertext;
					}
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}	
						$testo_completo = $this->get_final_text_content($contenuto, false);
						//rimuovo eventuale codice html presente nella stringa
						$testo_completo = $this->remove_html_tags_from_text($testo_completo);
						$ay_content['text'] = $testo_completo;
						
					} else {
						$ay_content['text'] = '';
					}
					$HTMLtesto_articolo = ''; //scarico l'oggetto contenente il div dell'articolo per recuperare memoria
					
					//cerco l'immagine dell'oggetto
					foreach ($html->find('div#ImmagineDealHP') as $a) {
						$immagini_articolo = $a->innertext;
					}
					$HTMLimmagini_articolo = str_get_html($immagini_articolo);
					if (is_object($HTMLimmagini_articolo)) {
						foreach ($HTMLimmagini_articolo->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
						}	
						$local_img = $this->get_final_img_content($url_img, $case, '', '450', '300');
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['local_img'] = '';
					}
					$HTMLimmagini_articolo = ''; //scarico l'object per recuperare memoria
					
					//cerco la scadenza dell'oggetto
					foreach ($html->find('font#counter_hours1')as $time) {
						$tempo_rimanente = $time->innertext;
					}
					if (is_numeric($tempo_rimanente)) {
						$ay_content['expirationTime'] = (($tempo_rimanente*86400)*1000);
					} else {
						$ay_content['expirationTime'] = 0;
					}
					
					return $ay_content;	
					break;
				case 'http://www.prezzofelice.it':
					
					//recupero il codice html della pagina
					$html = str_get_html($content);
					
					//recupero il prezzo del prodotto cercando h1.prezzo_dettaglio
					foreach ($html->find('h1.px_prezzoint2') as $a) {
						//salvo il valore solo finche non ne trovo uno
						$price = $a->innertext;
					}
					if (is_null($price)) {
						foreach ($html->find('h1.prezzo_dettaglio') as $a) {
							$price = $a->innertext;
						}
					}
					
					$price = $this->get_only_number($price);
					$ay_content['price'] = $price;
					
					//recupero se presente il prezzo originale del prodotto cercando li.barrato e al suo interno il testo
					foreach ($html->find('li.barrato')as $a) {
						$price_old = $a->innertext;
					}
					
					if (!is_null($price_old)) {
						$price_old = $this->get_only_number($price_old);
						$ay_content['price_old'] = $price_old;
					} else {
						$ay_content['price_old'] = 0;
					}
					
					//recupero il testo del prodotto cerco il div#box_dueparole e al suo interno tutti i tag p
					foreach ($html->find('div#box_dueparole') as $a) {
						$testo_articolo = $a->innertext;
					}
					$HTMLtesto_articolo = str_get_html($testo_articolo);
					if (is_object($HTMLtesto_articolo)) {
						//una volta rippato il div del contenuto vado a cercare il testo della notizia
						foreach ($HTMLtesto_articolo->find('p') as $b) {
							$contenuto[] = $b->innertext;
						}	
						$testo_completo = $this->get_final_text_content($contenuto, false);
						//rimuovo eventuale codice html presente nella stringa
						$testo_completo = $this->remove_html_tags_from_text($testo_completo);
						//rimuovo gli spazi vuoti multipli che sono presenti nel testo
						$testo_completo_no_space = preg_replace('/\s+/', ' ',$testo_completo);
						//rimuovo eventuali spazi esterni al testo
						$ay_content['text'] = trim($testo_completo_no_space);
						
					} else {
						$ay_content['text'] = '';
					}
					$HTMLtesto_articolo = ''; //scarico l'oggetto contenente il div dell'articolo per recuperare memoria
					
					//recupero immagine del prodotto all'interno del div#box_dettaglio_sx tutti i tag img
					foreach ($html->find('div#box_dettaglio_sx') as $a) {
						$immagini_articolo = $a->innertext;
					}
					$HTMLimmagini_articolo = str_get_html($immagini_articolo);
					if (is_object($HTMLimmagini_articolo)) {
						foreach ($HTMLimmagini_articolo->find('img') as $ay_immagini) {
							$url_img[] = $ay_immagini->src;
						}	
						$local_img = $this->get_final_img_content($url_img, $case, '', '450', '300');
						$ay_content['local_img'] = $local_img;
					} else {
						$ay_content['local_img'] = '';
					}
					$HTMLimmagini_articolo = ''; //scarico l'object per recuperare memoria
					
					//cerco di recuperare il tempo mancante all'offerta
					foreach ($html->find('span.cntd_days')as $time) {
						$tempo_rimanente = $time->innertext;
					}
					if (is_numeric($tempo_rimanente)) {
						$ay_content['expirationTime'] = (($tempo_rimanente*86400)*1000);
					} else {
						$ay_content['expirationTime'] = 0;
					}
					return $ay_content;	
					break;
				case 'http://www.groupon.fr':
					$ay_content = $this->get_content_from_portal($content, 'http://www.groupon.it');
					return $ay_content;
					
					break;
				case 'http://www.kgbdeals.fr':
					
					/*PARSERIZZAZIONE DEL CODICE HTML DELLA PAGINA*/
					$html = str_get_html($content);
					
					/*RECUPERO IL PREZZO IN OFFERTA*/
					/**
					 * @todo cercare il div avente classe pbdivprc recuperare solo numeri presenti in innertext
					 */
					foreach ($html->find('div.pbdivprc') as $a) {
						//salvo la stringa trovata all'interno del div
						$price = $a->innertext;
					}
					//se la variabile non � vuota
					if ((isset($price)) AND (!empty($price))) {
						//recupero solo il valore numerico
						$price = $this->get_only_number($price);
						$ay_content['price'] = $price;
					} else {
						//popolo l'array lasciando per˜ il campo vuoto
						$ay_content['price'] = 0;
					}
					
					
					/*RECUPERO IL VECCHIO PREZZO*/
					/**
					 * @todo recupero innertext del tag li avente classe value e della stringa ottenuta recupro solo i numeri
					 */
					foreach ($html->find('li.value') as $a) {
						//salvo la stringa trovata all'interno del div
						$price_old = $a->innertext;
					}
					
					//se la variabile non � vuota
					if ((isset($price_old)) AND (!empty($price_old))) {
						//recupero solo il valore numerico
						$price_old = $this->get_only_number($price_old);
						$ay_content['price_old'] = $price_old;
					} else {
						//popolo l'array lasciando per˜ il campo vuoto
						$ay_content['price_old'] = 0;
					}
					
					
					/*RECUPERO IL TEMPO CHE MANCA ALLO SCADERE*/
					/**
					 * @todo recupero soltanto i giorni che mancano allo scadere e se non presente considero un giorno come temo rimanente (perch� si tratterebbero di sole ore)
					 * devo cercare nel seguente tag div id="deal_days_rem" e recuperare al suo interno solo i numeri.
					 */
					
					foreach ($html->find('div#deal_days_rem') as $a) {
						//salvo la stringa trovata all'interno del div
						$days = $a->innertext;
					}
					
					if ((isset($days)) AND (!empty($days))) {
						$days = $this->get_only_number($days);
					} else {
						$days = 1;
					}
					
					//calcolo i millessimi di secondo che compongono i giorni della scadenza
					$ay_content['expirationTime'] = (($days*86400)*1000);

					/*RECUPERO LA DESCRIZIONE PRODOTTO*/
					/**
					 * @todo la descrizione del prodotto � tutta all'interno del tag <div class="blockContent"> va per˜ pulita dai codici html e va ovviamente recuperato solo il testo
					 * il recupero genererˆ un array dal quale dovr˜ recuperare per ogni risultato solo il testo
					 */
					
					//espressione regolare cerca le ricorrenze di spazi ovvero gli spazi ripetuti in una stringa
					$pattern = '#\s+#si';
					foreach ($html->find('div.blockContent') as $a) {
						//salvo la stringa trovata all'interno del div
						$ay_descrizione[] = preg_replace($pattern, ' ', trim($a->plaintext));
					}
					
					if ((isset($ay_descrizione)) AND (is_array($ay_descrizione))) {
						//lancio il metodo per unificare i testi che compongono la descrizione del prodotto
						$ay_content['text'] = $this->get_final_text_content($ay_descrizione);
					} else {
						$ay_content['text'] = '';
					}
					
					
					/*RECUPERO IMMAGINE DEL PRODOTTO*/
					/**
					 * @todo recupero il div avente class deal625x398-dealImage e mi recupero di questo TAG il suo stile perch� all'interno c'� il background dinamico che devo recuperare in quanto contiene
					 * il link alla immagine del prodotto
					 */
					foreach ($html->find('div.deal625x398-dealImage') as $a) {
						$style_div = $a->style;
					}
					
					if ((isset($style_div)) AND (!empty($style_div))) {
						//dovrei aver ricevuto qualcosa di simile: background-image: url('https://secure-www.kgbdeals.com/deals/FR/174407/174407_s625x398.jpg'); 
						//eseguo quindi delle operazioni per ricavarne un url pulito
						$style_div = str_replace("background-image: url('", "", $style_div);
						$style_div = str_replace("');", "", $style_div);
						$url_img[] = $style_div;
						//salvo in locale l'immagine presente nella url
						$local_img = $this->get_final_img_content($url_img, $case, '', '450', '300');
						$ay_content['local_img'] = $local_img;
						
						
					} else {
						//rinuncio a recuperare l'immagine
						$ay_content['local_img'] = '';
					}
					
					
					return $ay_content;
					break;
				default:
					throw new Exception('il metodo non riconosce il portale al quale si vuole accedere per recuperare le notizie');
				break;
			}
		} else {
			throw new Exception('il metodo necessita di una variabile popolata con del codice html come primo argomento e come secondo argomento il nome del portale al quale si vuole rippare le notizie.');
		}
		
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $img
	 */
	
	public function wget_img($img, $sito = '', $custom_prefix = '', $width='150', $height='150') {
		$ste=parse_url("$img");
		$hostsrc=$ste['host'];
		if(empty($hostsrc)){//se non inizia col nome host del sito forma il corretto URL
			if(substr($img,0,2) == "./"){
	 			$img=$sito.substr($img,1);
			}
			else if (substr($img,0,1) == "/"){
				$img= $sito.$img;
			}
			else{
				$img=$sito."/".$img;
			}
		}
		
		//recupero il nome del file che voglio caricare
		$nome_file=urlencode(basename("$img"));
		//ignoro operazione di salvataggio se pesca una gif
		//if (substr($nome_file, -3)!="gif") {	
			//ignoro la funzione di salvataggio se il file giˆ esiste nel sistema
			if(!file_exists(temp_img_path."/thumb_".$custom_prefix.$nome_file)) {
				//creao l'oggetto immagine richiamando la classe wideimage
				$image = WideImage::loadFromFile($img);
				//modifico le dimensioni dell'immagine rendendola quanto pi� simile a un quadrato 150x150 px
				$image_resized = $image->resize($width, $height, 'outside');
				//modifico il nome del file che vado a salvare per normalizzarlo
				$nome_file = preg_replace("/\W+/", ".", $nome_file);
				//salvo l'immagine nella cartella images/stories/ di joomla
				$image_resized->saveToFile(temp_img_path."/thumb_".$custom_prefix.$nome_file);
				return $custom_prefix.$nome_file;
			} else {
				return $custom_prefix.$nome_file;
			}
		//} else {
			//return false;
		//}
	}
	
	public function scaleImageFileToBlob($file) {

	    //$source_pic = $file;
	    $max_width = 150;
	    $max_height = 150;
	
	    list($width, $height, $image_type) = getimagesize($file);
	
	    switch ($image_type)
	    {
	        case 1: $src = imagecreatefromgif($file); break;
	        case 2: $src = imagecreatefromjpeg($file);  break;
	        case 3: $src = imagecreatefrompng($file); break;
	        default: return '';  break;
	    }
		
	    //mantiene le proporzioni naturali dell'immagine
	    $x_ratio = $max_width / $width;
	    $y_ratio = $max_height / $height;
	
	    if( ($width <= $max_width) && ($height <= $max_height) ){
	        $tn_width = $width;
	        $tn_height = $height;
	        }elseif (($x_ratio * $height) < $max_height){
	            $tn_height = ceil($x_ratio * $height);
	            $tn_width = $max_width;
	        }else{
	            $tn_width = ceil($y_ratio * $width);
	            $tn_height = $max_height;
	    }
	
	    $tmp = imagecreatetruecolor($tn_width,$tn_height);
	
	    /* Check if this image is PNG or GIF to preserve its transparency */
	    if(($image_type == 1) OR ($image_type==3))
	    {
	        imagealphablending($tmp, false);
	        imagesavealpha($tmp,true);
	        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
	        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
	    }
	    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);
	
	    /*
	     * imageXXX() has only two options, save as a file, or send to the browser.
	     * It does not provide you the oppurtunity to manipulate the final GIF/JPG/PNG file stream
	     * So I start the output buffering, use imageXXX() to output the data stream to the browser,
	     * get the contents of the stream, and use clean to silently discard the buffered contents.
	     */
	    ob_start();
	
	    switch ($image_type)
	    {
	        case 1: imagegif($tmp); break;
	        case 2: imagejpeg($tmp, NULL, 100);  break; // best quality
	        case 3: imagepng($tmp, NULL, 0); break; // no compression
	        default: echo ''; break;
	    }
	
	    $final_image = ob_get_contents();
	
	    ob_end_clean();
	
	    return $final_image;
	}
	
	/**
	 * Classe per recuperare da una stringa contenente codice html solo il testo inserito nei TAG p
	 *
	 * @param unknown_type $string
	 */
	
	public function get_innertext_in_tag_p($string) {
		$html = str_get_html($string);
		if (is_object($html)) {
			foreach ($html->find('p') as $a) {
				$testo_articolo = $a->innertext;
			}
		} else {
			return false;
		}
		return $testo_articolo;
	}
	
	/**
	 * il metodo controlla se � presente una stringa del tipo "hello world | text" ritorna "hello world" utile per pulire qualche sommario dei feed rss che analizzo
	 *
	 * @param unknown_type $string
	 * @return unknown
	 */
	public function remove_txt_after_break_symbol($string, $symbol = "|") {
		$ay_text = explode($symbol, $string);
		if (is_array($ay_text)) {
			return $ay_text[0];
		} else {
			return $string;
		}
	}
	
	public function get_summary_from_url($content, $ay_div_reserch, $ay_text_reserch, $number_of_tag = '0') {
		//parserizzo il codice content inviato
		$html = str_get_html($content);
		//controllo se ho generato un valido oggetto o se non cera codice html da analizzare
		if (is_object($html)) {
			foreach ($html->find($ay_div_reserch['type'].$ay_div_reserch['title']) as $a) {
				$find = $a->$ay_div_reserch['what'];
			}
			$Htmlfind = str_get_html($find);
			//se la prima ricerca ha dato esito positivo continuo con la ricerca interna al div
			if (is_object($Htmlfind)) {
				foreach ($Htmlfind->find($ay_text_reserch['type'].$ay_text_reserch['title']) as $b) {
					$text[] = $b->$ay_text_reserch['what'];
				}
			}
			$html = ''; //scarico gli object e recupero memoria
			$Htmlfind = ''; //scarico gli object e recupero memoria
			if ((isset($text)) and (is_array($text))) {
				return $text[$number_of_tag];
			} else {
				return false;
			}
			
		}
		
	}
	
	public function get_only_number($str, $point_like_comma = false) {
			$number = '';
			/*
			//elimino dal prezzo tutti i codici html
			$price = $this->remove_html_tags_from_text($price);
			//elimino la stringa iniziale lasciando solo la cifra Totale:&#32;&euro;
			$price = str_replace("Totale:", "", $price);
			$price = str_replace("da", "", $price);
			$price = str_replace("&#32;", "", $price);
			$price = str_replace("&euro;", "", $price);
			$price = str_replace("Valore", "", $price);
			$price = str_replace("d", "", $price);
			$price = str_replace("h", "", $price);
			$price = str_replace("m", "", $price);
			$price = str_replace("s", "", $price);
			//elimina tutti i caratteri che non siano compresi tra lo 0 e il 9 da vedere poi cosa succede con i prezzi con le virgole
			if (!is_numeric($price)) {
				$price = preg_replace('/[^0-9]/', '',  $price);
			}
			$price = trim($price);
			return $price;*/
		
		
		/*if (is_string($str)) {
			
			//rimuovo tag html presenti perch� i nomi delle classi e degli id potrebbero essere numerici
			$str = strip_tags($str);
			
			//converto gli html entities che possono dare problemi
			$str = html_entity_decode($str);
			
			$tot_char = strlen($str);
			
			for ($i=0;$i<=$tot_char;$i++) {
				//recupero in una singola variabile il carattere della stringa su cui il puntatore � posizionato
				$character = substr($str, $i, 1);
	
			
					if (is_numeric($character)) {
						$number .= $character;
					} else {
						$character_precedente = substr($str, $i-1, 1);
						$character_successivo = substr($str, $i+1, 1);
						if (((isset($character_precedente)) and (is_numeric($character_precedente))) AND ((isset($character_successivo)) and (is_numeric($character_successivo)))) {
							$number .= ".";
						}
					}
				
			}
			
			if (is_numeric($number)) {
				return $number;
			} else {
				return false;
			}
		} else {
			return false;
		}*/
			
		if (is_string($str)) {
				
				//rimuovo tag html presenti perch� i nomi delle classi e degli id potrebbero essere numerici
				$str = strip_tags($str);
				
				//converto gli html entities che possono dare problemi
				$str = html_entity_decode($str);
				
				$tot_char = strlen($str);
				
				for ($i=0;$i<=$tot_char;$i++) {
					//recupero in una singola variabile il carattere della stringa su cui il puntatore � posizionato
					$character = substr($str, $i, 1);
		
					if ($point_like_comma) {
						if (is_numeric($character)) {
							$number .= $character;
						} else {
							$character_precedente = substr($str, $i-1, 1);
							$character_successivo = substr($str, $i+1, 1);
							
							if ($character != '.') {
								if (((isset($character_precedente)) and (is_numeric($character_precedente))) AND ((isset($character_successivo)) and (is_numeric($character_successivo)))) {
									$number .= ".";
								}	
							}
							
						}
					} else {
					//recupero in una singola variabile il carattere della stringa su cui il puntatore � posizionato	
				
						if (is_numeric($character)) {
							$number .= $character;
						} else {
							$character_precedente = substr($str, $i-1, 1);
							$character_successivo = substr($str, $i+1, 1);
							if (((isset($character_precedente)) and (is_numeric($character_precedente))) AND ((isset($character_successivo)) and (is_numeric($character_successivo)))) {
								$number .= ".";
							}
						}
					}
				}
				
				if (is_numeric($number)) {
					$number = round($number, 2);
					return $number;
				} else {
					return false;
				}
			} else {
				return false;
			}
	}
	
	public function strip_tags_content($text, $tags = '', $invert = FALSE) {

	  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
	  $tags = array_unique($tags[1]);
	  
	  if(is_array($tags) AND count($tags) > 0) {
	    if($invert == FALSE) {
	      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
	    }
	    else {
	      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
	    }
	  }
	  elseif($invert == FALSE) {
	    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
	  }
	  return $text;
	} 
}
?>