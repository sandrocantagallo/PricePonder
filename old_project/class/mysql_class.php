<?php
#####################################################
# Qui saranno salvate tutte le classi che           #
# saranno utilizzate per la gestione del            #
# database mysql.                                   #            
#####################################################
# @Data Creazione:	23/09/2008						#
# @Autore:			Sandro Cantagallo				#
# @Email:			sandro.cantagallo@dromedian.com	#
#####################################################
/*
	@Classe:	mysql_db
	@Metodi:	connetti()					->	metodo adibito alla connessione con il database mysql. I dati per la connessione vengono pescati dal file config.inc.php
				scegli_db($name)			->	metodo per dire a mysql su quale database lavorare. Richiede come parametro il nome del database
				send_query($query, $name)	->	metodo per inviare una query di qualunque tipo al database mysql. Richiede la query da inviare e il nome del database desiderato.
												Lo script controlla il tipo di query inviata e si comporta di conseguenza. In caso di SELECT restituer� un array di risultati
												provvisto inoltre del campo 'tot_records'

*/

class mysql_db 
{
	
	//* DICHIARO LE VARIABILI CHE UTILIZZERO NELLA CLASSE
	private $db;
	public $host;
	public $user;
	public $pass;
	public $result;
	public $row;
	public $total;
	public $num;
	public $records;
	public $val;
	public $key;
	public $type;
	public $feedback = array();
	public $error;
	public $succes;
	
	protected $throwException = false;


	
	function __construct() 
	{
		
		//** PRENDO LE VARIABILI GLOBALI DI CUI HO BISOGNO PER PROCEDERE E CHE SONO SICURO NON CAMBIANO
		global $msg_error;
		global $msg_succes;
		
		//** INSERISCO IL VALORE PRESO DALLE VARIABILI GLOBALI NELLE VARIABILI CHE HO ISTANZIATO NELLA CLASSE
		$this->host		=	db_host;
		$this->user		=	db_user;
		$this->pass		=	password;
		$this->error	=	$msg_error;
		$this->succes	=	$msg_succes;
		
		$this->num = 0;
		
		$this->feedback = array	(
									'esito'			=>	'',
									'feedback'		=>	'',
									'tot_result'	=>	''
								);
	}
	function __destruct() {
		
		$this->row		=	'';
		$this->records	=	'';
		$this->num		=	0;
		$this->query	=	'';
		unset($this->feedback);
		
	}
	
	protected function _query($query, $db) {
		
		$db = $db ? $db : $this->db;
		$result = mysql_query($query, $db);
		
		if (mysql_errno($db) && $this->throwException) {
			
			throw new Exception(mysql_error(), mysql_errno());
			
		}
		
		return $result;
	}
	
	public static function getInstance() {
		
		static $instance;
		if (! $instance) {
			$instance = new mysql_db();
		}
		return $instance;
	}
	
	//* METODO PER LA CONNESSIONE AL SERVER MYSQL - PRIVATA E UTILIZZABILE SOLO ALL'INTERNO DELLA CLASSE
	public function connetti() 
	{
		if (!$this->db) {
			$this->db =	mysql_connect($this->host, $this->user, $this->pass);
			
			if (mysql_errno($this->db))
			{
				//** MESSAGGIO DI ERRORE PER NON RIUSCITA CONNESSIONE CON IL DB
				die ($this->error[0].$this->host);
			}
		} else {
			return true;
		}
	}
	
	//* METODO PER LA SCELTA DEL DB A CUI ALLACCIARSI
	public function scegli_db($name) 
	{
		$this->connetti(); //** MI CONNETTO REALMENTE AL DB
		mysql_select_db($name, $this->db)
		//** MESSAGGIO DI ERRORE PER ERRORE NELLA CONNESSIONE DEL DB
		OR die ($this->error[1].$name);
		
		return $this;
	}
	//* METODO PER L'INVIO DI UNA QUERY AL DB DI QUALUNQUE TIPO
	public function send_query($query, $name) 
	{	
		$this->scegli_db($name);
		
		return $this->query($query);
	}
	
	public function query($query) {
		//$this->records = '';
		$this->__destruct();
		//** MI CONNETTO AL DB
		$this->connetti();
		
		//** CONTROLLO CHE TIPO DI QUERY E' ARRIVATA E A SECONDA DEI CASI MI COMPORTO DI CONSEGUENZA
		if ((strstr($query, 'SELECT') !== FALSE) OR (strstr($query, 'select') !== FALSE)) {
			//** INVIO LA QUERY AL DB 
			$this->result = $this->_query($query, $this->db);
			//** TROVO I RISULTATI OTTENUTI
			$this->total = @mysql_num_rows($this->result);
			if ($this->total > 0) 
			{
				//*** LISTO TUTTI I RISULTATI OTTENUTI DALLA QUERY
				while ($this->row = mysql_fetch_assoc($this->result)) 
				{
					//**** 
					foreach ($this->row as $this->key=>$this->val) 
					{
						$this->records[$this->num][$this->key] = $this->val;
						//$this->records[$this->num][$this->key]['type'] = mysql_field_type($this->result, $this->num);
					}
					//**** AUMENTO DI UNA UNITA IL VALORE DI QUESTA VARIABILE PER PERMETTERE ALL'ARRAY DI POPOLARSI CORRETTAMENTE ALL'INFINITO
					$this->num++;
				}
				//**
				$this->records['esito'] = true;
				$this->records['tot_result'] = mysql_num_rows($this->result);
				return $this->records;
			} else 
			{
				//** MESSAGGIO DI ERRORE PER QUERY A RISULTATO 0
				$this->feedback['esito'] = false;
				$this->feedback['tot_result'] = 0;
				$this->feedback['feedback'] = $this->error[2].$this->error[6].$query.$this->error[7].mysql_errno($this->db)." ".mysql_error($this->db);
				return $this->feedback;
				//die();
			}
		} else {
			//** IN QUESTO CASO MI LIMITO A LANCIARE LA QUERY SUL DB E MOSTRARE A MONITOR IL RISULTATO
			if ($this->_query($query, $this->db)) 
			{
				//*** LA QUERY � STATA INVIATA CON SUCCESSO E QUINDI MANDO ESITO POSITIVO CON UN MINIMO DI FEEDBACK
				//*** CONTROLO IL NUMERO DI LINEE INTERESSATE
				$this->records = mysql_affected_rows();
				$this->feedback['feedback'] = $this->succes[1].$this->records;
				//*** MOSTRO LA QUERY LANCIATA
				$this->feedback['feedback'] .= $this->succes[2].$query;
				$this->feedback['esito'] = true;
				return $this->feedback;
			} else 
			{
				$this->feedback['esito'] = false;
				$this->feedback['feedback'] = $this->error[5].$this->error[6].$query.$this->error[7].mysql_errno($this->db)." ".mysql_error($this->db);
				return $this->feedback;	
				//die();
			}
		}
		
	}
		
