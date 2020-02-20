<?php
	date_default_timezone_set('Asia/Manila');

	define('DB_HOST', 'localhost');
	define('DB_NAME', 'supply_db');
	define('DB_USER', 'root');
	define('DB_PASS', '');
	define('DB_CHAR', 'utf8');
	
	class DB
	{
	    protected static $instance = null;

	    protected function __construct() {}
	    protected function __clone() {}

	    public static function instance()
	    {
	        if (self::$instance === null)
	        {
	            $opt  = array(
	                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	                PDO::ATTR_EMULATE_PREPARES   => FALSE,
	            );
	            $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHAR;
	            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
	        }
	        return self::$instance;
	    }

	    public static function __callStatic($method, $args)
	    {
	        return call_user_func_array(array(self::instance(), $method), $args);
	    }

	    public static function run($sql, $args = [])
	    {
	        if (!$args)
	        {
	             return self::instance()->query($sql);
	        }
	        $stmt = self::instance()->prepare($sql);
	        $stmt->execute($args);
	        return $stmt;
	    }

	    public static function getLastInsertedID(){
	    	return self::instance()->lastInsertId();
		}
		
		public static function insertLog($uid, $act_name, $act_descrip, $event_type){
			$i = self::run("INSERT INTO user_activities(uid, act_name, act_descrip, event_type, gmt_datetime) VALUES(?, ?, ?, ?, ?)", [$uid, $act_name, $act_descrip, $event_type, date("Y-m-d H:i:s")]);
			if($i->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}
		public static function time_elapsed_string($datetime, $full = false) {
			$now = new DateTime;
			$ago = new DateTime($datetime);
			$diff = $now->diff($ago);
	  
			$diff->w = floor($diff->d / 7);
			$diff->d -= $diff->w * 7;
	  
			$string = array(
				'y' => 'year',
				'm' => 'month',
				'w' => 'week',
				'd' => 'day',
				'h' => 'hour',
				'i' => 'minute',
				's' => 'second',
			);
			foreach ($string as $k => &$v) {
				if ($diff->$k) {
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
				} else {
					unset($string[$k]);
				}
			}
	  
			if (!$full) $string = array_slice($string, 0, 1);
			return $string ? implode(', ', $string) . ' ago' : 'Just Now';
		}
		public static function getCurrentDateTime(){
			return date("Y-m-d H:i:s");
		}
		public static function formatDateTime($datetime, $isTimeIncluded = false){
			if(!$isTimeIncluded){
				return date("Y-m-d", strtotime($datetime));
			}else{
				return date("Y-m-d H:i:s", strtotime($datetime));
			}
		}
	}
?>
