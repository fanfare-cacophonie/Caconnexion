<?php

require 'lib/Fanfaron.class.php';

session_start();

require 'conf.php';
require 'lib/Sql.class.php';
Sql::init($config['database']);

if (!isset($_SESSION['SM_sso_user']) || !$_SESSION['SM_sso_user']->checkPermission(Droits::Admin)) {
	echo "Accès refusé.";
	die;
}

if (isset($_POST['newone'])) {
	$error = '';
	if (!isset($_POST['surnom']) || empty($_POST['surnom']))
		$error = 'Surnom vide';
	else if (!isset($_POST['email']) || empty($_POST['email']))
		$error = 'E-mail vide';
	else if (!isset($_POST['mdp']) || empty($_POST['mdp']))
		$error = 'Mot de passe vide';
	else if (!isset($_POST['instru']) || empty($_POST['instru']))
		$error = 'Instrument vide';
	else if (!isset($_POST['status']))
		$error = 'Status vide';
	else if (!isset($_POST['generation']) || empty($_POST['generation']))
		$error = 'Génération vide';
	else if (!isset($_POST['droits']))
		$error = 'Droits vide';
	else if (!isset($_POST['tel']) || empty($_POST['tel']))
		$error = 'N° de téléphone vide';
	if (!empty($error)) {
		echo $error;
		exit;
	}
	
	try {
		$fanfaron = new Fanfaron($_POST);
		$fanfaron->setMdp($_POST['mdp']);
		$fanfaron->save();
		echo 'Le fanfaron a bien été ajouté';
	}
	catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
	}
}

include 'views/admin.php';

