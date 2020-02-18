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
	}
?>
