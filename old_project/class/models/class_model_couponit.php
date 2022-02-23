<?php
class Model_Couponit {
	
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
	
	public static function is_date($date)
    {
        $date = str_replace(array('\'', '-', '.', ','), '/', $date);
        $date = explode('/', $date);

        if(    count($date) == 1 // No tokens
            and    is_numeric($date[0])
            and    $date[0] < 20991231 and
            (    checkdate(substr($date[0], 4, 2)
                        , substr($date[0], 6, 2)
                        , substr($date[0], 0, 4)))
        )
        {
            return true;
        }
       
        if(    count($date) == 3
            and    is_numeric($date[0])
            and    is_numeric($date[1])
            and is_numeric($date[2]) and
            (    checkdate($date[0], $date[1], $date[2]) //mmddyyyy
            or    checkdate($date[1], $date[0], $date[2]) //ddmmyyyy
            or    checkdate($date[1], $date[2], $date[0])) //yyyymmdd
        )
        {
            return true;
        }
       
        return false;
    } 
	
    /**
     * Recupera l'ultimo url suffix regolarmente processato e salvato nel LogDB
     *
     * @return unknown
     */
    
	public function get_last_urlFeedSuffixId() {	
			//query
			$query = "SELECT urlFeedSuffixId FROM ".table_logs_crontab." ORDER BY id DESC LIMIT 0,1";
			$result = $this->connect_db->send_query($query, db_name);
			if ($result['tot_result']>0) {
				return $result[0]['urlFeedSuffixId'];
			} else {
				return false;
			}
	}
	
	public function get_last_urlCrawlerSuffixId() {
			$query = "SELECT urlFeedSuffixId FROM ".table_logs_crontab_crawler." ORDER BY id DESC LIMIT 0,1";
			$result = $this->connect_db->send_query($query, db_name);
			if ($result['tot_result']>0) {
				return $result[0]['urlFeedSuffixId'];
			} else {
				return false;
			}
	}
	
	public function get_url_crawler($last_urlFeedSuffixId = NULL) {
		if (is_null($last_urlFeedSuffixId)) {
			$query = 	 "SELECT u.id, u.partnerId, u.categoryId, p.url, p.ajax, CONCAT( p.urlFeed, u.urlSuffix ) AS feed_rss "
						." FROM ".table_partners." AS p "
						." INNER JOIN ".table_url_crawler_suffix." AS u "
						." ON p.id = u.partnerId"
						." ORDER BY u.id"
						." ASC LIMIT 0,1";
		} else {
			$query = 	 "SELECT u.id, u.partnerId, u.categoryId, p.url, p.ajax, CONCAT( p.urlFeed, u.urlSuffix ) AS feed_rss "
						." FROM ".table_partners." AS p "
						." INNER JOIN ".table_url_crawler_suffix." AS u "
						." ON p.id = u.partnerId"
						." WHERE u.id > '".$this->format_string($last_urlFeedSuffixId)."' "
						." ORDER BY u.id"
						." ASC LIMIT 0,1";
		}
		
		$result = $this->connect_db->send_query($query, db_name);
		if ($result['tot_result'] > 0) {
			return $result[0];
		} else {
			if (!is_null($last_urlFeedSuffixId)) {
				//forzo lo script in maniera tale da recuperare nuovamente il primo UrlSuffix presente in DB
				return $this->get_url_feed(NULL);
			} else {
				throw new Exception('non ci sono feed rss da analizzare');
			}
		}
	}
	
	public function get_url_feed($last_urlFeedSuffixId = NULL) {
		
		if (is_null($last_urlFeedSuffixId)) {
			$query = 	 "SELECT u.id, u.partnerId, u.categoryId, p.url, CONCAT( p.urlFeed, u.urlSuffix ) AS feed_rss "
						." FROM ".table_partners." AS p "
						." INNER JOIN ".table_urlfeedsuffix." AS u "
						." ON p.id = u.partnerId"
						." ORDER BY u.id"
						." ASC LIMIT 0,1";
		} else {
			$query = 	 "SELECT u.id, u.partnerId, u.categoryId, p.url, CONCAT( p.urlFeed, u.urlSuffix ) AS feed_rss "
						." FROM ".table_partners." AS p "
						." INNER JOIN ".table_urlfeedsuffix." AS u "
						." ON p.id = u.partnerId"
						." WHERE u.id > '".$this->format_string($last_urlFeedSuffixId)."' "
						." ORDER BY u.id"
						." ASC LIMIT 0,1";
		}
		
		$result = $this->connect_db->send_query($query, db_name);
		if ($result['tot_result'] > 0) {
			return $result[0];
		} else {
			if (!is_null($last_urlFeedSuffixId)) {
				//forzo lo script in maniera tale da recuperare nuovamente il primo UrlSuffix presente in DB
				return $this->get_url_feed(NULL);
			} else {
				throw new Exception('non ci sono feed rss da analizzare');
			}
		}
	}
	
