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
	
	public function ins_data($table_name, $ay_data) {
		if (is_array($ay_data)) {
			$result = $this->connect_db->send_dinamic_query($table_name, $ay_data, db_name);
			if ($result['esito'] == 1) {
				return true;
			} else {
				throw new Exception($result['feedback']);
			}
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
		
		$query = "SELECT name FROM ".table_cards." WHERE name LIKE '%".$this->format_string($keys)."%' ORDER BY name DESC LIMIT 0,20";

		$result = $this->connect_db->query($query);
		
		if ($result['tot_result']>0) {
			foreach ($result as $key=>$val) {
				if (is_numeric($key)) {
				$ay_data[$key] = $val['name'];
				}
			}
			
			return $ay_data;
		}
		
		return false;
		
		
	}
	
	public function get_card($keys) {
		$query = "SELECT * from ".table_cards." WHERE name = '".$this->format_string($keys)."' ORDER BY name DESC LIMIT 0,1 ";
		$result = $this->connect_db->query($query);
		if ($result['tot_result']>0) {
			return $result[0];
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
}
?>