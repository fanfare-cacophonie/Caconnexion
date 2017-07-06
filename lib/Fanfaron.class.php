<?php

class Fanfaron {
	private $id;
	private $surnom;
	private $email;
	private $mdp;
	private $instru;
	private $instru2;
	private $status;
	private $generation;
	private $droits;
	private $tel;
	
	public function __construct($infos = array()) {
		$this->id = isset($infos['id']) ? $infos['id'] : -1;
		$this->surnom = isset($infos['surnom']) ? $infos['surnom'] : '';
		$this->email = isset($infos['email']) ? $infos['email'] : '';
		$this->mdp = isset($infos['mdp']) ? $infos['mdp'] : '';
		$this->instru = isset($infos['instru']) ? $infos['instru'] : '';
		$this->instru2 = isset($infos['instru2']) ? $infos['instru2'] : '';
		$this->status = isset($infos['status']) ? $infos['status'] : Status::_1A;
		$this->generation = isset($infos['generation']) ? $infos['generation'] : -1;
		$this->droits = isset($infos['droits']) ? $infos['droits'] : Droits::Standard;
		$this->tel = isset($infos['tel']) ? $infos['tel'] : '';
	}
	
	public static function getFanfarons($crits = array()) {
		$fanfarons = Sql::getSql()->select('Fanfarons', array('*'), $crits);
		if (count($fanfarons) == 0)
			return false;

		$ret = array();
		foreach ($fanfarons as $f)
			$ret[] = new Fanfaron($f);
		return $ret;
	}
	
	public static function getFanfaron($crits = array()) {
		$fanfarons = self::getFanfarons($crits);
		if ($fanfarons == false)
			return false;
		else if (count($fanfarons) == 1)
			return $fanfarons[0];
		throw new Exception("La requête a retournée plusieurs résultats.");
	}
	
	public function checkMdp($mdp) {
		$mdp = hash('sha256', $mdp);
		if ($this->mdp == $mdp)
			return true;
		return false;
	}
	
	public function getPublicData() {
		return array(
			'id' => $this->id,
			'surnom' => $this->surnom,
			'email' => $this->email,
			'instru' => $this->instru,
			'instru2' => $this->instru2,
			'status' => $this->status,
			'generation' => $this->generation,
			'droits' => $this->droits,
			'tel' => $this->tel
		);
	}
	
	private function getData() {
		return array(
			'surnom' => $this->surnom,
			'email' => $this->email,
			'mdp' => $this->mdp,
			'instru' => $this->instru,
			'instru2' => $this->instru2,
			'status' => $this->status,
			'generation' => $this->generation,
			'droits' => $this->droits,
			'tel' => $this->tel
		);
	}

	public function set($infos) {
		if (!is_array($infos) || empty($infos))
			return;

		$this->surnom = isset($infos['surnom']) ? $infos['surnom'] : $this->surnom;
		$this->email = isset($infos['email']) ? $infos['email'] : $this->email;
		$this->instru = isset($infos['instru']) ? $infos['instru'] : $this->instru;
		$this->instru2 = isset($infos['instru2']) ? $infos['instru2'] : $this->instru2;
		$this->status = isset($infos['status']) ? $infos['status'] : $this->status;
		$this->generation = isset($infos['generation']) ? $infos['generation'] : $this->generation;
		$this->droits = isset($infos['droits']) ? $infos['droits'] : $this->droits;
		$this->tel = isset($infos['tel']) ? $infos['tel'] : $this->tel;
	}
	
	public function save() {
		if ($this->id == -1) {
			// création
			$this->id = Sql::getSql()->insert('Fanfarons', $this->getData());
		}
		else {
			// mise à jour
			Sql::getSql()->update('Fanfarons', $this->getData(), array('id' => $this->id));
		}
	}

	public function checkPermission($perm) {
		return $this->droits == $perm;
	}
}

abstract class Droits {
	const Standard = 0;
	const Modo = 1;
	const Admin = 2;
}

abstract class Status {
	const _1A = 0;
	const Vieux = 1;
	const Actif = 2;
}
