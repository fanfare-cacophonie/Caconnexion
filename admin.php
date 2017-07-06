<?php
die;
session_start();


require 'conf.php';
require 'lib/Sql.class.php';
Sql::init($config['database']);
require 'lib/Fanfaron.class.php';

$moi = new Fanfaron(array(
	'surnom' => 'Sauce Maison',
	'email' => 'smaiz@smaiz.fr',
	'mdp' => hash('sha256', '19941994/'),
	'instru' => 'trompette',
	'status' => Status::Actif,
	'generation' => 9,
	'droits' => Droits::Admin
));

$moi->save();
