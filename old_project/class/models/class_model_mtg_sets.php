<?php
class Model_mtg_sets {
	
	public	$connect_db;
	
	/**
	 * Enter description here...
	 *
	 */
	
	public function __construct() {

		$this->connect_db = new mysql_db();
		$this->connect_db->scegli_db(db_name);
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
	
	public function check_double_content($table_name, $column_name, $value) {
		
		$query = "SELECT * FROM ".$table_name." WHERE ".$column_name." = '".$this->format_string($value)."' ";
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function get_id_set($code) {
		$query = "SELECT id FROM ".table_sets." WHERE name = '".$this->format_string($code)."' ";
		
		$result = $this->connect_db->query($query);
		
		if ($result['tot_result']>0) {
			return $result[0]['id'];
		} else {
			throw new Exception('set non esistente');
		}
	}
	
	public function ins_card_in_set($table_name, $ay_data_set) {
		if (is_array($ay_data_set)) {
			$result = $this->connect_db->send_dinamic_query($table_name, $ay_data_set, db_name);
			
			if ($result['esito'] == 1) {
				return true;
			} else {
				throw new Exception($result['feedback']);
			}
		}
	}
	
	public function ins_data($table_name, $ay_data_set) {
		if (is_array($ay_data_set)) {
			$result = $this->connect_db->send_dinamic_query($table_name, $ay_data_set, db_name);
				
			if ($result['esito'] == 1) {
				return true;
			} else {
				throw new Exception($result['feedback']);
			}
		}
	}
	
	public function ins_card_data($table_name, $ay_data, $ay_set) {
		//echo "avvio funzione salvataggio dati <br />";
		if ((is_array($ay_data)) AND (is_array($ay_set))) {
			$result = $this->connect_db->send_dinamic_query($table_name, $ay_data, db_name);
			
			//echo "<pre>";
			//print_r($result);
			//echo "</pre>";
			
			$id_card = mysql_insert_id();
			
			if ($result['esito'] == 1) {
				
				//l'inserimento è andato bene adesso devo associare alla carte i SET e il multiverse ID relativo
				if (is_array($ay_set)) {
					
					foreach ($ay_set as $set) {
						
						$set_id = $this->get_id_set($set['code']);
						
						$ay_data_set = array 	(
													'id_card'	=>	$id_card,
													'id_set'	=>	$set_id,
													'multiverseid'	=>	$set['multiverseid'],
								
												);
						
						$this->ins_card_in_set('mtg_card_in_set', $ay_data_set);
					}
					
				}
				
				return true;
			} else {
				throw new Exception($result['feedback']);
			}
		} else {
			echo "<pre>";
			print_r($ay_set);
			print_r($ay_data);
			echo "</pre>";
			throw new Exception("non è stato inviato un SET valido per la carta ");
		}
	}
	
	public function get_set_to_import() {
		$query = "SELECT id, name FROM ".table_sets." WHERE id NOT IN (SELECT mtg_sets_id FROM ".table_imported_sets.") ORDER BY id ASC LIMIT 0,1";
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function get_cards($keys) {
		
		$query = "SELECT * FROM ".table_cards." AS tc INNER JOIN mtg_cards_foreignnames AS mcf ON tc.multiverseid = mcf.multiverseid WHERE mcf.name LIKE '".$this->format_string($keys)."%' GROUP BY tc.name ORDER BY tc.name DESC LIMIT 0,20";
		//echo $query;
		$result = $this->connect_db->query($query);
		
		/*echo "<pre>";
		print_r($result);
		echo "</pre>";*/
		
		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
					$ay_data[$key] = utf8_encode($val['name']);
				}
			}
			//echo "<pre>";
			//print_r($ay_data);
			//echo "</pre>";
			return $ay_data;
		} else {
			
			$query = "SELECT * FROM ".table_cards." AS tc WHERE tc.name LIKE '".$this->format_string($keys)."%' GROUP BY tc.name ORDER BY tc.name DESC LIMIT 0,20";
			$result = $this->connect_db->query($query);
			if ($result['tot_result']>0) {
				foreach ($result as $key=>$val) {
					if (is_numeric($key)) {
						$ay_data[$key] = utf8_encode($val['name']);
					}
				}
				//echo "<pre>";
				//print_r($ay_data);
				//echo "</pre>";
				return $ay_data;
			}
		}
		
		return false;
		
		
	}
	
