<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Francois Suter (Cobweb) <typo3@cobweb.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
*
* $Id$
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_t3lib.'class.t3lib_svbase.php');


/**
 * Service "Token authentication" for the "token_auth" extension.
 *
 * @author	Francois Suter (Cobweb) <typo3@cobweb.ch>
 * @package	TYPO3
 * @subpackage	tx_tokenauth
 */
class tx_tokenauth_sv1 extends tx_sv_authbase {
	public $prefixId = 'tx_tokenauth_sv1';		// Same as class name
	public $scriptRelPath = 'sv1/class.tx_tokenauth_sv1.php';	// Path to this script relative to the extension dir.
	public $extKey = 'token_auth';	// The extension key.
	protected $conf = array(); // Extension configuration
	protected $token;
	
	/**
	 * This method initialises the service and defines its availability
	 *
	 * @return	boolean		True if service is available, false otherwise
	 */
	public function init() {
		$this->conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		if ($this->conf['debug'] || TYPO3_DLOG) {
			t3lib_div::devLog('Initialising', 'token_auth', 0, $this->conf);
		}
			// If no IP mask is defined, all requests should be ignored
			// Consequently make this service unavailable
		if (empty($this->conf['IPmask'])) {
			$available = false;
		}
			// Otherwise the service is always available
		else {
			$available = true;
		}
		return $available;
	}

	/**
	 * This method performs some reinitialisation when the service is called more than once
	 */
	public function reset() {
			// Make sure no token persists from the previous run
		unset($this->token);
	}

	/**
	 * This method tries to match a FE user from the database and returns its record if successful
	 * 
	 * @return	mixed	FE user record, or false if no user was matched
	 */
	public function getUser() {
			// Check if request IP address is within allowed range
		$ip = t3lib_div::getIndpEnv('REMOTE_ADDR');
		if ($this->conf['debug'] || TYPO3_DLOG) {
			t3lib_div::devLog('IP to check: '.$ip, 'token_auth', 0);
		}
		if (t3lib_div::cmpIP($ip, $this->conf['IPmask'])) {
				// Get the token
			$token = t3lib_div::_GP($this->conf['tokenVariable']);
			if ($this->conf['debug'] || TYPO3_DLOG) {
				t3lib_div::devLog('Received token: '.$token, 'token_auth', 0);
			}
				// If token is empty, no user matching can be done
			if (empty($token)) {
				$user = false;
			}
			else {
				$this->token = $token;
					// Received token must match some token in the database
				$whereClause = 'fe_users.' . $this->conf['feusersField'] . ' = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($token, 'fe_users');
					// Add enable fields condition
				$whereClause .= $this->db_user['enable_clause'];
					// If no specific storage pid is defined, use default pid clause
				if (empty($this->conf['storagePID'])) {
					$whereClause .= $this->db_user['check_pid_clause'];
				}
				else {
					$whereClause .= ' AND pid IN ('.$this->conf['storagePID'].')';
				}
					// TODO: add a hook to manipulate where clause (could be used to test expiry of token)
					// Log SQL query to debug
				if ($this->conf['debug'] || TYPO3_DLOG) {
					$query = $GLOBALS['TYPO3_DB']->SELECTquery('*', $this->db_user['table'], $whereClause);
					t3lib_div::devLog($query, 'token_auth', 0);
				}
					// Execute SQL query
				$dbres = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $this->db_user['table'], $whereClause);
				if ($dbres) {
					if ($GLOBALS['TYPO3_DB']->sql_num_rows($dbres) > 0) {
						$user = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbres);
							// TODO: add a hook for postprocessing (e.g. delete token to provide one-time token feature)
					}
					$GLOBALS['TYPO3_DB']->sql_free_result($dbres);
				}
			}
		}
			// IP didn't match allowed range, return false to prevent authentication with this service
		else {
			$user = false;
		}
		if ($this->conf['debug'] || TYPO3_DLOG) {
			if ($user === false) {
				t3lib_div::devLog('No user found', 'token_auth', 2);
			}
			else {
				t3lib_div::devLog('User found', 'token_auth', -1, $user);
			}
		}
		return $user;
	}

	/**
	 * This method performs the authentication by matching the token received
	 *
	 * @param	array	$user: FE user record
	 */
	public function authUser($user) {
		if ($this->token == $user[$this->conf['feusersField']]) {
				// TODO: add a hook for postprocessing (e.g. delete token to provide one-time token feature)
			return 100;
		}
		else {
				// TODO: make this return value configurable (false to make service final)
			return 200;
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/token_auth/sv1/class.tx_tokenauth_sv1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/token_auth/sv1/class.tx_tokenauth_sv1.php']);
}

?>