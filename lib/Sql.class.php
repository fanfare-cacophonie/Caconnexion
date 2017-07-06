<?php

class Sql {
	private $config;
	private $bdd = null;
	private $prefix;
	private static $instance = null;
	
	public static function getSql() {
		if (is_null(self::$instance)) {
			throw new Exception('Uninitialized SQL connection');
		}
		return self::$instance;
	}
	
	public static function init($config) {
		self::$instance = new Sql($config);
	}

	private function __construct($config) {
		$this->config = $config;
		$this->prefix = isset($config['prefix']) ? $config['prefix'] : '';
	}

	private function bdd() {
		if (is_null($this->bdd)) {
			$this->bdd = new PDO('mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['dbname'], $this->config['user'], $this->config['passwd']);
		}
		return $this->bdd;
	}

	public function select($table, $objs = array('*'), $crits = array()) {
		$sql = 'SELECT ' . implode($objs, ',') . ' FROM `' . $this->prefix . $table . '`';
		if (!empty($crits)) {
			$sql .= ' WHERE ';
			foreach ($crits as $k => $v) {
				$sql .= $k . ' = :' . $k . ', ';
			}
			$sql = substr($sql, 0, -2);
		}
		$req = $this->bdd()->prepare($sql);
		$req->execute($crits);

		$error = $req->errorInfo();
		if ($error[1] != 0)
			throw new Exception(error[2]);

		return $req->fetchAll();
	}

	public function update($table, $values, $crits) {
		$sql = 'UPDATE `' . $this->prefix . $table . '` SET ';
		foreach ($values as $k => $v) {
			$sql .= $k . ' = :' . $k . ', ';
		}
		$sql = substr($sql, 0, -2);
		$sql .= ' WHERE ';
		foreach ($crits as $k => $v) {
			$sql .= $k . ' = :' . $k . ', ';
		}
		$sql = substr($sql, 0, -2);
		$req = $this->bdd()->prepare($sql);
		$req->execute(array_merge($values, $crits));

		$error = $req->errorInfo();
		if ($error[1] != 0)
			throw new Exception(error[2]);
	}

	public function insert($table, $values) {
		$sql = 'INSERT INTO `' . $this->prefix . $table . '` (';
		$keys = '';
		$vals = '';
		foreach ($values as $k => $v) {
			$keys .= $k . ', ';
			$vals .= ':' . $k . ', ';
		}
		$keys = substr($keys, 0, -2);
		$vals = substr($vals, 0, -2);
		$sql .= $keys . ') VALUES (' . $vals . ')';
		$req = $this->bdd()->prepare($sql);
		$req->execute($values);

		$error = $req->errorInfo();
		if ($error[1] != 0)
			throw new Exception(error[2]);

		return $this->id = $this->bdd()->lastInsertId();
	}
	
	
}
