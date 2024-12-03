<?php

		//Log in script
	if (isset($_POST["login_fname"]) && isset($_POST["login_lname"]) && isset($_POST["login_snum"]) && isset($_POST["login_pwd"])){
		$login_fname = strip_tags(preg_replace('/[^\x{0600}-\x{06FF}A-Za-z]/u','', $_POST["login_fname"]));
		$login_lname = strip_tags(preg_replace('/[^\x{0600}-\x{06FF}A-Za-z]/u','', $_POST["login_lname"]));
		$login_snum = strip_tags(preg_replace('/[^0-9]/u','', $_POST["login_snum"]));
		$login_pwd = strip_tags(preg_replace('/[^\x{0600}-\x{06FF}A-Za-z0-9 !@#$%^&*()]/u','', $_POST["login_pwd"]));
		$login_pwd_md5 = md5($login_pwd);
		$sql = mysqli_query("SELECT id FROM users WHERE first_name= '$login_fname' AND last_name= '$login_lname' AND serial_num= '$login_snum' AND password= '$login_pwd_md5' AND closed='no' LIMIT 1");
		$user_count = mysqli_num_rows($sql);
		if ($user_count == 1) {
			while($row = mysqli_fetch_array($sql)) {
				$id = $row["id"];
			}
			$_SESSION["login_fname"] = $login_fname;
			$_SESSION["login_lname"] = $login_lname;
			$_SESSION["login_snum"] = $login_snum;
			header("location: home.php");
			exit();
		}
		else {
			echo "<div class='sign_error'><p>تم إدخال معلومات خاطئة , الرجاء إعادة المحاولة .</p></div>" ;
		}
	}

 ?>
