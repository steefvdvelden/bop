<?php
	class Db
	{
		private $connection;
		private $selectdb;
		private $lastQuery;
		private $config;	
	
		function __construct($config){	
			$this->config = $config;
		}
		
		function __destruct(){
		}
		
		public function openConnection(){
			try{
				$this->connection = mysqli_connect($this->config->hostname, $this->config->username, $this->config->password);
				$this->selectdb = mysqli_select_db($this->connection, $this->config->database);
			}
			catch(exception $e){
				echo $e;
			}
		}
		
		public function closeConnection(){
			try{
				mysqli_close($this->connection);			
			}
			catch(exception $e){
				echo $e;			
			}		
		}
		
		public function escapeString($string){
			return addslashes($string);
		}
				
		public function query($query){
			$query = str_replace("}", "", $query);
			$query = str_replace("{", $this->config->prefix, $query);			
			try{
				if(empty($this->connection)){
					$this->openConnection();
					$this->lastQuery = mysqli_query($this->connection, $query);
					$this->closeConnection();
					return $this->lastQuery;					
				}
				else {
					$this->lastQuery = mysqli_query($this->connection, $query);
					return $this->lastQuery;			
				}			
			}
			catch(exception $e){
				return $e;			
			}
		}
		
		public function insert_query($query){
			$query = str_replace("}", "", $query);
			$query = str_replace("{", $this->config->prefix, $query);
			try{
				if(empty($this->connection)){
					$this->openConnection();
					$result=mysqli_query($this->connection, $query);
					if (!$result)
						$this->lastQuery = mysqli_error($this->connection);
					else
						$this->lastQuery = mysqli_insert_id($this->connection);
				$this->closeConnection();
				}
				else{
					$result=mysqli_query($this->connection, $query);
					if (!$result)
						$this->lastQuery = mysqli_error($this->connection);
					else
						$this->lastQuery = mysqli_insert_id($this->connection);
				}
				return $result;
			}
			catch(exception $e){
				$this->lastQuery = $e;
				return false;
			}
		}
		
		public function lastQuery(){
			return $this->lastQuery;		
		}	
		
		public function pingServer(){
			try{
				if(!mysqli_ping($this->connection))
					return false;
				else 
					return true;
			}
			catch(exception $e){
				return $e;
			}		
		}	
		
		public function hasRows($result){
			try{
				if(mysqli_num_rows($result)>0)
					return true;					
				else 
					return false;
			}
			catch(exception $e){
				return $e;
			}
		}
		
		public function countRows($result){
			try{
				return mysqli_num_rows($result);				
			}
			catch(exception $e){
				return $e;
			}
		}
		
		public function fetchAssoc($result){
			try{
				return mysqli_fetch_assoc($result);				
			}
			catch(exception $e){
				return $e;			
			}
		}
		
		public function fetchArray($result){
			try{
				return mysqli_fetch_array($result);
			}	
			catch(exception $e){
				return $e;			
			}	
		}
		
		/**
		 * 
		 * Haal gegevens op met een query en retourneer het resultaat als een DOMDocument
		 * De XML structuur heeft als root tag &lt'elementname'+'s'&gt, er kunnen meerdere records opgehaald worden
		 * Elk record wordt in een element met de tag &ltelementname&gt). Het ziet er dus als volgt uit:
		 * &ltelementnames&gt
		 *     &ltelementname&gt
		 *        &ltveld_1&gtwaarde veld_1&lt/veld_1&gt
		 *        &ltveld_2&gtwaarde veld_2&lt/veld_2&gt
		 *        ....
		 *     &lt/elementname&gt
		 *     &ltelementname&gt
		 *        &ltveld_1&gtwaarde veld_1&lt/veld_1&gt
		 *        &ltveld_2&gtwaarde veld_2&lt/'veld_2&gt
		 *        ....
		 *     &lt/elementname&gt
		 *     ...
		 * &lt/elementnames&gt
		 * @param	string	$query	Het SQL statement dat moet worden uitgevoerd
		 * @param	string	$elementName	de naam van de root-tag van een record
		 * @param	string	$namespace	De prefix die gebruikt moet worden om de namespace aan te geven
		 * @param	string	$nsURL	De namespace, URL die uniek is.
		 * @return	DOMDocument	Het resultaat van de query, XML bestand verpakt als DOMDocument
		 */       
		public function getXMLRecordSet($query, $elementName = "element", $namespace="", $nsURL=""){
			$returnValue = "";
			$returnValue .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURL.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			try {
				$queryResult = $this->query($query);
				if (!$this->lastQuery)
					$returnValue.="<$elementName><{$namespace}error>".mysqli_error($this->connection).
								  "</{$namespace}error></$elementName>";
				else{
					while ($book = $this->fetchAssoc($queryResult)) {
						$returnValue .= "<$elementName>";
						foreach ($book as $key => $value) 
							$returnValue .= "<$key>$value</$key>";
						$returnValue .= "</$elementName>";
					}
				}
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
			catch(exception $e){
				$returnValue.="<$elementName><error>$e</error></$elementName>";
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
		}
		
			/**
		 * De functie voert een insert of update query uit en retourneert het ID van het aangemaakte record.
		 * ID wordt alleen bepaald als de tabel auto-nummering heeft. Als de insert mislukt wordt de error 
		 * message terug gemeld onder de tag &lterror&gt, en de waarde 0 voor het ID.
		 * @param string $query	De uit te voeren insert query
		 * @param string $elementName	de naam van de root-tag van het retourbericht
		 * @param string $namespace	De prefix die gebruikt moet worden om de namespace aan te geven
		 * @param string $nsURL	De namespace, URL die uniek is.
		 * @param boolean $isUpdate Is het een update (TRUE) of een insert (FALSE) opdracht
		 * @return DOMDocument	Het resultaat van de insert, XML bestand verpakt als DOMDocument
		 */
		public function getXMLUpdateResult($query, $elementName = "element", $namespace="", $nsURL="", $isUpdate){
			$returnValue = "";
			$returnValue .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURL.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			try {
				foreach ($query as $queryline) {
						$queryResult = $this->insert_query($queryline);
						if (!$queryResult) {
							$returnValue.="<$elementName><error>".$this->lastQuery.$queryline."</error>";
							if (!$isUpdate) $returnValue.="<Inserted_record_ID>0</Inserted_record_ID>";
							$returnValue.="</$elementName>";
						}
						else{
							$returnValue.="<$elementName>";
							$returnValue.=($isUpdate) ? "<updateResult>Succesvol</updateResult>" : "<Inserted_record_ID>".$this->lastQuery."</Inserted_record_ID>";
							$returnValue.="</$elementName>";
						}
					}
				
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
			catch(exception $e){
				$returnValue.="<$elementName><error>$e</error>";
				if (!$isUpdate) $returnValue.="<Inserted_record_ID>0</Inserted_record_ID>";
				$returnValue.="</$elementName>";
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
		}
				/**
		 * De functie voert een insert of update query uit en retourneert het ID van het aangemaakte record.
		 * ID wordt alleen bepaald als de tabel auto-nummering heeft. Als de insert mislukt wordt de error 
		 * message terug gemeld onder de tag &lterror&gt, en de waarde 0 voor het ID.
		 * @param string $service	De service waarin de error is ontstaan
		 * @param string $elementName	de naam van de root-tag van het retourbericht
		 * @param string $namespace	De prefix die gebruikt moet worden om de namespace aan te geven
		 * @param string $nsURL	De namespace, URL die uniek is.
		 * @return DOMDocument	Het resultaat van de insert, XML bestand verpakt als DOMDocument
		 */
		public function getXMLError($service, $elementName = "element", $namespace="", $nsURL="", $error = "Unknown Error"){
			$returnValue = "";
			$returnValue .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURL.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			$returnValue.="<$elementName><service>".$service."</service><error>".$error."</error>";
			$returnValue.="</$elementName>";
			$returnValue .= "</{$elementName}s>";
			$result = new DOMDocument("1.0");
			$result->loadXML($returnValue);
			return $result;
		}
	}	
	
	class config{
	
		public $hostname;
		public $username;
		public $password;
		public $database;
		public $prefix;
		public $connector;
			
		function __construct($hostname = NULL, 
									$username = NULL, 
									$password = NULL, 
									$database = NULL, 
									$prefix = NULL, 
									$connector = NULL){
			$this->hostname = !empty($hostname) ? $hostname : "";
			$this->username = !empty($username) ? $username : "";
			$this->password = !empty($password) ? $password : "";
			$this->database = !empty($database) ? $database : "";
			$this->prefix = !empty($prefix) ? $prefix : "";
			$this->connector = "mysqli";			
		}	
		
		function __destruct(){
		}
	}

	class AccessDb{
		private $server;
		private $user;
		private $pass;
		private $db;
		private $constring;
		private $resultSet;
		public  $conn;
		public  $rowCount;
	
		function __construct($dbName, $user = "", $pass = ""){
			$this->db = !empty($dbName) ? $dbName : "";
			$this->user = !empty($user) ? $user : "";
			$this->pass = !empty($pass) ? $pass : "";
			$dbLoc = "C:\\test\\".$this->db;
			$this->constring = 'odbc:DRIVER={Microsoft Access Driver (*.mdb)};Dbq='.$dbLoc.'; '.
							   'Uid='.$user.'; Pwd='.$pass.';';
		}
	
		public function connect(){
			try {
				$this->conn = new PDO($this->constring);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
			}
			catch(exception $e){
				echo $e;
			}
		}
				
		public function disconnect(){
			$this->conn=null;
		}
	
		public function executeQuery($sqlString){
			try {
				$this->resultSet=$this->conn->query($sqlString);
			}
			catch (exception $e){
				$this->resultSet = $e;
			}
		}
		
		public function fetchArrayList(){
			$row = array();
			$rows = array();
			$rowsCount=0;

			$result = $this->resultSet;
				
			while ($result->fetch(PDO::FETCH_ASSOC)){
			    //  loop over the object directly 
	    		foreach($result as $key => $val){
	    			$row[$key]=$val;
	    		}
	    		$rows[$rowsCount]= $row;
	    		$rowsCount++;
			}
			return $rows;
		}
	
		/**
		 * Deze functie zet de gegevens van de laatst opgehaalde query om naar XML formaat
		 * en retourneert die als DOMDocument
		 * @param string $sqlString Het SQL statement dat uitgevoerd moet worden
		 * @param string $elementName De naam van de gegevensset (root tag van de XML)
		 * @param unknown $namespace De prefix van de namespace die gebruikt wordt
		 * @param unknown $nsURI De URL die de namespacce identificeert
		 * @return DOMDocument Het XML bestand met de query gegevens
		 */
		public function fetchXMLRecordSet($sqlString, $elementName = "element",$namespace,$nsURI){
			$this->executeQuery($sqlString);
			$returnValue = "";
			$returnValue = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURI.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			try{
				$result = $this->resultSet->fetch(PDO::FETCH_ASSOC);
				while ($result){
		    		$returnValue.="<$elementName>";
					//  loop over the object directly 
		    		foreach($result as $key=>$val){
		    			$returnValue.="<$key>$val</$key>";
		    		}
					$returnValue .= "</$elementName>";
					$result = $this->resultSet->fetch(PDO::FETCH_ASSOC);
				}
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
			catch(exception $e){
		    	$returnValue.="<$elementName>";
				$returnValue.= "<error>$e</error>";
				$returnValue.="</$elementName>";
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
		}
			/**
		 * Deze functie zet de gegevens van de laatst opgehaalde query om naar XML formaat
		 * en retourneert die als DOMDocument
		 * @param string $sqlString Het SQL statement dat uitgevoerd moet worden
		 * @param string $elementName De naam van de gegevensset (root tag van de XML)
		 * @param unknown $namespace De prefix van de namespace die gebruikt wordt
		 * @param unknown $nsURI De URL die de namespacce identificeert
		 * @return DOMDocument Het XML bestand met de query gegevens
		 */
		public function fetchXMLUpdateResult($sqlString, $elementName = "element",$namespace,$nsURI){
			foreach ($sqlString as $queryline) 
				$this->executeQuery($queryline);
			$returnValue = "";
			$returnValue = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURI.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			try{
				$this->executeQuery("SELECT @@IDENTITY");
				$result = $this->resultSet->fetch(PDO::FETCH_ASSOC);
				while ($result){
		    		$returnValue.="<$elementName>";
					//  loop over the object directly 
		    		foreach($result as $key=>$val){
		    			$returnValue.="<$key>$val</$key>";
		    		}
					$returnValue .= "</$elementName>";
					$result = $this->resultSet->fetch(PDO::FETCH_ASSOC);
				}
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
			catch(exception $e){
		    	$returnValue.="<$elementName>";
				$returnValue.= "<error>$e</error>";
				$returnValue.="</$elementName>";
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
		}
	}
	class MSSQLDb
	{
		private $connection;
		private $selectdb;
		private $lastQuery;
		private $config;
	
		function __construct($config){
			$this->config = $config;
		}
	
		function __destruct(){
		}
	
		public function openConnection(){
			try{
				$connectionInfo = array( "Database"=>$this->config->database);
				$this->connection = sqlsrv_connect($this->config->hostname, $connectionInfo);
			}
			catch(exception $e){
				echo $e;
			}
		}
	
		public function closeConnection(){
			try{
				sqlsrv_close($this->connection);
			}
			catch(exception $e){
				echo $e;
			}
		}
	
		public function escapeString($string){
			return addslashes($string);
		}
	
		public function query($query){
// 			$query = str_replace("}", "", $query);
// 			$query = str_replace("{", $this->config->prefix, $query);
			try{
				if(empty($this->connection)){
					$this->openConnection();
					$this->lastQuery = sqlsrv_query($this->connection, $query);
					$this->closeConnection();
					return $this->lastQuery;
				}
				else {
 					$this->lastQuery = sqlsrv_query($this->connection, $query);
					return $this->lastQuery;
				}
			}
			catch(exception $e){
				return $e;
			}
		}
	
		public function insert_query($query){
			$query = str_replace("}", "", $query);
			$query = str_replace("{", $this->config->prefix, $query);
			try{
				if(empty($this->connection)){
					$this->openConnection();
					$result=sqlsrv_query($this->connection, $query);
					if (!$result)
						$this->lastQuery = var_dump(sqlsrv_errors());
					else
						$this->lastQuery = sqlsrv_query($this->connection, "SELECT @@IDENTITY AS ins_id");
					$this->closeConnection();
				}
				else{
					$result=sqlsrv_query($this->connection, $query);
					if (!$result)
						$this->lastQuery = var_dump(sqlsrv_errors());
					else
						$this->lastQuery = sqlsrv_query($this->connection, "SELECT @@IDENTITY AS ins_id");
				}
				return $result;
			}
			catch(exception $e){
				$this->lastQuery = $e;
				return false;
			}
		}
	
		public function lastQuery(){
			return $this->lastQuery;
		}
	
	
		public function hasRows($result){
			try{
				if(sqlsrv_has_rows($result)>0)
					return true;
				else
					return false;
			}
			catch(exception $e){
				return $e;
			}
		}
	
		public function countRows($result){
			try{
				return sqlsrv_num_rows($result);
			}
			catch(exception $e){
				return $e;
			}
		}
	
		public function fetchAssoc($result){
			try{
				return sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			}
			catch(exception $e){
				return $e;
			}
		}
	
		public function fetchArray($result){
			try{
				return sqlsrv_fetch_array($result);
			}
			catch(exception $e){
				return $e;
			}
		}
	
		/**
		 *
		 * Haal gegevens op met een query en retourneer het resultaat als een DOMDocument
		 * De XML structuur heeft als root tag &lt'elementname'+'s'&gt, er kunnen meerdere records opgehaald worden
		 * Elk record wordt in een element met de tag &ltelementname&gt). Het ziet er dus als volgt uit:
		 * &ltelementnames&gt
		 *     &ltelementname&gt
		 *        &ltveld_1&gtwaarde veld_1&lt/veld_1&gt
		 *        &ltveld_2&gtwaarde veld_2&lt/veld_2&gt
		 *        ....
		 *     &lt/elementname&gt
		 *     &ltelementname&gt
		 *        &ltveld_1&gtwaarde veld_1&lt/veld_1&gt
		 *        &ltveld_2&gtwaarde veld_2&lt/'veld_2&gt
		 *        ....
		 *     &lt/elementname&gt
		 *     ...
		 * &lt/elementnames&gt
		 * @param	string	$query	Het SQL statement dat moet worden uitgevoerd
		 * @param	string	$elementName	de naam van de root-tag van een record
		 * @param	string	$namespace	De prefix die gebruikt moet worden om de namespace aan te geven
		 * @param	string	$nsURL	De namespace, URL die uniek is.
		 * @return	DOMDocument	Het resultaat van de query, XML bestand verpakt als DOMDocument
		 */
		public function getXMLRecordSet($query, $elementName = "element", $namespace="", $nsURL=""){
			$returnValue = "";
			$returnValue .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURL.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			try {
				$queryResult = $this->query($query);
				if (!sqlsrv_has_rows($queryResult))
					$returnValue.="<$elementName><{$namespace}error>".var_dump(sqlsrv_errors()).
					"</{$namespace}error></$elementName>";
				else{
					while ($book = $this->fetchAssoc($queryResult)) {
						$returnValue .= "<$elementName>";
						foreach ($book as $key => $value)
							$returnValue .= "<$key>$value</$key>";
						$returnValue .= "</$elementName>";
					}
				}
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
			catch(exception $e){
				$returnValue.="<$elementName><error>$e</error></$elementName>";
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
		}
	
		/**
		 * De functie voert een insert query uit en retourneert het ID van het aangemaakte record.
		 * ID wordt alleen bepaald als de tabel auto-nummering heeft. Als de insert mislukt wordt de error
		 * message terug gemeld onder de tag &lterror&gt, en de waarde 0 voor het ID.
		 * @param string $query	De uit te voeren insert query
		 * @param string $elementName	de naam van de root-tag van het retourbericht
		 * @param string $namespace	De prefix die gebruikt moet worden om de namespace aan te geven
		 * @param string $nsURL	De namespace, URL die uniek is.
		 * @return DOMDocument	Het resultaat van de insert, XML bestand verpakt als DOMDocument
		 */
			public function getXMLUpdateResult($query, $elementName = "element", $namespace="", $nsURL="", $isUpdate){
			$returnValue = "";
			$returnValue .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
			if ($namespace!=""){
				$returnValue.='<'.$namespace.':'.$elementName.'s xmlns:'.$namespace.'="'.$nsURL.'">';
				$namespace=$namespace.":";
				$elementName=$namespace.$elementName;
			}
			else
				$returnValue .= "<{$elementName}s>";
			try {
				foreach ($query as $queryline) {
						$queryResult = $this->insert_query($queryline);
						if (!$queryResult) {
							$returnValue.="<$elementName><error>".$this->lastQuery.$queryline."</error>";
							if (!$isUpdate) $returnValue.="<Inserted_record_ID>0</Inserted_record_ID>";
							$returnValue.="</$elementName>";
						}
						else{
							$returnValue.="<$elementName>";
							$returnValue.=($isUpdate) ? "<updateResult>Succesvol</updateResult>" : "<Inserted_record_ID>".$this->lastQuery."</Inserted_record_ID>";
							$returnValue.="</$elementName>";
						}
					}
				
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
			catch(exception $e){
				$returnValue.="<$elementName><error>$e</error>";
				if (!$isUpdate) $returnValue.="<Inserted_record_ID>0</Inserted_record_ID>";
				$returnValue.="</$elementName>";
				$returnValue .= "</{$elementName}s>";
				$result = new DOMDocument("1.0");
				$result->loadXML($returnValue);
				return $result;
			}
		}
	}
	
?>