<?php

// Koneksi Database menggunakan PDO

Class db {
	private $server	= "mysql:host=localhost;dbname=responsi";

	private $user	= "root";

	private $pass	= "";
	
	private $option	= array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);

	protected $con;

	public function openConnection() {

		try {

			$this->con = new PDO($this->server, $this->user, $this->pass, $this->option);
			return $this->con;

		} catch (PDOException $e) {

			echo "Connection Error: " . $e->getMessage();

		}

	}

	public function closeConnection() {

		$this->con = null;
		
	}
}

?>