<?php

session_start();

if (isset($_SESSION['SM_sso_user'])) {
	echo "T'es d�j� connect�.";
	die;
}

if (!isset($_POST['surnom']) || !isset($_POST['mdp'])) {
	include 'views/login.php';
	die;
}
else {
	require 'conf.php';
	require 'lib/Sql.class.php';
	Sql::init($config['database']);
	require 'lib/Fanfaron.class.php';
	$fanfaron = Fanfaron::getFanfaron(array('surnom' => $_POST['surnom']));
	if ($fanfaron->checkMdp($_POST['mdp'])) {
		$_SESSION['SM_sso_user'] = $fanfaron;
		echo "Tu es connect�.";
	}
	else {
		echo "Erreur de connexion.";
	}
}
