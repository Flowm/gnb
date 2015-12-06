<?php

require_once "resource_mappings.php";
require_once getPageAbsolute("account");

date_default_timezone_set("Europe/Berlin");

//TODO: Do we need those?
//ini_set("display_errors",2);
//ini_set("error_reporting",E_ALL|E_STRICT);

/**
 * Singleton class
 */
final class DB {

	//TODO: Set error output to off etc.

	//var $debugMode = false;
	private $debugMode = true;

	private $pdo;

	private $instance;

	private $DB_HOST			= "localhost";
	private $DB_USERNAME		= "samurai";
	private $DB_PASSWORD		= "6JEn7RhLAGaavQTx";
	private $DB_NAME			= "gnbdb";

	public $USER_TABLE_NAME			= "gnbdb.user";
	public $USER_TABLE_KEY			= "id";
	public $USER_TABLE_ROLE			= "role";
	public $USER_TABLE_STATUS		= "status";
	public $USER_TABLE_APPROVER		= "approved_by_user_id";
	public $USER_TABLE_FIRSTNAME	= "first_name";
	public $USER_TABLE_LASTNAME		= "last_name";
	public $USER_TABLE_EMAIL		= "email";
	public $USER_TABLE_SALT			= "pw_salt";
	public $USER_TABLE_HASH			= "pw_hash";
	public $USER_TABLE_AUTHDEV		= "auth_device";
	public $USER_TABLE_PIN			= "pin";
	public $USER_TABLE_PW_RESET		= "pw_reset_hash";
	public $USER_TABLE_PW_RESET_TS	= "pw_reset_hash_timestamp";

	public $TAN_TABLE_NAME			= "gnbdb.tan";
	public $TAN_TABLE_KEY			= "id";
	public $TAN_TABLE_ACCOUNT_ID	= "account_id";
	public $TAN_TABLE_USED_TS		= "used_timestamp";

	public $TRANSACTION_TABLE_NAME	= "gnbdb.transaction";
	public $TRANSACTION_TABLE_KEY	= "id";
	public $TRANSACTION_TABLE_TO	= "destination_account_id";
	public $TRANSACTION_TABLE_FROM	= "source_account_id";
	public $TRANSACTION_TABLE_AP_AT	= "approved_at";
	public $TRANSACTION_TABLE_AP_BY	= "approved_by_user_id";
	public $TRANSACTION_TABLE_STATUS= "status";
	public $TRANSACTION_TABLE_AMOUNT= "amount";
	public $TRANSACTION_TABLE_DESC	= "description";
	public $TRANSACTION_TABLE_TAN	= "tan_id";
	public $TRANSACTION_TABLE_C_TS	= "creation_timestamp";

	public $ACCOUNT_TABLE_NAME		= "gnbdb.account";
	public $ACCOUNT_TABLE_KEY		= "id";
	public $ACCOUNT_TABLE_USER_ID	= "user_id";
	public $ACCOUNT_TABLE_TAN_TIME	= "last_tan_time";

	public $ACCOUNTOVERVIEW_TABLE_NAME		= "gnbdb.account_overview";
	public $ACCOUNTOVERVIEW_TABLE_KEY		= "id";
	public $ACCOUNTOVERVIEW_TABLE_USER_ID	= "user_id";
	public $ACCOUNTOVERVIEW_TABLE_BALANCE	= "balance";
	public $ACCOUNTOVERVIEW_TABLE_TAN_TIME	= "last_tan_time";

	public $FAILEDLOGIN_TABLE_NAME	= "failed_login";
	public $FAILEDLOGIN_TABLE_KEY	= "id";
	public $FAILEDLOGIN_USER_ID		= "user_id";
	public $FAILEDLOGIN_LOGIN_TS	= "login_timestamp";

	public $FAKE_APPROVER_USER_ID = 1;
	public $SCS_DEFAULT_TAN = "ASMARTCARDTANYO";
	
	private $MAGIC = 'SUITUP';
	private $WELCOMECREDIT_DESCRIPTION = 'GNB Welcome Credit';
	private $MAX_FAILED_LOGIN_ATTEMPTS = 5;
	private $PW_RESET_HASH_TIMEOUT = "1 DAY";

	/**
	 * Call this method to get singleton
	 *
	 * @return DB
	 */
	public static function i()
	{
		static $inst = null;
		if ($inst === null) {
			$inst = new DB();
		}
		return $inst;
	}

