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

echo "Page d'admin.";
