<?php 
		//Log out Script
	session_start();
	session_destroy();
	header("location: index.php");
 ?>