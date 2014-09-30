<?php

class DataBase extends Tool {

	private $connections = [];
	private $defaultConnection = null;

	public function openConnection($dsn, $username = null, $password = null, $options = null, $name = null) {
		$con = new DataBaseConnection($dsn, $username, $password, $options);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if (!$this->defaultConnection) {
			$this->defaultConnection = $con;
		}

		$this->connections[$name] = $con;
	}

	public function getConnection($name = null) {
		return ($name ? $this->connections[$name] : $this->defaultConnection);
	}

}

class DataBaseConnection extends PDO {

	public function __construct($dsn, $username = null, $password = null, $options = null) {
		parent::__construct($dsn, $username, $password, $options);
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function fetchAllAssoc($sql, $bind = []) {
		$st = $this->prepare($sql);

		foreach ($bind as $key => $value) {
			$st->bindValue(':'.$key, $value);
		}

		$st->execute();

		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

}
