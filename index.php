<?php

require 'lib/Fanfaron.class.php';

session_start();

if (isset($_SESSION['SM_sso_user'])) {
	echo "Bonjour " . $_SESSION['SM_sso_user']->getPublicData()['surnom'];
}
else {
	echo "Non connecté.";
}

