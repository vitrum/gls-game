<?php
require_once 'inc.php';
require_once 'config.php';

//Define ouput status code
define('STATUS_SYSTEM', -9);
define('STATUS_NOTLOGIN', -6);
define('STATUS_GOT', -2); // Already retrieved
define('STATUS_UNLUCKY', -1);
define('STATUS_OK', 1);

//Define coupon's type
define('TYPE_1', 1); //red heart
define('TYPE_2', 2);
define('TYPE_3', 3);
define('TYPE_4', 4);
define('TYPE_0', 0);

// //test
// $customer_id = '1235';
// $customer_name = 'JobsGa';
// $customer_email = 'ajofw@fef.com';

if(!$login) Game::jsonMsg(STATUS_NOTLOGIN);

$game = new Game;
$game->customer_id = $customer_id;
$game->customer_name = $customer_name;
$game->customer_email = $customer_email;
$game->run();

/**
 * Tiger Game 
 * @author Jack Wang<hi@phpecho.net>
 * @date 2013-01-15 22:42
 */
class Game{
	private $_db;
	/**
	 * Change the following value carefully
	 * They are possibility to get coupons
	 * Make sure it is equql 1.0
	 */
	private $_probabilities = array(
		TYPE_1=>0.002,
		TYPE_2=>0.008,
		TYPE_3=>0.01,
		TYPE_4=>0.28,
		TYPE_0=>0.7 // Not lucky, thank you
	);

	/*
		1500-300 RMB coupon       1 for each day 
		1000-150  RMB coupon      10 for each day
		800-100 RMB coupon        100 for each day
		600-50 RMB coupon         300 for each day
		Thank You                 rest
	*/
	private $_limitDaily = array(
		TYPE_1=>1,
		TYPE_2=>10,
		TYPE_3=>100,
		TYPE_4=>600,
	);

	public $customer_id;
	public $customer_name;
	public $customer_email;

	protected $todayStartTime;
	protected $todayEndTime;

	public function __construct(){
		$dsn = sprintf('mysql:dbname=%s;host=%s', DB_NAME, DB_HOST);
		$this->_db = new PDO($dsn, DB_USER, DB_PASSWORD);

		$this->todayStartTime = mktime(0, 0, 0);
		$this->todayEndTime = mktime(23, 59, 59);
	}

	public function run(){
		//Check the user if has got one today;
		$code = NULL;
		if(!$this->canGet(&$code)){
			// if($code)
				$this->jsonMsg(STATUS_GOT);
			// else
				// $this->jsonMsg(STATUS_UNLUCKY);
		}

		//Get random possiblity
		$type = $this->getRandType();
		if($type==TYPE_0 || !$this->canTake($type)){
			//The user is unlucky, and he can not play again today.
			$this->store($type);
			$this->jsonMsg(STATUS_UNLUCKY);
		}

		//Select and update
		$sql = 'SELECT id, code FROM coupon WHERE status=0 AND type='.$type.' FOR UPDATE';
		$this->_db->beginTransaction();
		$ret = $this->queryObj($sql);
		$code = $ret->code;
		//NOTICE: Codes may run out
		$this->store($type, $code);
		if($code){		
			// Update the coupon's status
			$this->_db->exec('UPDATE coupon SET status=1 WHERE id='.$ret->id);
		}
		$this->_db->commit();

		if(empty($code)){
			$this->jsonMsg(STATUS_UNLUCKY);
		}

		$data = array('type'=>$type, 'code'=>$code);
		$this->jsonMsg(STATUS_OK, $data);
	}

	/**
	 * Check left coupons amount if it's available
	 */
	public function canTake($type){
		$sql = 'SELECT COUNT(*) AS n FROM record WHERE type='.$type;
		$ret = $this->queryObj($sql);
		return $ret->n < $this->_limitDaily[$type];
	}

	public function canGet($code){
		$sql = 'SELECT code FROM record WHERE 
			customer_id="'.$this->customer_id.'" AND 
			created<='.$this->todayEndTime.' AND created>='.$this->todayStartTime;
		$ret = $this->queryObj($sql);
		if($ret){
			$code = $ret->code;
		}
		return !$ret; //Not exist
	}

	protected function store($type, $code=NULL){
		$sql = 'INSERT INTO record(customer_id, customer_email, customer_name, type, code, created)
				VALUES(:customer_id, :customer_email, :customer_name, :type, :code, :created)';
		$stmt = $this->_db->prepare($sql);
		return $stmt->execute(array(
			':customer_id'=>$this->customer_id,
			':customer_name'=>$this->customer_name,
				':customer_email'=>$this->customer_email,
				':type'=>$type,
				':code'=>$code,
				':created'=>time()
		));
	}

	protected function getRandType(){
		return $this->random($this->_probabilities);
	}

	protected function random($input, $pow = 2)
	{
		$much = pow(10, $pow);
		$max = array_sum($input) * $much;
		$rand = mt_rand(1, $max);
		$base = 0;
		foreach($input as $k => $v){
			$min = $base * $much + 1;
			$max = ($base + $v) * $much;
			if($min <= $rand && $rand <= $max){
				return $k;
			}else{
				$base += $v;
			}
		}
		return false;
	}

	protected function queryObj($sql){
		$stmt = $this->_db->query($sql);
		return $stmt->fetchObject();
	}

	public static function jsonMsg($status, $params = array())
	{
		$data = array('status'=>$status, 'params'=>$params);
		echo json_encode($data);
		exit();
	}
}