	/**
	 * Private ctor so nobody else can instance it
	 *
	 */
	private function __construct()
	{
		try {
			$this->pdo = new PDO('mysql:host=' . $this->DB_HOST . ';dbname=' . $this->DB_NAME . ';charset=utf8',
				$this->DB_USERNAME, $this->DB_PASSWORD);
		} catch (PDOException $ex) {
			if ($this->debugMode) {
				var_dump($ex);
			}
		}
	}

	function debug($message)
	{
		if ($this->debugMode) {
			echo "$message <br/>";
		}
	}

	function mapUserRole($key)
	{
		$USER_ROLES = array(
			'client'		=> 0,
			'employee'		=> 1,
			0				=> 'client',
			1				=> 'employee',
		);

		return $USER_ROLES[$key];
	}

	function mapUserStatus($key)
	{
		$USER_STATUS = array(
			'unapproved'	=> 0,
			'approved'		=> 1,
			'rejected'		=> 2,
			'blocked'		=> 3,
			0				=> 'unapproved',
			1				=> 'approved',
			2				=> 'rejected',
			3				=> 'blocked',
		);

		return $USER_STATUS[$key];
	}

	function mapTransactionStatus($key)
	{
		$TRANSACTION_STATUS = array(
			'unapproved'	=> 0,
			'approved'		=> 1,
			'rejected'		=> 2,
			0				=> 'unapproved',
			1				=> 'approved',
			2				=> 'rejected',
		);

		return $TRANSACTION_STATUS[$key];
	}

	function mapAuthenticationDevice($key)
	{
		$AUTHENTICATION_DEVICE = array(
			'none'			=> 0,
			'TANs'			=> 1,
			'SCS'			=> 2,
			0				=> 'none',
			1				=> 'TANs',
			2				=> 'SCS',
		);

		return $AUTHENTICATION_DEVICE[$key];
	}

	/************************************************
	 * MISC FUNCTIONS
	 ************************************************/

