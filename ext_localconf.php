<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addService($_EXTKEY,  'auth' /* sv type */,  'tx_tokenauth_sv1' /* sv key */,
	array(
		'title' => 'Token authentication',
		'description' => 'Authenticate a FE user based on a simple token',

		'subtype' => 'getUserFE,authUserFE',

		'available' => TRUE,
		'priority' => 60,
		'quality' => 50,

		'os' => '',
		'exec' => '',

		'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_tokenauth_sv1.php',
		'className' => 'tx_tokenauth_sv1',
	)
);
?>