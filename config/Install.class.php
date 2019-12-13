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
            $this->servername = "localhost";
			$this->username = "id11838557_pm";
			$this->password = "123456";
			$this->dbname = "id11838557_pm";
			$this->charset = "utf8mb4";

			try
			{
				$dns = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset;
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