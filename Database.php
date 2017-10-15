<?php
	class Database
	{
		private $host = null;
		private $db = null;
		private $user = null;
		private $pass = null;

		private $conn = null;

		public  function __construct($settingsFile) {
			$this->loadDBConf($settingsFile);
			$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

			if($this->conn->connect_errno) {
				die('Database object could not connect to database: ' . $this->conn->connect_error);
			}
			$this->conn->close();
		}
		
		private function loadDBConf($settingsFile) {
			$file = fopen($settingsFile, 'r');
			if($file) {
				while(($line = fgets($file)) != false) {
					$settings_tok = explode("=", $line);
					if(strcmp(trim($settings_tok[0]), 'host') == 0) {
						$this->host = trim($settings_tok[1]);
					} else if(strcmp(trim($settings_tok[0]), 'db') == 0) {
						$this->db = trim($settings_tok[1]);
					} else if(strcmp(trim($settings_tok[0]), 'user') == 0) {
						$this->user = trim($settings_tok[1]);
					} else if(strcmp(trim($settings_tok[0]), 'pass') == 0) {
						$this->pass = trim($settings_tok[1]);
					}
				}
			}				
		}

		private function connect() {
			$this->conn->connect($this->host, $this->user, $this->pass, $this->db);
		}

		private function close() {
			$this->conn->close();
		}
		
		public function insertQuery($query) {
			$ret = true;
			$this->connect();
			if(!$this->conn->query($query)) {
				$ret = false;
				echo $this->conn->error;
			}			
			$this->close();

			return $ret;
		}

		public function selectQuery($columns, $table, $where='', $sort='') {
			$arg_num = func_num_args();
			$method = null;
			$ret = null;

			// If we have more than 4 arguments, method specified.
			// (Starting from 0)
			if($arg_num > 3) {
				$method = func_get_arg(4);
			}

			$this->connect();
			$qry = sprintf("SELECT $columns FROM $table %s %s", (strlen($where) > 0)?"WHERE " . $where : "", (strlen($sort) > 0)? $sort : "");

			if ($result = $this->conn->query($qry)) {
				$rows = array();

				// Fetch rows
				while($row = $result->fetch_assoc()) {
					$rows[] = $row;
				}

				// JSON response
				if (strcmp($method,'json') == 0) {
					$ret = json_encode($rows);
				// Other than JSON
				} else {
					$ret = $rows;
				}
			} else {
				echo $this->conn->error;
			}
			$this->close();

			return $ret;
		}
		
		public function escapeString($string) {
			$this->connect();
			$escaped = $this->conn->real_escape_string($string);
			$this->close();
			return $escaped;
		}
		
	}
?>
