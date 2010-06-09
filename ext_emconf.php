<?php

########################################################################
# Extension Manager/Repository config file for ext: "token_auth"
#
# Auto generated 22-02-2009 21:03
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Simple token authentication',
	'description' => 'Allow FE authentication based on the verification of a simple token.',
	'category' => 'services',
	'author' => 'Francois Suter (Cobweb)',
	'author_email' => 'typo3@cobweb.ch',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '4.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:9:"ChangeLog";s:4:"d7b8";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"5b29";s:14:"ext_tables.php";s:4:"4fde";s:14:"ext_tables.sql";s:4:"79f8";s:16:"locallang_db.xml";s:4:"4a7e";s:19:"doc/wizard_form.dat";s:4:"4c8c";s:20:"doc/wizard_form.html";s:4:"8399";s:30:"sv1/class.tx_tokenauth_sv1.php";s:4:"4ead";}',
);

?>