	function recordIsInTable($record_value, $record_name, $table_name)
	{
		$SQL = "SELECT *
				FROM $table_name
				WHERE
					$record_name=:record_value
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':record_value', $record_value, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($result != -1 && sizeof($result) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function executeSetStatement($statement)
	{
		$this->pdo->exec($statement);
	}

	function getEmployeeStatus($employee_ID)
	{
		$employee = $this->getEmployee($employee_ID);

		if ($employee == false) {
			return false;
		}

		return $employee[$this->USER_TABLE_STATUS];
	}


	function loginUser($mail, $password)
	{

		$SQL = "SELECT
					$this->USER_TABLE_KEY,
					$this->USER_TABLE_SALT
				FROM $this->USER_TABLE_NAME
				WHERE
					$this->USER_TABLE_EMAIL=:mail
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) != 1) {
			return false;
		}

		$user_id = $result[0][$this->USER_TABLE_KEY];
		$salt = $result[0][$this->USER_TABLE_SALT];
		$password_hash = $this->getPasswordHash($password, $salt);

		$status = $this->mapUserStatus('approved');

		$SQL = "SELECT $this->USER_TABLE_KEY
				FROM $this->USER_TABLE_NAME
				WHERE
					$this->USER_TABLE_HASH = :password_hash
					AND
					$this->USER_TABLE_STATUS = :status
					AND
					$this->USER_TABLE_EMAIL = :mail
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':status', $status, PDO::PARAM_INT);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) != 1) {
			$this->handleFailedLogin($user_id);
			return false;
		} else {
			$this->handleSuccessfulLogin($user_id);
			return $this->getUser($result[0][$this->USER_TABLE_KEY]);
		}
	}

	function genRandString($length)
	{
		return substr(bin2hex(openssl_random_pseudo_bytes(round($length/2))), 0, $length);
	}

	function getPasswordHash($password, $salt)
	{
		return hash('sha512', $this->MAGIC . $password . $salt);
	}

	function handleFailedLogin($user_id)
	{
		if (!$this->addFailedLoginAttempt($user_id)) {
			die("COULD NOT ADD FAILED LOGIN ATTEMPT");
		} else {
			
			$fails = $this->getNumberOfFailedLoginAttempts($user_id);
			if ($fails >= $this->MAX_FAILED_LOGIN_ATTEMPTS) {
				$this->blockUser($user_id, $this->FAKE_APPROVER_USER_ID);
				return false;
			} else {
				return true;
			}

		}
	}

	function handleSuccessfulLogin($user_id)
	{
		$SQL = "DELETE FROM $this->FAILEDLOGIN_TABLE_NAME
				WHERE
					$this->FAILEDLOGIN_USER_ID = :user_id
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$result = $stmt->execute();

		return $result;
	}

	function addFailedLoginAttempt($user_id)
	{
		$SQL = "INSERT INTO $this->FAILEDLOGIN_TABLE_NAME
				(
					$this->FAILEDLOGIN_USER_ID,
					$this->FAILEDLOGIN_LOGIN_TS
				)
				VALUES
				(
					:user_id,
					now()
				)";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $this->pdo->lastInsertId();

		if ($result == 0) {
			return false;
		} else {
			return true;
		}
				
	}

	function getNumberOfFailedLoginAttempts($user_id)
	{
		$col = "count";

		$SQL = "SELECT count(*) AS $col
				FROM $this->FAILEDLOGIN_TABLE_NAME
				WHERE
					$this->FAILEDLOGIN_USER_ID = :user_id
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 1) {
			return $result[0][$col];
		} else {
			return false;
		}
	}

	/************************************************
	 * /MISC FUNCTIONS
	 ************************************************/

	/************************************************
	 * USER FUNCTIONS
	 ************************************************/

	function addUser(
		$first_name,
		$last_name,
		$email,
		$password,
		$role_filter,
		$pin,
		$authentication_device='none')
	{
		$role = $this->mapUserRole($role_filter);
		$auth_device = $this->mapAuthenticationDevice($authentication_device);

		$salt = $this->genRandString(8);
		$password_hash = $this->getPasswordHash($password, $salt);

		$SQL = "INSERT INTO $this->USER_TABLE_NAME
				(
					$this->USER_TABLE_FIRSTNAME,
					$this->USER_TABLE_LASTNAME,
					$this->USER_TABLE_EMAIL,
					$this->USER_TABLE_ROLE,
					$this->USER_TABLE_SALT,
					$this->USER_TABLE_HASH,
					$this->USER_TABLE_AUTHDEV,
					$this->USER_TABLE_PIN
				)
				VALUES
				(
					:first_name,
					:last_name,
					:email,
					:role,
					:salt,
					:password_hash,
					:auth_device,
					:pin
				)";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
		$stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->bindValue(':role', $role, PDO::PARAM_INT);
		$stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
		$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':auth_device', $auth_device, PDO::PARAM_INT);
		$stmt->bindValue(':pin', $pin, PDO::PARAM_STR);
		$stmt->execute();

		$result = $this->pdo->lastInsertId();

		if ($result != 0) {
			return $result;
		} else {
			return false;
		}
	}

	function addClient($first_name, $last_name, $email, $password, $pin, $auth_device)
	{
		return $this->addUser($first_name, $last_name, $email, $password, 'client', $pin, $auth_device);
	}

	function addEmployee($first_name, $last_name, $email, $password, $pin)
	{
		return $this->addUser($first_name, $last_name, $email, $password, 'employee', $pin);
	}

	function getUser($user_ID, $role_filter = "")
	{
		$where = "";
		if ($role_filter != "") {
			$role = $this->mapUserRole($role_filter);
			$where = "AND $this->USER_TABLE_ROLE = :role";
		}

		$SQL = "SELECT
					$this->USER_TABLE_KEY,
					$this->USER_TABLE_FIRSTNAME,
					$this->USER_TABLE_LASTNAME,
					$this->USER_TABLE_EMAIL,
					$this->USER_TABLE_STATUS,
					$this->USER_TABLE_ROLE,
					$this->USER_TABLE_APPROVER,
					$this->USER_TABLE_AUTHDEV,
					$this->USER_TABLE_PIN
				FROM $this->USER_TABLE_NAME
				WHERE
					$this->USER_TABLE_KEY = :user_ID
					$where
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':user_ID', $user_ID, PDO::PARAM_INT);
		if ($role_filter != "") {
			$stmt->bindValue(':role', $role, PDO::PARAM_INT);
		}
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 1) {
			return $result[0];
		} else {
			return false;
		}
	}

	function getClient($client_ID)
	{
		return $this->getUser($client_ID, 'client') ;
	}

	function getEmployee($employee_ID)
	{
		return $this->getUser($employee_ID, 'employee') ;
	}

	function getUsersByName($name, $role_filter = "")
	{
		$where = "";
		if ($role_filter != "") {
			$role = $this->mapUserRole($role_filter);
			$where = "AND $this->USER_TABLE_ROLE = :role";
		}

		$name = "%" . $name . "%";

		$SQL = "SELECT
					$this->USER_TABLE_KEY,
					$this->USER_TABLE_FIRSTNAME,
					$this->USER_TABLE_LASTNAME,
					$this->USER_TABLE_EMAIL,
					$this->USER_TABLE_STATUS,
					$this->USER_TABLE_ROLE,
					$this->USER_TABLE_APPROVER
					$this->USER_TABLE_AUTHDEV,
					$this->USER_TABLE_PIN
				FROM $this->USER_TABLE_NAME
				WHERE
					(
						$this->USER_TABLE_FIRSTNAME LIKE :name
						OR
						$this->USER_TABLE_LASTNAME LIKE :name
					)
					$where
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':name', $name, PDO::PARAM_STR);
		if ($role_filter != "") {
			$stmt->bindValue(':role', $role, PDO::PARAM_INT);
		}
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	function getClientsByName($name)
	{
		return $this->getUsersByName($name, 'client');
	}

	function getEmployeesByName($name)
	{
		return $this->getUsersByName($name, 'employee');
	}

	function getUnapprovedUsers($role_filter = "")
	{
		$status = $this->mapUserStatus('unapproved');
		return $this->getUsersByStatus($status, $role_filter);
	}

	function getRejectedUsers($role_filter = "")
	{
		$status = $this->mapUserStatus('rejected');
		return $this->getUsersByStatus($status, $role_filter);
	}

	function getBlockedUsers($role_filter = "")
	{
		$status = $this->mapUserStatus('blocked');
		return $this->getUsersByStatus($status, $role_filter);
	}

	function getUsersByStatus($status, $role_filter = "")
	{
		$where = "";
		if ($role_filter != "") {
			$role = $this->mapUserRole($role_filter);
			$where = "AND $this->USER_TABLE_ROLE = :role";
		}

		$SQL = "SELECT *
				FROM $this->USER_TABLE_NAME
				WHERE
					$this->USER_TABLE_STATUS = :status
					$where
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':status', $status, PDO::PARAM_INT);
		if ($role_filter != "") {
			$stmt->bindValue(':role', $role, PDO::PARAM_INT);
		}
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	function changeUserStatus($user_id, $new_status, $processor_id, $role_filter = "")
	{
		if ($this->getEmployeeStatus($processor_id) != $this->mapUserStatus('approved')) {
			return false;
		}

		$where = "";
		if ($role_filter != "") {
			$role = $this->mapUserRole($role_filter);
			$where = "AND $this->USER_TABLE_ROLE   = :role";
		}

		$setApprover = "";

		if ($new_status == $this->mapUserStatus('approved') ||
			$new_status == $this->mapUserStatus('rejected')) {
			$setApprover = ", $this->USER_TABLE_APPROVER = :processor_id";
		}

		$SQL = "UPDATE $this->USER_TABLE_NAME
				SET
					$this->USER_TABLE_STATUS   = :new_status
					$setApprover
				WHERE
					$this->USER_TABLE_KEY		= :user_id
					AND $this->USER_TABLE_STATUS != :new_status
					$where
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':new_status', $new_status, PDO::PARAM_INT);
		if ($setApprover != "") {
			$stmt->bindValue(':processor_id', $processor_id, PDO::PARAM_INT);
		}
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		if ($where != "") {
			$stmt->bindValue(':role', $role, PDO::PARAM_INT);
		}
		$result = $stmt->execute();

		if ($result == true && $stmt->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}

	function approveEmployee($employee_id, $approver_id)
	{
		return $this->approveUser($employee_id, $approver_id, 'employee');
	}

	function approveClient($client_id, $approver_id)
	{
		return $this->approveUser($client_id, $approver_id, 'client');
	}

	function rejectEmployee($employee_id, $rejector_id)
	{
		return $this->rejectUser($employee_id, $rejector_id, 'employee');
	}

	function rejectClient($client_id, $rejector_id)
	{
		return $this->rejectUser($client_id, $rejector_id, 'client');
	}

	function blockEmployee($employee_id, $blocker_id)
	{
		return $this->blockUser($employee_id, $blocker_id, 'employee');
	}

	function blockClient($client_id, $blocker_id)
	{
		return $this->blockUser($client_id, $blocker_id, 'client');
	}

	function approveUser($user_id, $approver_id, $role_filter = "")
	{
		$new_status = $this->mapUserStatus('approved');
		return $this->changeUserStatus($user_id, $new_status, $approver_id, $role_filter);
	}

	function rejectUser($user_id, $rejector_id, $role_filter = "")
	{
		$new_status = $this->mapUserStatus('rejected');
		return $this->changeUserStatus($user_id, $new_status, $rejector_id, $role_filter);
	}

	function blockUser($user_id, $blocker_id, $role_filter = "")
	{
		$new_status = $this->mapUserStatus('blocked');
		return $this->changeUserStatus($user_id, $new_status, $blocker_id, $role_filter);
	}

	function setPasswordResetHash($mail, $password_reset_hash)
	{
		$SQL = "UPDATE
					$this->USER_TABLE_NAME
				SET
					$this->USER_TABLE_PW_RESET = :password_reset_hash,
					$this->USER_TABLE_PW_RESET_TS = now()
				WHERE
					$this->USER_TABLE_EMAIL = :mail
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':password_reset_hash', $password_reset_hash, PDO::PARAM_STR);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$result = $stmt->execute();

		if ($result == true && $stmt->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}

	function resetPassword($mail, $password, $reset_hash)
	{
		$salt = $this->genRandString(8);
		$password_hash = $this->getPasswordHash($password, $salt);

		$SQL = "UPDATE
					$this->USER_TABLE_NAME
				SET
					$this->USER_TABLE_SALT = :salt,
					$this->USER_TABLE_HASH = :password_hash,
					$this->USER_TABLE_PW_RESET = NULL,
					$this->USER_TABLE_PW_RESET_TS = NULL
				WHERE
					$this->USER_TABLE_EMAIL = :mail
					AND $this->USER_TABLE_PW_RESET = :reset_hash
					AND now() <= date_add(pw_reset_hash_timestamp, INTERVAL $this->PW_RESET_HASH_TIMEOUT);
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
		$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->bindValue(':reset_hash', $reset_hash, PDO::PARAM_STR);

		$result = $stmt->execute();

		if ($result == true && $stmt->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}

	/************************************************
	 * /USER FUNCTIONS
	 ************************************************/

	/************************************************
	 * ACCOUNT FUNCTIONS
	 ************************************************/

	function getAccountDetails($account_ID)
	{
		$SQL = "SELECT 
					$this->ACCOUNTOVERVIEW_TABLE_KEY,
					$this->ACCOUNTOVERVIEW_TABLE_BALANCE,
					$this->ACCOUNTOVERVIEW_TABLE_TAN_TIME
				FROM $this->ACCOUNTOVERVIEW_TABLE_NAME
				WHERE
					$this->ACCOUNTOVERVIEW_TABLE_KEY = :account_ID
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':account_ID', $account_ID, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 1) {
			return $result[0]; 
		} else {
			return false;
		}
	}

	function getAccountsForUser($user_id)
	{
		$SQL = "SELECT
					$this->ACCOUNTOVERVIEW_TABLE_KEY,
					$this->ACCOUNTOVERVIEW_TABLE_BALANCE,
					$this->ACCOUNTOVERVIEW_TABLE_TAN_TIME
				FROM $this->ACCOUNTOVERVIEW_TABLE_NAME
				WHERE
					$this->ACCOUNTOVERVIEW_TABLE_USER_ID = :user_id
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	function getAccountOwnerFromID($account_id){

		$SQL = "SELECT
					CONCAT(
						u.$this->USER_TABLE_FIRSTNAME,
						' ',
						u.$this->USER_TABLE_LASTNAME
					) \"Name\",
					u.$this->USER_TABLE_KEY \"User ID\"
				FROM 
					$this->ACCOUNT_TABLE_NAME b,
					$this->USER_TABLE_NAME u
				WHERE 
					u.$this->USER_TABLE_KEY = b.$this->ACCOUNT_TABLE_USER_ID 
					AND b.$this->ACCOUNT_TABLE_KEY = :account_id
				";
		
		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 1) {
			return $result[0]; 
		} else {
			return false;
		}
	}

	function addAccount($user_id)
	{
		$SQL = "INSERT INTO $this->ACCOUNT_TABLE_NAME
					( $this->ACCOUNT_TABLE_USER_ID )
				VALUES
					( :user_id )
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $this->pdo->lastInsertId();
		
		if ($result != 0) {
			return $result;
		} else {
			return false;
		}
	}

	function addAccountWithBalance($user_id, $balance)
	{
		$new_account_id = $this->addAccount($user_id);

		if ($new_account_id == false) {
			return false;
		}

		$accounts_admin = $this->getAccountsForUser($this->FAKE_APPROVER_USER_ID);
		$account_admin = $accounts_admin[0];
		$src_account = $account_admin['id'];

		$account = new account($account_admin);

		$tans = $account->generateTANs(1);
		$tan = $tans[0];

		$result = $this->processTransaction($src_account,
											$new_account_id,
											$balance,
											$this->WELCOMECREDIT_DESCRIPTION,
											$tan);

		if ($balance >= 10000) {
			$this->approvePendingTransaction($this->FAKE_APPROVER_USER_ID, $result);
		}

		return $new_account_id;
	}

	function setAccountBalance($account_id, $balance)
	{
		return false; //TODO: IMPLEMENT
	}

	/************************************************
	 * /ACCOUNT FUNCTIONS
	 ************************************************/

	/************************************************
	 * TAN FUNCTIONS
	 ************************************************/

	function insertTAN($tan, $account_id)
	{
		if (strlen($tan) != 15) {
			return false;
		}
		
		$SQL = "INSERT INTO $this->TAN_TABLE_NAME
					(
						$this->TAN_TABLE_KEY,
						$this->TAN_TABLE_ACCOUNT_ID
					)
				VALUES
					(
						:tan,
						:account_id
					)
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':tan', $tan, PDO::PARAM_STR);
		$stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$result = $stmt->execute();

		if ($result == true && $stmt->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}

	function checkTanAndSetUsed($account_id, $tan) {

		$SQL = "UPDATE
					$this->TAN_TABLE_NAME
				SET
					$this->TAN_TABLE_USED_TS = now()
				WHERE
					$this->TAN_TABLE_KEY = :tan
					AND $this->TAN_TABLE_ACCOUNT_ID = :account_id
					AND $this->TAN_TABLE_USED_TS IS NULL";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':tan', $tan, PDO::PARAM_STR);
		$stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$result = $stmt->execute();

		if ($result == true && $stmt->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}

	function verifyTANCode($account_id, $tan_code)
	{
		$SQL = "SELECT *
				FROM $this->TAN_TABLE_NAME
				WHERE
					$this->TAN_TABLE_KEY = :tan_code
					AND $this->TAN_TABLE_ACCOUNT_ID = :account_id
					AND $this->TAN_TABLE_USED_TS IS NULL";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':tan_code', $tan_code, PDO::PARAM_STR);
		$stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 1) {
			return $result;
		} else {
			return false;
		}
	}

	function setLastTANTime($account_id, $last_tan_time)
	{
		$SQL = "UPDATE
					$this->ACCOUNT_TABLE_NAME
				SET
					$this->ACCOUNT_TABLE_TAN_TIME = :last_tan_time
				WHERE
					$this->ACCOUNT_TABLE_KEY = :account_id
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':last_tan_time', $last_tan_time, PDO::PARAM_INT);
		$stmt->bindValue(':account_id', $account_id, PDO::PARAM_INT);
		$result = $stmt->execute();

		if ($result == true && ($stmt->rowCount() == 1 OR $stmt->rowCount() == 0)) {
			return true;
		} else {
			return false;
		}
	}

	/************************************************
	 * /TAN FUNCTIONS
	 ************************************************/

	/************************************************
	 * TRANSACTION FUNCTIONS
	 ************************************************/

	function getTransaction($transaction_id)
	{
		$SQL = "SELECT *
				FROM $this->TRANSACTION_TABLE_NAME
				WHERE
					$this->TRANSACTION_TABLE_KEY = :transaction_id";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':transaction_id', $transaction_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (sizeof($result) == 1) {
			return $result[0];
		} else {
			return false;
		}
	}

	function getAccountTransactions($account_ID, $filter = "ALL")
	{
		$where = "";
			# only get transfers to said account
		if ( $filter == 'TO' )
		{
			$where = "WHERE $this->TRANSACTION_TABLE_TO = :account_ID";
		}
		# only get transfers from said account
		elseif ( $filter == 'FROM' )
		{
			$where = "WHERE $this->TRANSACTION_TABLE_FROM = :account_ID";
		}
		# get all transfers for said account
		else
		{
			$where = "WHERE
						$this->TRANSACTION_TABLE_TO 		= :account_ID
						OR $this->TRANSACTION_TABLE_FROM 	= :account_ID
					";
		}

		$SQL = "SELECT *
				FROM $this->TRANSACTION_TABLE_NAME
				$where";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':account_ID', $account_ID, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	function getPendingTransactions()
	{
		$status = $this->mapTransactionStatus('unapproved');

		$SQL = "SELECT *
				FROM $this->TRANSACTION_TABLE_NAME
				WHERE
					$this->TRANSACTION_TABLE_STATUS	= :status
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':status', $status, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	function processTransaction($source, $destination, $amount, $description, $tan, $auth_device = 'TANs')
	{
		$approved_at	= 'NULL';
		$approved_by	= 'NULL';
		$status			= '0';

		if ($source == $destination) {
			return false;
		}
		
		if ($amount <= 0) {
			return false;
		}

		if ($amount < 10000) {
			$approved_at	= 'now()';
			$approved_by	= $this->FAKE_APPROVER_USER_ID;
			$status			= $this->mapTransactionStatus('approved');
		}

		$this->pdo->beginTransaction();

		$src_account_details = $this->getAccountDetails($source);

		if ($src_account_details == false) {
			$this->pdo->rollBack();
			return false;
		}

		$src_balance = $src_account_details[$this->ACCOUNTOVERVIEW_TABLE_BALANCE];

		if ($src_balance < $amount) {
			$this->pdo->rollBack();
			return false;
		}

		$dst_account_details = $this->getAccountDetails($destination);

		if ($dst_account_details == false) {
			$this->pdo->rollBack();
			return false;
		}

		if ($auth_device == "TANs") {
			if ($this->checkTanAndSetUsed($source, $tan) == false) {
				$this->pdo->rollBack();
				return false;
			}
		} else {
			$tan = $this->SCS_DEFAULT_TAN;
		}

		$SQL = "INSERT INTO $this->TRANSACTION_TABLE_NAME
					(
						$this->TRANSACTION_TABLE_FROM,
						$this->TRANSACTION_TABLE_TO,
						$this->TRANSACTION_TABLE_C_TS,
						$this->TRANSACTION_TABLE_AMOUNT,
						$this->TRANSACTION_TABLE_DESC,
						$this->TRANSACTION_TABLE_TAN,
						$this->TRANSACTION_TABLE_AP_AT,
						$this->TRANSACTION_TABLE_AP_BY,
						$this->TRANSACTION_TABLE_STATUS
					)
				VALUES
					(
						:source,
						:destination,
						now(),
						:amount,
						:description,
						:tan,
						$approved_at,
						$approved_by,
						$status
					)
				";
		
		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':source', $source, PDO::PARAM_INT);
		$stmt->bindValue(':destination', $destination, PDO::PARAM_INT);
		$stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
		$stmt->bindValue(':description', $description, PDO::PARAM_STR);
		$stmt->bindValue(':tan', $tan, PDO::PARAM_STR);
		$stmt->execute();

		$result = $this->pdo->lastInsertId();
		
		if ($result != 0) {
			$this->pdo->commit();
			return $result;
		} else {
			$this->pdo->rollBack();
			return false;
		}
	}

	function verifyTransaction($source, $destination, $amount, $description, $tan_code, $auth_device='TANs')
	{
		$var_res = array (
			"result"	=> false,
			"message"	=> "[Default] No test has been completed"
		);

		if ($source == $destination) {
			$var_res["message"]	= '[Account] Source and Destination can not be the same';
			return $var_res ; 
		}

		if ($amount <= 0) {
			$var_res["message"]	= '[Amount] Amount can not be negative or zero';
			return $var_res ; 
		}

		$sourceExists = $this->getAccountDetails($source);

		if ($sourceExists == false) {
			$var_res["message"]	= '[Account] Source account not found';
			return $var_res;
		}

		$destinationExists = $this->getAccountDetails($destination);

		if ($destinationExists == false) {
			$var_res["message"]	= '[Account] Destination account not found';
			return $var_res;
		}

		if ($amount > $sourceExists[$this->ACCOUNTOVERVIEW_TABLE_BALANCE]) {
			$var_res["message"]	= '[Funds] Insuffecient funds' ;
			return $var_res ; 
		}

		//In case the user uses a PIN, we cannot check the TAN on the db. TAN validity was already checked
		$tanValid = ($auth_device == 'TANs') ? $this->verifyTANCode($source, $tan_code) : true;

		if ($tanValid == false) {
			$var_res["message"]	= '[TAN] Invalid or used TAN';
			return $var_res ; 
		}
	
		$var_res["result"] = true;
		$var_res["message"]	= '[Success] Passed all tests' ;

		return $var_res ; 
	}

	function processPendingTransaction($transaction_id, $processor_id, $status)
	{
		$old_status = $this->mapTransactionStatus('unapproved');

		if ($this->getEmployeeStatus($processor_id) != $this->mapUserStatus('approved')) {
			return false;
		}

		$SQL = "UPDATE $this->TRANSACTION_TABLE_NAME
				SET
					$this->TRANSACTION_TABLE_AP_AT 	= now(),
					$this->TRANSACTION_TABLE_AP_BY 	= :processor_id,
					$this->TRANSACTION_TABLE_STATUS	= :status
				WHERE
					$this->TRANSACTION_TABLE_KEY	= :transaction_id
					AND $this->TRANSACTION_TABLE_STATUS = :old_status
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->bindValue(':processor_id', $processor_id, PDO::PARAM_INT);
		$stmt->bindValue(':status', $status, PDO::PARAM_INT);
		$stmt->bindValue(':transaction_id', $transaction_id, PDO::PARAM_INT);
		$stmt->bindValue(':old_status', $old_status, PDO::PARAM_INT);
		$result = $stmt->execute();

		if ($result == true && $stmt->rowCount() == 1) {
			return true;
		} else {
			return false;
		}
	}

	function approvePendingTransaction($processor_id, $transaction_id)
	{
		$status = $this->mapTransactionStatus('approved');
		return $this->processPendingTransaction($transaction_id, $processor_id, $status);
	}

	function rejectPendingTransaction($processor_id, $transaction_id)
	{
		$status = $this->mapTransactionStatus('rejected');
		return $this->processPendingTransaction($transaction_id, $processor_id, $status);
	}

	/************************************************
	 * /TRANSACTION FUNCTIONS
	 ************************************************/

	/************************************************
	 * OVERVIEW FUNCTIONS
	 ************************************************/

	function getNumberOfUsers()
	{
		$col = 'count';

		$unapproved = $this->mapUserStatus('unapproved');
		$rejected = $this->mapUserStatus('rejected');
		$client = $this->mapUserRole('client');

		$SQL = "SELECT
					count($this->USER_TABLE_KEY) as $col
				FROM
					$this->USER_TABLE_NAME
				WHERE
					$this->USER_TABLE_STATUS != $unapproved
					AND $this->USER_TABLE_STATUS != $rejected
					AND $this->USER_TABLE_ROLE = $client
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($result == false || sizeof($result) == 0) {
			return 0;
		} else {
			return $result[0][$col];
		}
	}

	function getNumberOfAccounts()
	{
		$col = 'count';

		$SQL = "SELECT
					count($this->ACCOUNT_TABLE_KEY) as $col
				FROM
					$this->ACCOUNT_TABLE_NAME
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($result == false || sizeof($result) == 0) {
			return 0;
		} else {
			return $result[0][$col];
		}
	}

	function getNumberOfTransactions()
	{
		$col = 'count';

		$approved = $this->mapTransactionStatus('approved');

		$SQL = "SELECT
					count($this->TRANSACTION_TABLE_KEY) as $col
				FROM
					$this->TRANSACTION_TABLE_NAME
				WHERE
					$this->TRANSACTION_TABLE_STATUS = $approved
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($result == false || sizeof($result) == 0) {
			return 0;
		} else {
			return $result[0][$col];
		}
	}

	function getTotalAmountOfMoney()
	{
		$col = 'sum';

		$SQL = "SELECT
					sum($this->ACCOUNTOVERVIEW_TABLE_BALANCE) as $col
				FROM
					$this->ACCOUNTOVERVIEW_TABLE_NAME
				WHERE
					$this->ACCOUNTOVERVIEW_TABLE_USER_ID != $this->FAKE_APPROVER_USER_ID
				";

		$stmt = $this->pdo->prepare($SQL);
		$stmt->execute();

		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($result == false || sizeof($result) == 0) {
			return 0;
		} else {
			return $result[0][$col];
		}
	}

	/************************************************
	 * /OVERVIEW FUNCTIONS
	 ************************************************/
}

?>