	//* METODO PER INVIARE QUALSIASI TIPO DI QUERY POPOLANDO UN SEMPLICE ARRAY E DICHIARANDONE IL TIPO E IL NOME DEL DATABASE
	public function send_dinamic_query($table_name, $array, $name, $type = 'INSERT', $where = null) {
		//** CONTROLLO CHE LA VAR $array SIA VERAMENTE UN ARRAY CORRETTAMENTE POPOLATO
		$this->__destruct();
		
		if (($array !='') AND (is_array($array))) 
		{
			//** CONTROLO QUALE TIPO DI QUERY � STATA RICHIESTA
			if ($type == 'INSERT') 
			{
				//*** METODO INSERT
				$this->query .=	"INSERT INTO "
								.$table_name
								." (";
			}
			elseif ($type == 'REPLACE')
			{
				//*** METODO REPLACE
				$this->query .=	"REPLACE INTO "
								.$table_name
								." (";
			} else
			{
				//*** MESSAGGIO DI ERRORE PER AZIONE NON CONSENTITA
				$this->feedback['esito'] = false;
				$this->feedback['feedback'] = $this->error[4];
				return $this->feedback;
				
			}
			//** SFOGLIO L'ARRAY INVIATO PER PRENDERE TUTTI I KEY
			foreach ($array as $this->key=>$this->val)
			{
					$this->query .= "`".$this->key."`,";
			}
			//** RIMUOVO L'ULTIMA VIRGOLA INSERITA CHE DAREBBE ERRORE NELLA QUERY
			$this->query = substr($this->query, 0, -1);
			$this->query .= ") VALUES (";
			//** SFOGLIO NUOVAMENTE L'ARRAY QUESTA VOLTA PER PRENDERE TUTTI I VAL
			foreach ($array as $this->key=>$this->val)
			{
				$this->query .= "'".mysql_real_escape_string($this->val)."',";
			}
			//** RIMUOVO L'ULTIMA VIRGOLA CHE DAREBBE ERRORE NELLA QUERY
			$this->query = substr($this->query, 0, -1);
			$this->query .= ")";
			$this->scegli_db($name);
			
			//echo $this->query." <br />";
			
			//** MANDO LA QUERY REALIZZATA AL DB
			if ($this->_query($this->query, $this->db)) 
			{
				//*** LA QUERY � STATA INVIATA CON SUCCESSO E QUINDI MANDO ESITO POSITIVO CON UN MINIMO DI FEEDBACK
				$this->feedback['esito'] = true;
				$this->feedback['feedback'] = $this->succes[0];
				//*** CONTROLO IL NUMERO DI LINEE INTERESSATE
				$this->records = mysql_affected_rows();
				$this->feedback['feedback'] .= $this->succes[1].$this->records;
				//*** MOSTRO LA QUERY LANCIATA
				$this->feedback['feedback'] .= $this->succes[2].$this->query;
				return $this->feedback;
				
			} else 
			{
				$this->feedback['esito'] = false;
				$this->feedback['feedback'] = $this->error[5].$this->error[6].$this->query.$this->error[7].mysql_errno($this->db)." ".mysql_error($this->db);
				return $this->feedback;
				
			}
		} else 
		{
			//** MANDO IN ERRORE PERCH� NON � UN ARRAY
			$this->feedback['esito'] = false;
			$this->feedback['feedback'] = $this->error[3];
			return $this->feedback;
		}

	}
	
	/*
	 * FUNZIONE PER INIZIALIZZARE UNA TRANSACTION
	 */
	
	
	public function start_transaction() {	
	
		
		$this->query("START TRANSACTION");
	}
	
	/**
	 * FUNCTIONE PER COMMITTARE UNA TRANSACTION
	 *
	 */
	
	public function commit_transaction() {
		
		$this->query('COMMIT');
	}
	
	/**
	 * FUNZIONE PER ANNULLARE UNA TRANSACTION
	 *
	 */
	
	public function rollback_transaction() {
		$this->query('ROLLBACK');
	}

	/**
	 * @param unknown_type $throwException
	 */
	public function throwException($throwException) {
		$this->throwException = $throwException;
		return $this;
	}	
}

//$var = $connect->send_query("SELECT * FROM global_text ORDER BY id", $db_name);

/*$array = array 
		(
			"menu_1"	=>	"asd",
			"menu_2"	=>	"roof",
			"menu_3"	=>	"mau",
			"menu_4"	=>	"bau"
		);

$var = $connect->send_dinamic_query('menu', $array, $db_name);*/
?>