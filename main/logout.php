<?php
	include "connection/connection.php";
	session_start();

	if(isset($_SESSION["username"])){
		$gmt_last_access = date("Y-m-d H:i:s");
		// update last access and online status
		$u = DB::run("UPDATE user_accounts SET gmt_last_access = ?, isOnline = 0 WHERE uid = ?", [$gmt_last_access, $_SESSION["uid"]]);

		DB::insertLog($_SESSION["uid"], "Session Ended", "None", "LOG-OUT");

		session_destroy();
	}

	header("Location: login.php");
?>
