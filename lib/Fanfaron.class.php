<?php

class Fanfaron {
	private $id = -1;
	private $surnom = '';
	private $email = '';
	private $mdp = '';
	private $instru = '';
	private $instru2 = '';
	private $status = Status::_1A;
	private $generation = -1;
	private $droits = Droits::Standard;
	private $tel = '';
	
	public function __construct($infos = array()) {
		if (isset($infos['id'])) {
			if (is_numeric($infos['id']))
				$this->id = intval($infos['id']);
			else
				throw new Exception('ID invalide (!)');
		}
		
		$this->set($infos);
		// We can set the password without hashing it in the constructor, because it can come from the database, where the passwords are already hashed.
		$this->mdp = $infos['mdp'];
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
		if ($this->mdp == hash('sha256', $mdp))
			return true;
		include 'lib/phpbb.php';
		if (phpbb_check_hash($mdp, $this->mdp))
			return true;
		return false;
	}
	
	public function setMdp($mdp) {
		if (empty($mdp))
			throw new Exception('Mot de passe vide');
		$this->mdp = hash('sha256', $mdp);
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

		if (isset($infos['surnom'])) {
			if (!empty($infos['surnom']))
				$this->surnom = $infos['surnom'];
			else
				throw new Exception('Surnom invalide');
		}

		if (isset($infos['email'])) {
			if(filter_var($infos['email'], FILTER_VALIDATE_EMAIL))
				$this->email = $infos['email'];
			else
				throw new Exception('E-mail invalide');
		}

		if (isset($infos['instru'])) {
			if (!empty($infos['instru']))
				$this->instru = $infos['instru'];
			else
				throw new Exception('Instru invalide');
		}

		if (isset($infos['instru2']) && !empty($infos['instru2']))
			$this->instru2 = $infos['instru2'];
		// We don't throw exception for 'instru2', it is not required

		if (isset($infos['status'])) {
			if (Status::validate($infos['status']))
				$this->status = intval($infos['status']);
			else
				throw new Exception('Status invalide');
		}

		if (isset($infos['generation'])) {
			if (is_numeric($infos['generation']))
				$this->generation = intval($infos['generation']);
			else
				throw new Exception('Generation invalide');
		}

		if (isset($infos['droits'])) {
			if (Droits::validate($infos['droits']))
				$this->droits = intval($infos['droits']);
			else
				throw new Exception('Droits invalide');
		}

		if (isset($infos['tel'])) {
			if (!empty($infos['tel']))
				$this->tel = $infos['tel'];
			else
				throw new Exception('N° de téléphone invalide');
		}
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
	
	public static function validate($var) {
		return is_numeric($var) && $var >= self::Standard && $var <= self::Admin;
	}
}

abstract class Status {
	const _1A = 0;
	const Vieux = 1;
	const Actif = 2;
	
	public static function validate($var) {
		return is_numeric($var) && $var >= self::_1A && $var <= self::Actif;
	}
}