	public function get_card($keys) {
		//$query = "SELECT * from ".table_cards." WHERE name = '".$this->format_string($keys)."' ORDER BY name DESC LIMIT 0,1 ";
		$this->connect_db->send_query('SET NAMES utf8', db_name);
		$this->connect_db->send_query('SET CHARACTER SET utf8', db_name);
		$query = "SELECT tc.* FROM ".table_cards." AS tc INNER JOIN mtg_cards_foreignnames AS mcf ON tc.multiverseid = mcf.multiverseid WHERE mcf.name = '".$this->format_string($keys)."' GROUP BY tc.name ORDER BY tc.name DESC LIMIT 0,20";
		//echo $query;
		$result = $this->connect_db->query($query);
		//print_r($result);
		if ($result['tot_result']>0) {
			return $result[0];
		} else {
			
			$query = "SELECT tc.* FROM ".table_cards." AS tc WHERE tc.name = '".$this->format_string($keys)."' GROUP BY tc.name ORDER BY tc.name DESC LIMIT 0,20";
			$result = $this->connect_db->query($query);
			if ($result['tot_result']>0) {
				return $result[0];
			} else {
				return false;
			}
			
			
		}
	}
	
	public function get_data($table, $order_by = 'id', $ordering = 'DESC', $limit = '1', $start='0', $where = '') {
		$query = "SELECT * FROM ".$table." ".$where." ORDER BY ".$order_by." ".$ordering." LIMIT ".$start.",".$limit;
		
		
		
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
					$ay_data[$key] = $val;
				}
			}
			return $ay_data;
		} else {
			return false;
		}
	}
	
	public function tot_data($table, $column) {
		$query = "SELECT COUNT(".$this->format_string($column).") AS tot FROM ".$this->format_string($table)." ";
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			return $result[0]['tot'];
		}
	}
	
	public function get_card_img($id_card) {
		$query = "SELECT * FROM mtg_cards_in_set where multiverseid = '".$id_card."' ORDER BY id_set DESC LIMIT 0,1 ";
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			return "http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=".$result[0]['multiverseid']."&type=card";
		} else {
			return "http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=0&type=card";
		}
	}
	
	public function get_card_set($id_card) {
		$query = "SELECT ms.* FROM mtg_cards_in_set AS mcis INNER JOIN ".table_sets." AS ms ON ms.id = mcis.id_set WHERE mcis.multiverseid = '".$id_card."' ORDER BY ms.id DESC";
		$result= $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
					$ay_data[$key] = $val;
				}
			}
			return $ay_data;
		}
	}
	
	public function get_last_imported_set() {
		$query = "SELECT a.code FROM ".table_sets." AS a INNER JOIN ".table_imported_sets." AS b ON a.id = b.mtg_sets_id ORDER BY b.mtg_imported_date DESC LIMIT 0,1 ";
	
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			return $result[0]['code'];
		} else {
			return false;
		}
	}
	
	public function new_set_to_import($ay_files, $last_imported_index) {
		if (is_array($ay_files)) {
			$now = false;
			foreach ($ay_files as $file) {
				if ($now) {
					$new_file = $file;
					$now = false;
				}
				if ($last_imported_index."-x.json" == $file) {
					$now = true;
				}
				
				
			}
			
			if (!isset($new_file)) {
				$new_file = false;
			} 
			
			return $new_file;
		}
	}
	
	public function rewrite_files_list($ay_set_imported, $ay_files) {
		
		foreach ($ay_set_imported as $set_imported) {
			
			if(($key = array_search($set_imported['code']."-x.json", $ay_files)) !== false) {
				unset($ay_files[$key]);
			}
			
		}
		
		return $ay_files;
		
	}
	
	public function get_last_price($multiverseid, $site, $day = false) {
		
			if ($day) {
				//AND day >= NOW() - INTERVAL 1 DAY
				$query = "SELECT * FROM mtg_cards_price WHERE multiverseid = '".$this->format_string($multiverseid)."' AND shop_code = '".$this->format_string($site)."' AND day >= NOW() - INTERVAL 1 DAY ORDER BY day DESC LIMIT 0,1 ";

			} else {
				$query = "SELECT * FROM mtg_cards_price WHERE multiverseid = '".$this->format_string($multiverseid)."' AND shop_code = '".$this->format_string($site)."' ORDER BY day DESC LIMIT 0,1 ";
			}
			
			//echo $query;
			$result = $this->connect_db->query($query);
			if ($result['tot_result']>0) {
				return $result[0]['price'];
			} else {
				return false;
			}

		
	}
	
	public function get_last_multiverseid_priced($limit) {
		$query = "	SELECT *
					FROM mtg_cards_price
					GROUP BY `multiverseid`
          ORDER BY day DESC 
					LIMIT 0 , ".$limit;
		
		$result = $this->connect_db->query($query);
		
		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
					$ay_data[$key] = $val['multiverseid'];
				}
			}
			
			return $ay_data;
		} else {
			return false;
		}
	}
	
	public function get_last_cards_price() {
		
		// recupero prima le ultime carte listate
		
		$ay_multiverseid = $this->get_last_multiverseid_priced(30);
    
		if (is_array($ay_multiverseid)) {
			
			foreach ($ay_multiverseid as $key=>$multiverseid) {
				
				$ay_card = $this->get_data(table_cards, 'multiverseid', 'DESC', '1', '0', 'WHERE multiverseid = \''.$multiverseid.'\'');
				
				//per ogni carta devo popolare un array con i valori e i prezzi dei vari negozi
				$ay_card[0]['name'] = "<a title=\"".$ay_card[0]['name']."\" rel=\"popover\" data-img=\"".$this->get_card_img($ay_card[0]['multiverseid'])."\" href=\"".base_url."card/".$ay_card[0]['multiverseid'].".html\">".$ay_card[0]['name']."";
				
				$ay_data[$key]['name'] = $ay_card[0]['name'];
				if ($this->get_last_price($multiverseid, 'DeckTutor')) {
					$ay_data[$key]['decktutor_price'] = $this->get_last_price($multiverseid, 'DeckTutor', false)." &euro;";
				} else {
					$ay_data[$key]['decktutor_price'] = 'Non Tracciato';
				}
				if ($this->get_last_price($multiverseid, 'Ebay')) {
					$ay_data[$key]['ebay_price'] = $this->get_last_price($multiverseid, 'Ebay', false)." &euro;";
				}else {
					$ay_data[$key]['ebay_price'] = 'Non Tracciato';
				}
				if ($this->get_last_price($multiverseid, 'Lurkoneshop')) {
					$ay_data[$key]['lurkone_price'] = $this->get_last_price($multiverseid, 'Lurkoneshop', false)." &euro;";
				}else {
					$ay_data[$key]['lurkone_price'] = 'Non Tracciato';
				}
				if ($this->get_last_price($multiverseid, 'MagicCorner')) {
					$ay_data[$key]['magicorner_price'] = $this->get_last_price($multiverseid, 'MagicCorner', false)." &euro;";
				}else {
					$ay_data[$key]['magicorner_price'] = 'Non Tracciato';
				}
				if ($this->get_last_price($multiverseid, 'MagicMarket')) {
					$ay_data[$key]['magicmarket_price'] = $this->get_last_price($multiverseid, 'MagicMarket', false)." &euro;";
				}else {
					$ay_data[$key]['magicmarket_price'] = 'Non Tracciato';
				}
				
			}
			
			return $ay_data;
			
		}
		
	}
	
	public function get_set_cards($id) {
		$query = "SELECT *
					FROM mtg_cards AS mc
					INNER JOIN mtg_cards_in_set AS mcis ON mc.multiverseid = mcis.multiverseid
					WHERE mcis.id_set = '".$this->format_string($id)."' ";
		$result = $this->connect_db->query($query);

		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
					$val['name_pure'] = $val['name'];
					$val['name'] = "<a title=\"".$val['name']."\" rel=\"popover\" data-img=\"".$this->get_card_img($val['multiverseid'])."\" href=\"".base_url."card/".$val['multiverseid'].".html\">".$val['name']."";
					$ay_data[$key] = $val;
				}
			}
			return $ay_data;
		} else {
			return false;
		}
		
	}
	
	public function get_all_cards_with_color_from_set($id_set, $mana_color) {
		
		switch ($mana_color) {
			case 'W':
				$query = "SELECT count(mc.multiverseid) as TOT FROM `mtg_cards` AS mc INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set WHERE ms.id = ".$id_set." AND `manaCost` like '%{W}%' AND `manaCost` not like '%{R}%' AND `manaCost` not like '%{U}%' AND `manaCost` not like '%{G}%' AND `manaCost` not like '%{B}%' AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%'";
				break;
			case 'G':
				$query = "SELECT count(mc.multiverseid) as TOT FROM `mtg_cards` AS mc INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set WHERE ms.id = ".$id_set." AND `manaCost` like '%{G}%' AND `manaCost` not like '%{R}%' AND `manaCost` not like '%{U}%' AND `manaCost` not like '%{W}%' AND `manaCost` not like '%{B}%' AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%'";	
				break;
			case 'B':
				$query = "SELECT count(mc.multiverseid) as TOT FROM `mtg_cards` AS mc INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set WHERE ms.id = ".$id_set." AND `manaCost` like '%{B}%' AND `manaCost` not like '%{R}%' AND `manaCost` not like '%{U}%' AND `manaCost` not like '%{W}%' AND `manaCost` not like '%{G}%' AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%'";				
				break;
			case 'U':
				$query = "SELECT count(mc.multiverseid) as TOT FROM `mtg_cards` AS mc INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set WHERE ms.id = ".$id_set." AND `manaCost` like '%{U}%' AND `manaCost` not like '%{R}%' AND `manaCost` not like '%{B}%' AND `manaCost` not like '%{W}%' AND `manaCost` not like '%{G}%' AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%'";
				break;
			case 'R':
				$query = "SELECT count(mc.multiverseid) as TOT FROM `mtg_cards` AS mc INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set WHERE ms.id = ".$id_set." AND `manaCost` like '%{R}%' AND `manaCost` not like '%{U}%' AND `manaCost` not like '%{B}%' AND `manaCost` not like '%{W}%' AND `manaCost` not like '%{G}%' AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%'";
				break;
			case 'multicolor':
				$query = 	 " SELECT count(mc.multiverseid) as TOT "
							." FROM `mtg_cards` AS mc "
							." INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid "
							." INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set "
							." WHERE "
							." ms.id = ".$id_set
							." AND ( "
							." `manaCost` like '%{U}{R}%' OR "
							." `manaCost` like '%{U}{W}%' OR "
							." `manaCost` like '%{U}{B}%' OR "
							." `manaCost` like '%{U}{G}%' OR "
									
							." `manaCost` like '%{W}{G}%' OR "
							." `manaCost` like '%{W}{B}%' OR "
							." `manaCost` like '%{W}{R}%' OR "
							." `manaCost` like '%{W}{U}%' OR "
									
							." `manaCost` like '%{G}{U}%' OR "
							." `manaCost` like '%{G}{W}%' OR "
							." `manaCost` like '%{G}{R}%' OR "
							." `manaCost` like '%{G}{B}%' OR "
									
							." `manaCost` like '%{R}{U}%' OR "
							." `manaCost` like '%{R}{W}%' OR "
							." `manaCost` like '%{R}{G}%' OR "
							." `manaCost` like '%{R}{B}%' OR "
		
							." `manaCost` like '%{B}{U}%' OR "
							." `manaCost` like '%{B}{W}%' OR "
							." `manaCost` like '%{B}{R}%' OR "
							." `manaCost` like '%{B}{R}%'  "
							
							." ) AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%' ";
				
				break;
			case 'incolor':
				$query = 	 " SELECT count(mc.multiverseid) as TOT "
						." FROM `mtg_cards` AS mc "
						." INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid "
						." INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set "
						." WHERE "
						." ms.id = ".$id_set
						." AND ( "
						." `manaCost` not like '%{R}%' AND "
						." `manaCost` not like '%{W}%' AND "
						." `manaCost` not like '%{B}%' AND "
						." `manaCost` not like '%{U}%' AND "
						." `manaCost` not like '%{G}%'  "
						." ) AND mc.originalType NOT LIKE '%Land%' AND mc.originalType NOT LIKE '%Token%' ";
				break;
				
				
				
				
		}
		
		
		$result = $this->connect_db->query($query);
		
		if ($result['tot_result']>0) {
			return $result[0]['TOT'];
		} else {
			return '0';
		}
		
	}
	
	public function get_set_mana_cost_asset($id) {
		if (is_numeric($id)) {
			
			$query = "SELECT count( mc.multiverseid ) AS TOT, mc.cmc
					FROM `mtg_cards` AS mc
					INNER JOIN mtg_cards_in_set AS mcis ON mcis.multiverseid = mc.multiverseid
					INNER JOIN mtg_sets AS ms ON ms.id = mcis.id_set
					WHERE ms.id = ".mysql_real_escape_string($id)."
				    AND mc.originalType NOT LIKE '%Land%' 
	     			AND mc.originalType NOT LIKE '%Token%' 
					GROUP BY mc.cmc
					ORDER BY mc.cmc ASC ";
			$result = $this->connect_db->query($query);
			
			if ($result['tot_result']>0) {
				foreach ($result as $key=>$val) {
					if (is_numeric($key)) {
						$ay_mana_asset[$val['cmc']] = $val['TOT'];
					}
				}
				
				return $ay_mana_asset;
			} else {
				return false;
			}
			
		} else {
			throw new Exception('Il metodo get_set_mana_cost_asset necessita di un numero intero passato come argomento per funzionare.');
		}
	}
	
}
?>