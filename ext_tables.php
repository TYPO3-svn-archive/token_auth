<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_tokenauth_token' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:token_auth/locallang_db.xml:fe_users.tx_tokenauth_token',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'trim',
		)
	),
);


t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('fe_users','tx_tokenauth_token;;;;1-1-1');
?>