	public function check_double_content($title) {
		
		if (is_string($title)) {
			
			$query = "SELECT * FROM ".table_products." WHERE title = '".$this->format_string($title)."'";
			$result = $this->connect_db->send_query($query, db_name);
			
			if ($result['tot_result']>0){
				return true;	
			} else {
				return false;
			}
		} else{
			throw new Exception('il metodo necessita di una stringa passata per argomento per funzionare');
		}
		
	}
	
	/**
	 * funzione per l'inserimento dei prodotti all'interno del db.
	 *
	 * @param unknown_type $ay_content
	 */
	
	public function ins_product($ay_content, $ay_data_feed_rss, $ay_product_final = false) {
		
		if (!$ay_product_final) {
			//preparo l'array per l'inserimento del prodotto nel sistema
			$ay_product = array	(
									'partnerId'			=>	$ay_data_feed_rss['partnerId'],
									'importedTime'		=>	$ay_content['importedTime'],
									'expirationTime'	=>	$ay_content['content']['expirationTime'],
									'title'				=>	$this->format_string($ay_content['title']),
									'link'				=>	$this->format_string($ay_content['url']),
									'description'		=>	$this->format_string($ay_content['content']['text']),
									'price'				=>	$ay_content['content']['price'],
									'price_old'			=>	$ay_content['content']['price_old'],
									'summary'			=>	$this->format_string($ay_content['summary']),
									'img'				=>	$this->format_string($ay_content['content']['local_img']),
								);
							
		} else {
			$ay_product = $ay_product_final;					
		}
		//inserisco array dentro al db
		$result = $this->connect_db->send_dinamic_query(table_products, $ay_product, db_name, 'INSERT');
		
		//controllo se la query � andata a buon fine
		if ($result['esito']==1) {
			return true;
		} else {
			throw new Exception($result['feedback']);
		}
	}
	
	public function ins_log($ay_data_feed_rss, $esito = 'ok') {
		$query 	= "INSERT INTO ".table_logs_crontab." (dateExecution, urlFeedSuffixId, result) VALUES ('".time()."','".$this->format_string($ay_data_feed_rss['id'])."','".$esito."')";
		$result = $this->connect_db->send_query($query, db_name);
		if ($result['esito'] == 1) {
			return true;
		} else {
			throw new Exception($result['feedback']);
		}
	}
	
	/**
	 * @todo inserire un controllo sull'esistenza del prodotto inserire un controllo sulla esistenza della categoria.
	 *
	 * @param unknown_type $productId
	 * @param unknown_type $categoryId
	 */
	
	public function ins_categoryAssociation($productId, $categoryId) {
		
		//controllo se è già presente l'associazione tra categoria e prodotto
		if (!$this->check_double_categoryAssociation($productId, $categoryId)) {
			//preparo l'array per l'inserimento dell'associazione
		
			$ay_data = array	(
									'productId' => $productId,
									'categoryId' => $categoryId,
								);
								
			$result = $this->connect_db->send_dinamic_query(table_category_associated, $ay_data, db_name, 'INSERT');
			
			if ($result['esito'] == 1) {
				return true;
			} else {
				throw new Exception($result['feedbook']);
			}
		} else {
			return false;
		}
	}
	/**
	 * recupera un prodotto utilizzando come parametro di ricerca la key e come valore il val passato nei parametri
	 *
	 * @param unknown_type $val
	 * @param unknown_type $key
	 */
	public function get_product($val, $key) {
		
		$query = "SELECT * FROM ".table_products." where ".$this->format_string($key)." = '".$this->format_string($val)."' ORDER BY id DESC LIMIT 0,1";
		$result = $this->connect_db->send_query($query, db_name);
		
		if ($result['tot_result']>0){
			return $result[0];
		} else {
			throw new Exception($result['feedback']);
		}
	}
	
	/**
	 * Il metodo controlla se esiste giˆ l'associazione tra prodotto e categoria nella tabella categoryAssociation
	 *
	 * @param unknown_type $productId
	 * @param unknown_type $categoryId
	 * 
	 * @return bool true/false
	 */
	
