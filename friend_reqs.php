<?php include("./inc/header_inc.php"); ?>
<?php 
	if ($user_snum){
			//Continue normally
	}
	else {
		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/index.php\">";
		exit();
	}
 ?>
 <div class="container friend_reqs"><br>
 	<h1>طلبات الصداقة لديك</h1><hr><br>
 	 <div class="container-fluid">
	<?php 

			//Telling the users they have friend requests
		$friend_req = mysql_query("SELECT * FROM friend_reqs WHERE to_user='$user_snum'") or die (mysql_error());
		$num_rows = mysql_num_rows($friend_req);
		if ($num_rows == 0){
			echo "<h3 style='text-align:center;'>لا يوجد لديك أي طلبات صداقة</h3><br><h4 style='text-align:center;position:relative;bottom:25px'>ابحث عن أصدقائك الآن !</h4>";
		}
		else {
			while ($get_row = mysql_fetch_assoc($friend_req)){
				$id = $get_row['id'];
				$from_user = $get_row['from_user'];
				$from_user_fname = $get_row['from_user_fname'];
				$from_user_lname = $get_row['from_user_lname'];
				$to_user = $get_row['to_user'];
				$to_user_fname = $get_row['to_user_fname'];
				$to_user_lname = $get_row['to_user_lname'];
				$get_pic_q =mysql_query("SELECT * FROM users WHERE serial_num='$from_user'") or die (mysql_error());
				$get_pic = mysql_fetch_assoc($get_pic_q);
				$friend_pic = $get_pic['profile_pic'];
				echo "<div class='col-lg-6 col-lg-offset-1 col-md-6 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-7 col-xs-offset-1'>";
				if ($friend_pic==""){
					echo" <a href='$from_user'><img src='img/def_pic.jpg' class='friend_req_pic' height='190' width='170' /></a>
				    </div><br><br><br><br><br><br><br>";
				}
				else {
				    echo" <a href='$from_user'><img src='users_data/profile_pics/$friend_pic' class='friend_req_pic' height='190' width='170' /></a>
				    </div><br><br><br><br><br><br><br>";
				}
				echo '<h3 style="text-align:center">المستخدم <a href="'.$from_user.'">'. $from_user_fname . ' ' . $from_user_lname . '</a> أرسل طلب صداقة</h3>';
	 ?>

	 <?php 

	 			//Accepting friend requests
	 	if(isset($_POST['accept_req'.$from_user])){
		 		//We need to concat the 2 users friends so that they are always added to each other and not replaced
		 		//Logged in user friends
	 		$friends_logged_in = mysql_query("SELECT friends FROM users WHERE serial_num='$user_snum'") or die (mysql_error());
	 		$friends_logged_in_names = mysql_fetch_assoc($friends_logged_in);
	 		$friends_array_logged_in = $friends_logged_in_names['friends'];
	 		$friends_logged_in_explode = explode(",", $friends_array_logged_in);
	 		$friends_logged_in_count = count($friends_logged_in_explode);

	 			//User who sent the friend request friends
	 		$friends_sent_to = mysql_query("SELECT friends FROM users WHERE serial_num='$from_user'") or die (mysql_error());
	 		$friends_sent_to_names = mysql_fetch_assoc($friends_sent_to);
	 		$friends_array_sent_to = $friends_sent_to_names['friends'];
	 		$friends_sent_to_explode = explode(",", $friends_array_sent_to);
	 		$friends_sent_to_count = count($friends_sent_to_explode);

	 			//Making the initial no. of friends 0
	 		if ($friends_array_logged_in == ""){
	 			$friends_logged_in_count = count(NULL);
	 		}
			if ($friends_array_sent_to == ""){
	 			$friends_sent_to_count = count(NULL);
	 		}

	 			//Adding friends arrays to users
	 		if ($friends_logged_in_count == NULL) {
			   $add_friend_query = mysql_query("UPDATE users SET friends=CONCAT(friends,'$from_user') WHERE serial_num='$user_snum'");
			  }
			if ($friends_sent_to_count == NULL) {
			   $add_friend_query = mysql_query("UPDATE users SET friends=CONCAT(friends,'$to_user') WHERE serial_num='$from_user'");
			  }
			if ($friends_logged_in_count >= 1) {
			   $add_friend_query = mysql_query("UPDATE users SET friends=CONCAT(friends,',$from_user') WHERE serial_num='$user_snum'");
			  }
			if ($friends_sent_to_count >= 1) {
			   $add_friend_query = mysql_query("UPDATE users SET friends=CONCAT(friends,',$to_user') WHERE serial_num='$from_user'");
			  }
			  
			  	//Remove request
			$remove_request = mysql_query("DELETE FROM friend_reqs WHERE to_user='$to_user'&& from_user='$from_user'");
	 		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/friend_reqs.php\">";
	 	}
	 	if(isset($_POST['ignore_req'.$from_user])){
	 		$ignore_request = mysql_query("DELETE FROM friend_reqs WHERE to_user='$to_user'&& from_user='$from_user'");
	 		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/friend_reqs.php\">";
	 	}
	 	else {
	 		//Nothing
	 	}
	 ?>
	<br>
	<form action="friend_reqs.php" method="POST" class="friend_btns">
		<input type="submit" class="btn btn-danger accept_req" name="accept_req<?php echo $from_user; ?>" value="قبول">
		<input type="submit" class="btn btn-danger ignore_req" name="ignore_req<?php echo $from_user; ?>" value="رفض">
	</form><hr><br>
	<?php 
		}
		}
	?>
 </div>
</div>