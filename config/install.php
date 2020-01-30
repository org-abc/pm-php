<?php

	include_once "database.php";

    class Config
	{
		private $servername;
		private $username;
		private $password;
		private $dbname;
		private $charset;

		public function connect()
		{
            $this->servername = "35.222.32.203";
			$this->username = "kondie";
			$this->password = "pocketMechanic4321";
			$this->dbname = "pocket_mechanic";
			$this->charset = "utf8mb4";

			try
			{
				$dns = "mysql:dbname=".$this->dbname.";unix_socket=/cloudsql/specials-1523610414581:us-central1:pocket-mechanic";
				$conn = new PDO($dns, $this->username, $this->password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			}
			catch(PDOException $e)
			{
                //echo $e->getMessage();
                return (null);
			}
		}
	}

?>