	public function check_double_categoryAssociation($productId, $categoryId) {
		
		//preparo la query di controllo
		$query = "SELECT * FROM ".table_category_associated." WHERE productId = '".$this->format_string($productId)."' AND categoryId = '".$this->format_string($categoryId)."' ";
		//lancio la query nel db
		$result = $this->connect_db->send_query($query, db_name);
		
		if ($result['tot_result']>0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_local($id) {
		$query = "SELECT * FROM ".table_partners." WHERE id = '".$this->format_string($id)."' ";
		
		$rs = $this->connect_db->send_query($query, db_name);
		
		
		if ($rs['tot_result']>0) {
			
			return $rs[0]['local'];
			
		} else {
			throw new Exception('non esiste un partner avente id: '.$id);
		}
	} 
	
	public function get_sub_category($locale = 'it-IT') {
		$query = "SELECT * FROM ".table_sub_category." WHERE locale = '".$this->format_string($locale)."' ORDER BY name ASC";
		
		$result = $this->connect_db->send_query($query, db_name);
		
		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
					$ay_sub_category[$key] = $val; 
				}
			}
		} else {
			throw new Exception('Errore: '.$result['feedback']);
		}
		
		return $ay_sub_category;
	}
	
	public function get_sub_category_tags($sub_category) {
		if (is_numeric($sub_category)) {
			
			$query = "SELECT * FROM ".table_sub_categoryTags." WHERE sub_categoryId = '".$this->format_string($sub_category)."' ";
			
			$result = $this->connect_db->send_query($query, db_name	);
			
			if ($result['tot_result'] > 0) {
				foreach ($result as $key=>$val) {
					if (is_numeric($key)) {
						$ay_tags[$key] = $val;
					}
				}
			} else {
				$ay_tags = false;
			}
			
		} else {
			throw new Exception('il metodo get_sub_category_tags necessita di un numero intero passato come argomento per funzionare.');
		}
		
		return $ay_tags;
	}
	
	public function ins_sub_categoryAssociation($id_product, $ay_products, $ay_data_rss, $default_sub_category = 1) {
		
		//numero totale di sottocategorie associate al prodotto
		$tot_association = 0;
		
		//recupero la lingua per la quale queto prodotto � stato creato
		$local = $this->get_local($ay_data_rss['partnerId']);
		
		//recupero tutte le sotto categorie presenti nel sistema
		$ay_sub_category = $this->get_sub_category($local);
		
		//listo tutte le sub_category
		foreach ($ay_sub_category as $sub_category) {
			
			$association = false;
			
			//recupero i Tag della sub_category
			$ay_sub_category_tags = $this->get_sub_category_tags($sub_category['id']);
			
			//se non ci sono tags per la categoria prescelta salto
			if ((is_array($ay_sub_category_tags)) AND (!$association)) {
				
				//listo tutti i tags della categoria per confrontarli con il prodotto
				foreach ($ay_sub_category_tags as $tag) {
					if (!$association) {
						
						//controllo se il tag esatto � presente nel testo
						if (strlen($tag['tag']) > 3) {
							//controllo se la parola � presente nella descrizione o nel sommario o nel titolo del prodotto
							if($this->trovaStringa($ay_products['title'], $tag['tag'])) {
		  						$association = true;
		  						$sub_categoryTagsId = $tag['id'];
		  					}
		  					
							if($this->trovaStringa($ay_products['summary'], $tag['tag'])) {
		  						$association = true;
		  						$sub_categoryTagsId = $tag['id'];
		  					}
						}
						
						/**
						 * Evito di scomporre le parole composte in quanto le parole singole di una parola
						 * composta potrebbero avere un significato ambiguo.
						 * 
						 * Es. Centro benessere: la parola centro potrebbe essere inserita in qualsiasi contesto e quindi � troppo ambigua per una ricerca semantica del testo
						 */
						//provo a vedere il tag � composto da pi� parole
						/*$ay_tag = explode(" ", $tag['tag']);
						
						if (is_array($ay_tag)) {
							
							foreach ($ay_tag as $sub_tag) {
								
								if (strlen($sub_tag) > 4) {
									//controllo se la parola � presente nella descrizione o nel sommario o nel titolo del prodotto
									if($this->trovaStringa($ay_products['title'], $sub_tag)) {
				  						$association = true;
				  						$sub_categoryTagsId = $tag['id'];
				  					}
				  					
									if($this->trovaStringa($ay_products['summary'], $sub_tag)) {
				  						$association = true;
				  						$sub_categoryTagsId = $tag['id'];
				  					}
								}
							}
							
							
						} else {
							if ($tag['tag'] > 3) {
								//controllo se la parola � presente nella descrizione o nel sommario o nel titolo del prodotto
								if($this->trovaStringa($ay_products['title'], $tag['tag'])) {
			  						$association = true;
			  						$sub_categoryTagsId = $tag['id'];
			  					}
			  					
								if($this->trovaStringa($ay_products['summary'], $tag['tag'])) {
			  						$association = true;
			  						$sub_categoryTagsId = $tag['id'];
			  					}
							}
						}*/
					}
				}
			}
			
			//se c'� associabilitˆ tra sub_category e prodotto procedo a inserire l'associazione
			if ($association) {
				
				$query = "INSERT INTO ".table_sub_categoryAssociation." (sub_categoryId, productId, sub_categoryTagsId) VALUES ('".$sub_category['id']."','".$this->format_string($id_product)."', '".$sub_categoryTagsId."')";
				$rs = $this->connect_db->send_query($query, db_name);
				if ($rs['esito'] != 1) {
					throw new Exception($rs['feedback']);
				}
				$tot_association++;
			}
		
		}
		
		//se nessuna categoria soddisfa il prodotto trovato lo associo forzatamente alla categoria Shopping
		
		if ($tot_association == 0) {
			$query = "INSERT INTO ".table_sub_categoryAssociation." (sub_categoryId, productId) VALUES ('".$this->format_string($default_sub_category)."','".$this->format_string($id_product)."')";
			$rs = $this->connect_db->send_query($query, db_name);
			if ($rs['esito'] != 1) {
				throw new Exception($rs['feedback']);
			}
		}
		
		return true;		
	}
	
	public function startsWith($haystack, $needle)
	{
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) === $needle);
	}
	
	
	public function trovaStringa($text,$wordToSearch)  
    {  
    	$word_find = false;
    	
    	//controllo se ho ricevuto una sola parola da ricercare o una parola composta
    	$tot_words = str_word_count($wordToSearch);
    	
    	if ($tot_words > 1) {
    		
    		//applico il controllo con regular expression e ritorno vero solo se nella frase � presente la parola composta precisa
    		$patt = "/(?:^|[^a-zA-Z])" . preg_quote($wordToSearch, '/') . "(?:$|[^a-zA-Z])/i";
    		$word_find = preg_match($patt, $text);
    		
    	} else {
    		
    		/**
    		 * Modifica richiesta il 16/07/2013
    		 * 
    		 * Inserisco in caso di parola chiave singola una ricarca sulla stringa di ricerca in modalitˆ "esatta". In altre parole 
    		 * se il tag cercato e bar l'associazione avverˆ per un titolo simile a questo: offerta del bar peppino ma non in questo caso: il barman peppino vi offre
    		 * 
    		 */
    		
    		$word_find = preg_match('/\b('.$wordToSearch.')\b/', $text);
    		
    		/*
    		//applico il controllo flessibile per verificare se la parola � presente in maniera precisa o all'interno di una parola composta di cui per˜ ne � prefisso
    		$pos = strpos($text, $wordToSearch);
    		
    		if ($pos === false) {
    			
				//la parola non � presente nel testo
    			$word_find = false;
    			
    		} else {
    			
    			//genero un array delle parole che compongono la frase utilizzando come separatore lo spazio
				$ay_words = explode(" ", $text);
				
				foreach ($ay_words as $word) {
					
					if ($this->startsWith($word, $wordToSearch)) {
						 $word_find =  true;
					}
				}
			}*/
    	}
    	
    	return $word_find;
    } 
    
    public function ins_Crawlerlog($url, $esito = 'ok') {
    	if (is_array($url)) {
	    	$query 	= "INSERT INTO ".table_logs_crontab_crawler." (dateExecution, urlFeedSuffixId, result) VALUES ('".time()."','".$this->format_string($url['id'])."','".$esito."')";
			$result = $this->connect_db->send_query($query, db_name);
			if ($result['esito'] == 1) {
				return true;
			} else {
				throw new Exception($result['feedback']);
			}
    	} else {
    		throw new Exception('il metodo ins_Crawlerlog necessita di un array passato come argomento per funzionare');
    	}
    }
    
    public function get_PostParam($id) {
    	$query = "SELECT * FROM crawlerAjaxParameters WHERE urlCrawlerSuffixId = '".$this->format_string($id)."' ORDER BY id ";
    	$result = $this->connect_db->send_query($query, db_name);
    	if ($result['tot_result']>0) {
    		foreach ($result as $key=>$val) {
    			if (is_numeric($key)) {
    				$ay_data[$key] = $val;
    			}
    		}
    		return $ay_data;
    	} else {
    		throw new Exception($result['feedback']);
    	}
    }
}
?>