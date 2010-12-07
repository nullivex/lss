<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Db {

	static $inst = false;

	private $config;
	private $pdo;
	private $connected = false;
	private $query_count = 0;

	public static function _get(){
		if(self::$inst == false) self::$inst = new Db();
		return self::$inst;
	}

	public function setConfig($config){
		$this->config = $config;
		return $this;
	}

	public function connect(){
		try{
			$this->pdo = new PDO(
				$this->config['driver'].':dbname='.$this->config['database'].
				';host='.$this->config['host'].';port='.$this->config['port'],
				$this->config['user'],
				$this->config['password'],
				array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION)
			);
			$this->connected = true;
		}
		catch(PDOException $error){
			$this->connected = false;
			throw new Exception("Database Connection Failed: ".$error->getMessage());
		}
		return $this;
	}

	public function exec($statement){
		$this->query_count++;
		return $this->pdo->exec($statement);
	}

	public function prepare($statement,$driver_options=array()){
		$this->query_count++;
		return $this->pdo->prepare($statement,$driver_options);
	}

	public function query($statement){
		$this->query_count++;
		return $this->pdo->query($statement);
	}

	public function getQueryCount(){
		return $this->query_count;
	}

	public function __call($function_name, $parameters) {
		return call_user_func_array(array($this->pdo, $function_name), $parameters);
	}

}
