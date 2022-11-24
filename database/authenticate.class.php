<?php
/** 
 * Pages that authenticates 
 * @version 1.0
 * @since 21 Listopad 2022
 */
require_once('connection.class.php');

// Class derives from db connections
class Authenticate extends Connection
{
	private $data = array();
	// constructor
	public function __construct()
	{
		$this->erro = '';
	}
	// Set values for object
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}
	// return requested object and its contents
	public function __get($name)
	{
		if (array_key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		// Exception handling
		$trace = debug_backtrace();
		trigger_error(
			'Undefined property via __get(): ' . $name .
			' in ' . $trace[0]['file'] .
			' on line ' . $trace[0]['line'],
			E_USER_NOTICE
		);
		return null;
	}

	public function IsEmpty(array $array_de_dados)
	{
		/**
		 * Function tests whether a given array is empty or contains some empty string, used to help verify form inputs
		 * returns true if some element in array is empty/null
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $array_de_dados Array containing all required data
		 */
		return in_array("", $array_de_dados);
	}

	public function isValidNick(string $nick)
	{
		/**
		 * function tests whether a given string contains any invalid character, used to help verify usernames 
		 * returns true if it's a valid username
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $nick Nickname to be validated
		 */
		// 
		return !preg_match('/[^A-Za-z0-9]/', $nick) && strlen($nick) > 1 && strlen($nick) < 21;
	}

	public function isSwimmerTrue(string $is_swimmer)
	{
		/**
		 * function that returns true if is_swimmer is 1(checked)
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $is_swimmer Checkbox correspondent parameter that should be 1 if checkbox is marked
		 */
		return $is_swimmer == '1';
	}

	public function isValidDate(string $date)
	{
		/**
		 * function tests wheter a given date is valid, 
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $date string containing the date, should be in format YYYY-MM-DD
		 */
		// set array with day, month and year by exploding string into array 
		$birth_array = explode('-', $date);
		// tests if array is set, countable and has only 3 elements
		if (isset($birth_array) && count($birth_array) === 3) {
			// set variables
			$day = $birth_array[2];
			$month = $birth_array[1];
			$year = $birth_array[0];
			// check for valid date
			if (checkdate($month, $day, $year)) {
				// valid
				return TRUE;
			} else {
				// invalid date
				return FALSE;
			}
		} else {
			// array not set or invalid
			return FALSE;
		}
	}

	public function isDuplicateOrInvalidNick(string $nick)
	{
		/**
		 * function check is given nickname is duplicate on invalid 
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $nick Nickname to be validated
		 */
		if (!$this->isValidNick($nick)) {
			return true;
		}
		$this->type = 'CheckNick';
		$this->param = array($nick);
		$result = $this->Fetch();

		if (isset($result) && !empty($result)) {
			return true;
		}
		return false;
	}

	public function Insert()
	{
		/**
		 * function Insert data in database based on Type parameter
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $this->type Parameter must contain one of the already defined type on switch: CreateUser
		 * @param $this->param Array containing all data required for INSERT
		 */
		try {
			// Creates new connection
			$db_handle = new Connection();
			// Set PDO to exception mode
			$db_handle->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// get insert type
			switch ($this->type) {
				case 'CreateUser':
					$insert = "INSERT INTO users VALUES(?, ?, ?, ?);";
					break;

				default:
					throw new Exception("Not a Valid Request");
			}
			// Prepare the query
			$stmt = $db_handle->pdo->prepare($insert);
			// execute and bind parameters
			$stmt->execute($this->param);
			// Disconnect
			$stmt = null;
			return TRUE;
		} catch (PDOException $e) {
			// on any error, return false
			return FALSE;
		}
	}

	public function Fetch()
	{
		/**
		 * function Fetchs data from database based on Type parameter
		 * @version 1.0
		 * @since 21 Listopad 2022
		 * @param $this->type Parameter must contain one of the already defined type on switch: GetUsers, CheckNick, GetWebhook
		 * @param $this->param Array containing all data required for SELECT
		 */
		try {
			// Creates new connection
			$db_handle = new Connection();
			// Set PDO to exception mode
			$db_handle->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// get update type
			switch ($this->type) {
				case 'GetUsers':
					$query = "SELECT * FROM users";
					break;

				case 'CheckNick':
					$query = "SELECT * FROM users WHERE name = ? LIMIT 1";
					break;
				
				case 'GetWebhook':
					$query = "SELECT * FROM webhook WHERE WebhoodId = ? LIMIT 1";
					break;

				default:
					throw new Exception("Not a Valid Request");
			}
			// Prepare the query
			$stmt = $db_handle->pdo->prepare($query);
			// execute and bind parameters
			$stmt->execute($this->param);
			// fetch results
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// Disconnect
			$stmt = null;
			// validate db fetch returned data
			if (!empty($result) && count($result)) {
				// success
				return $result;
			} else {
				// failure
				return FALSE;
			}
		} catch (PDOException $e) {
			// on any error, return false
			return FALSE;
		}
	}
}
?>