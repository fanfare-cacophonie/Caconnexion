<?php

require 'lib/Fanfaron.class.php';

session_start();

require 'conf.php';
require 'lib/Sql.class.php';
require 'lib/jsConnect.php';
Sql::init($config['database']);

$user = array();
if (isset($_SESSION['SM_sso_user'])) {
	$data = $_SESSION['SM_sso_user']->getPublicData();
    $user['uniqueid'] = $data['id'];
    $user['name'] = $data['surnom'];
    $user['email'] = $data['email'];
    $user['photourl'] = '';
	if ($data['droits'] == Droits::Admin)
		$user['roles'] = 'REGISTERED,GLOBAL_MODERATORS,ADMINISTRATORS';
	else if ($data['droits'] == Droits::Modo)
		$user['roles'] = 'REGISTERED,GLOBAL_MODERATORS';
	else
		$user['roles'] = 'REGISTERED';
}

// Generate the jsConnect string.
// This should be true unless you are testing.
// You can also use a hash name like md5, sha1 etc which must be the name as the connection settings in Vanilla.
writeJsConnect($user, $_GET, $config['sso_vanilla']['clientID'], $config['sso_vanilla']['secret'], 'sha256');
