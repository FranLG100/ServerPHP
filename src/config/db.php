<?php
  class db{
    private $dbHost ='127.0.0.1';
    private $dbUser = 'dam_dam_admin';
    private $dbPass = '**LorenPeda123**';
    private $dbName = 'dam_reservas';
	private $dbNameCsv = 'dam_csv';
    //conección 
    public function conectDB(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbName";
      $dbConnexion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnexion;
    }
	  
	  public function conectDBCSV(){
      $mysqlConnect = "mysql:host=$this->dbHost;dbname=$this->dbNameCsv";
      $dbConnexion = new PDO($mysqlConnect, $this->dbUser, $this->dbPass);
      $dbConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $dbConnexion;
    }
  }