<?php include ("inc/header_inc.php"); ?>
<?php 

		//User's URL
	if (isset($_GET['u'])){
		$snumber = mysql_real_escape_string(strip_tags($_GET['u']));
		if (ctype_alnum($snumber)){
			$check = mysql_query("SELECT first_name, last_name, serial_num FROM users WHERE serial_num= '$snumber' ");
			if (mysql_num_rows($check)==1) {
				$get = mysql_fetch_assoc($check);
				$first_name = $get["first_name"];
				$last_name = $get["last_name"];
				$serial_num = $get["serial_num"];
			}
			else { 
				echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/index.php\">";
				exit();
			}
		}
	}

		//Showing the profile picture on the profile page
	$profile_pic_check = mysql_query("SELECT profile_pic FROM users WHERE serial_num='$serial_num'");
	$get_profile_pic = mysql_fetch_assoc($profile_pic_check);
	$profile_pic_db = $get_profile_pic['profile_pic'];
	if ($profile_pic_db==""){
		$profile_pic = "img/def_pic.jpg";
	}
	else {
		$profile_pic = "users_data/profile_pics/".$profile_pic_db;
	}

		//Showing the cover photo on the profile page
	$cover_pic_check = mysql_query("SELECT cover_pic FROM users WHERE serial_num='$serial_num'");
	$get_cover_pic = mysql_fetch_assoc($cover_pic_check);
	$cover_pic_db = $get_cover_pic['cover_pic'];
	if ($cover_pic_db==""){
		$cover_pic = "img/def_cover.jpg";
	}
	else {
		$cover_pic = "users_data/cover_pics/".$cover_pic_db;
	}

		//Posts handling - Insert into DB
	$post = mysql_real_escape_string(strip_tags(@$_POST["post"]));
		 if ($post != "") {
		 	$date_added =  date("Y-m-d");
		 	$added_by = $user_snum;
		 	$added_by_fname = $user_fname;
		 	$added_by_lname = $user_lname;
		 	$posted_to = $snumber;
		 	$delete_status = "No";
		 	$total_likes = 0;
			$post_command = "INSERT INTO posts VALUES ('','$post','$date_added','$added_by','$added_by_fname','$added_by_lname','$posted_to','$delete_status')";
			$query = mysql_query($post_command) or die (mysql_error());
			$get_post_id = mysql_query("SELECT id FROM posts ORDER BY id DESC LIMIT 1") or die(mysql_error());
			$post_id = mysql_fetch_assoc($get_post_id);
			$id = $post_id['id'];
			$post_likes = mysql_query("INSERT INTO post_likes VALUES ('','$total_likes','$id')");
		}
		else {
			//Nothing
		}
 ?>

 <?php 

 		//Handling Friend requests
 	$bad_messege = "";
 	$friend_msg = "";
 	if (isset($_POST['add_friend'])){
 		$friend_req = $_POST['add_friend'];
 		$from_user = $user_snum;
 		$from_user_fname = $user_fname;
 		$from_user_lname = $user_lname;
 		$to_user = $serial_num;
 		$to_user_fname = $first_name;
 		$to_user_lname = $last_name;
 		if ($from_user==$to_user){
 			$bad_messege = "لا يمكنك إرسال طلب صداقة إلى نفسك";
 		}
 		else {
 			$friend_request = mysql_query("INSERT INTO friend_reqs VALUES ('','$from_user','$from_user_fname','$from_user_lname','$to_user','$to_user_fname','$to_user_lname')");
 			$friend_msg = "تم إرسال طلب الصداقة !";
 		}
 	}
 	else {
 		//Nothing
 	}
  ?>
  <?php

  		//Removing friends
  		//Friends for the user's profile
  	if(isset($_POST['remove_friend'])){
  		$remove_friend_check = mysql_query("SELECT friends FROM users WHERE serial_num='$serial_num'");
  		$remove_friend_row = mysql_fetch_assoc($remove_friend_check);
  		$remove_db_friends = $remove_friend_row['friends'];

  			//friends for the user logged in
  		$remove_friend_check_logged = mysql_query("SELECT friends FROM users WHERE serial_num='$user_snum'");
  		$remove_friend_row_logged = mysql_fetch_assoc($remove_friend_check_logged);
  		$remove_db_friends_logged = $remove_friend_row_logged['friends'];

  			//Vars for the friends , they either have a comma at the start or at the end or dont have the comma
  		$user_profile_comma_start = ",".$serial_num;
  		$user_profile_comma_end = $serial_num.",";
  		$user_logged_comma_start = ",".$user_snum;
  		$user_logged_comma_end = $user_snum.",";

  			//Replacing the friends with commas and no commas with nothing for the user's profile
  		if (strstr($remove_db_friends_logged, $user_profile_comma_start)){
  			$remove_friend_user = str_replace("$user_profile_comma_start","",$remove_db_friends_logged);	
  		}
  		elseif  (strstr($remove_db_friends_logged, $user_profile_comma_end)){
  			$remove_friend_user = str_replace("$user_profile_comma_end","",$remove_db_friends_logged);	
  		}
  		elseif  (strstr($remove_db_friends_logged, $serial_num)){
  			$remove_friend_user = str_replace("$serial_num","",$remove_db_friends_logged);	
  		}

  			//Replacing the friends with commas and no commas with nothing for the user logged in
  		if (strstr($remove_db_friends, $user_logged_comma_start)){
  			$remove_friend_logged = str_replace("$user_logged_comma_start","",$remove_db_friends);	
  		}
  		elseif  (strstr($remove_db_friends, $user_logged_comma_end)){
  			$remove_friend_logged = str_replace("$user_logged_comma_end","",$remove_db_friends);	
  		}
  		elseif  (strstr($remove_db_friends, $user_snum)){
  			$remove_friend_logged = str_replace("$user_snum","",$remove_db_friends);	
  		}

  		@$remove_friend_query_user = mysql_query("UPDATE users SET friends='$remove_friend_user' WHERE serial_num='$user_snum'");
  		@$remove_friend_query_logged = mysql_query("UPDATE users SET friends='$remove_friend_logged' WHERE serial_num='$serial_num'");
  	}
  	else {
  		//Do nothing
  	}

  ?>
  <?php 
  
		//Reminders actions
  		$remind_ok = "";
  		$reminder_msg = "";
    	if (isset($_POST['remember_me'])){
    		$check_reminder = mysql_query("SELECT * FROM reminders WHERE from_user='$user_snum' && to_user='$serial_num'");
    		$reminder_exist = mysql_num_rows($check_reminder);
    		if ($reminder_exist == 1){
    			$reminder_msg = "عليك أن تنتظر حتى يتم الرد على سلامك";
    		}
    		else {
		   	$remind_user = mysql_query("INSERT INTO reminders VALUES ('','$user_snum','$user_fname','$user_lname','$serial_num','$first_name','$last_name')");
		   	$remind_ok = "تم السلام على ".$first_name." ".$last_name." بنجاح";
		   }
		}
		else {
			//Nothing
		}
   ?>
<div class='row cover_img_div container-fluid'>
    <img src='<?php echo $cover_pic; ?>' alt='غلاف <?php echo $first_name; ?>' title='غلاف <?php echo $first_name; ?>' class="cover_img col-lg-12 col-md-12 col-sm-12 col-xs-12" height="400" />
</div>
<div class="container profile_page">
	<div class="row main_info">
	 <div class="col-lg-3 col-md-4 col-sm-5 col-xs-9">
	  <img src="<?php echo $profile_pic; ?>" width="270" height="310" class="profileImg" alt="<?php echo $first_name.' '.$last_name; ?>" title="<?php echo $first_name.' '.$last_name; ?>" />
	  <h3 class="profile_owner"><?php echo $first_name." ".$last_name; ?></h3>
	 </div>
	  <div class="personal_info_right col-lg-9 col-md-8 col-sm-7 col-xs-12">
	  <div class="profile_buttons row">
		  <form action="<?php echo $serial_num; ?>" class='container-fluid' method="post">
		    <?php echo '<p class="friend_msg">'.$friend_msg.'</p>'; ?>
		    <?php echo '<p class="bad_messege">'.$bad_messege.'</p>'; ?>
		    <?php echo '<p class="friend_msg">'.$remind_ok.'</p>'; ?>
		    <?php echo '<p class="bad_messege">'.$reminder_msg.'</p>'; ?>
		    <?php

		    			//Show add friend if users aren't friends , or show remove friends if they are friends
		    	$friends_array = "";
				$count_friends = "";
				$friends_array_slice = "";
				$add_as_friend = "";
				$poking = "";
				$select_friends_query = mysql_query("SELECT friends FROM users WHERE serial_num='$serial_num'");
				$friends_row = mysql_fetch_assoc($select_friends_query);
				$friends_array = $friends_row['friends'];
				if ($friends_array != "") {
				   $friends_array = explode(",",$friends_array);
				   $count_friends = count($friends_array);
				   $friends_array_slice = array_slice($friends_array, 0, 100);

				   if ($user_snum != $serial_num){
					    if (in_array($user_snum,$friends_array)) {
							$add_as_friend = '<input type="submit" class="remove_frnd_btn col-lg-4 col-md-4 col-sm-4 col-xs-4" value="" name="remove_friend" />';
							$send_messege = '<input type="submit" class="send_message_btn col-lg-4 col-md-4 col-sm-4 col-xs-4" name="send_msgs" value="" />';
							$poking = '<input type="submit" class="poke_btn col-lg-4 col-md-4 col-sm-4 col-xs-4" name="remember_me" value="" />';
						}
						else
						{
							$add_as_friend = '<input type="submit"  class="col-lg-6 col-md-6 col-sm-6 col-xs-6 add_frnd_btn" value="" name="add_friend" />';
							$send_messege = '<input type="submit" class="col-lg-6 col-md-6 col-sm-6 col-xs-6 send_message_btn" name="send_msgs" value="" />';
						}
						echo $add_as_friend;
						echo $send_messege;
						echo $poking;
					}
				}
		    ?>

		    <?php
		    		//Sending messeges
		    	if (isset($_POST['send_msgs'])){
		    		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/send_msgs.php?u=$serial_num\">";
		    	}
		    	else {
		    		//Do nothing
		    	}
		    ?>

	      </form>
	  </div>
	   <div class='personal_frnds'>
		   <div class="textHeader">أصدقاء <?php echo $first_name.' '.$last_name; ?></div>
		    <div class="profileFriendsImg">
			  <?php

			  		//Showing friends on the profile page
			   if($count_friends != 0){
			   	foreach ($friends_array_slice as $key => $value) {
			   		$get_friend_query = mysql_query("SELECT * FROM users WHERE serial_num='$value' LIMIT 1") or die (mysql_error());
			   		$get_friend_row = mysql_fetch_assoc($get_friend_query);
			   		$friend_snum = $get_friend_row['serial_num'];
			   		$friend_fname = $get_friend_row['first_name'];
			   		$friend_lname = $get_friend_row['last_name'];
			   		$friend_pic = $get_friend_row['profile_pic'];
			   		if ($friend_pic == ""){
			   			echo "<a href='$friend_snum'><img src='img/def_pic.jpg' alt='$friend_fname $friend_lname' class='friend_img' title='$friend_fname $friend_lname' height='110' width='100' /></a>";
			   		}
			   		else {
			   			echo "<a href='$friend_snum'><img src='users_data/profile_pics/$friend_pic' alt='$friend_fname $friend_lname' class='friend_img' title='$friend_fname $friend_lname' height='110' width='100' /></a>";
			   		}
			   	}
			   }
			   else {
			   	echo $first_name." ".$last_name." لا يملك أصدقاء حاليا";
			   }
			  ?>
			</div>
		  </div>
		  <div class='personal_info'>	 
			  <div class="textHeader">معلومات حول <?php echo $first_name.' '.$last_name; ?></div>
			  <div class="profileContentInfo">
			  	<?php

			  			//Get bio from DB and show on homepage
			  		$bio_query = mysql_query("SELECT bio FROM users WHERE serial_num='$serial_num'") or die (mysql_error());
			  		$get_results = mysql_fetch_assoc($bio_query);
			  		$user_bio = $get_results['bio'];
			  		if ($user_bio==""){
			  			echo "لا يوجد أي معلومات";
			  		}
			  		else {
			  			echo $user_bio;
			  		}
			  	?>
			  </div>
		  </div>
	  </div>
	 </div>
      <div class="postForm row">
        <form action="<?php echo $snumber; ?>" method="post">
	      	<input type="submit" name="send_post" value="نشر" id="post_button" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 btn btn-danger" />
	      	<textarea id="post_area" name="post" placeholder="ماذا ستقول ؟!..." class="col-lg-9 col-lg-offset-1 col-md-9 col-md-offset-1 col-sm-9 col-sm-offset-1 col-xs-9 col-xs-offset-1"></textarea>
        </form>
      </div>
	  <div class="postProfile row">
	  	<?php 

	  			//Handling posts - Showing posts on user's profile page
	  		$show_posts = mysql_query("SELECT * FROM posts WHERE user_posted_to='$serial_num' && delete_status='No' ORDER BY id DESC LIMIT 7") or die (mysql_error());
	  		while ($row = mysql_fetch_assoc($show_posts)){
	  			$post_id = $row["id"];
	  			$body = $row["body"];
	  			$date_added = $row["date_added"];
	  			$added_by = $row["added_by"];
	  			$added_by_fname = $row["added_by_fname"];
	  			$added_by_lname = $row["added_by_lname"];
	  			$user_posted_to = $row["user_posted_to"];
	  			$posts_pic_q = mysql_query("SELECT * FROM users WHERE serial_num='$added_by'") or die (mysql_error());
	  			$posts_pic = mysql_fetch_assoc($posts_pic_q);
	  			$posted_prof_pic = $posts_pic['profile_pic'];
	  			?>

	  			<script language="javascript">
				function toggle_comment<?php echo $post_id; ?>() {
			           var link = document.getElementById("show_comment<?php echo $post_id; ?>");
			           if (link.style.display == "block") {
			              link.style.display = "none";
			           }
			           else
			           { link.style.display = "block"; }
			    }

			    function toggle_like<?php echo $post_id; ?>() {
			           var link = document.getElementById("show_like<?php echo $post_id; ?>");
			           if (link.style.display == "block") {
			              link.style.display = "none";
			           }
			           else
			           { link.style.display = "block"; }
		        }
			</script>

	  			<?php
	  			echo "<div class='profile_post'>";
	  			if ($user_snum == $serial_num){
	  				echo "<form action='$serial_num' method='post' id='delete_form'>
	  					 	<input type='submit' name='delete_post_$post_id' class='delete_btn' title='حذف المنشور' value='X' />
	  					  </form>";
	  			}
	  			echo "<div class='posted_by'>
	  					 $date_added&nbsp&nbsp|&nbsp&nbsp<a href='$added_by'>$added_by_fname $added_by_lname</a><br>";
	  					 if ($posted_prof_pic==""){
	  					 	echo "<a href='$added_by'><img src='img/def_pic.jpg' alt='$added_by_fname $added_by_lname' title='$added_by_fname $added_by_lname' class='profile_post_pic' height='100' width='80' /></a>";
	  					 }
	  					 else {
	  					 	echo "<a href='$added_by'><img src='users_data/profile_pics/$posted_prof_pic' alt='$added_by_fname $added_by_lname' title='$added_by_fname $added_by_lname' class='profile_post_pic' height='100' width='80' /></a>";
	  					 }
	  				  echo"</div><br><br><br><br>
	  				  <h4 id='the_post' class='post_content'>$body</h4>
	  				  <br><br><div class='row'>
				   		<div class='container-fluid'>
				    		<div class='posts_options'>
							     <a href='javascript:;' onClick='javascript:toggle_comment$post_id();'>&nbspالتعليقات</a>&nbsp&nbsp
							     <a href='javascript:;' onClick='javascript:toggle_like$post_id();'>|&nbsp&nbspالإعجابات</a>
						    </div>
				   		</div>
				 	  </div>
				  	<div id='show_like$post_id' class='frame_like'>
					 	<iframe src='./like_frm.php?post_id=$post_id' frameborder='0' class='like_frame'></iframe>
				  	</div>
				  	<div id='show_comment$post_id' class='comments'>
					 	<iframe src='./comment_frm.php?post_id=$post_id&added_by=$added_by&added_by_fname=$added_by_fname&added_by_lname=$added_by_lname' frameborder='0' class='comment_frame'></iframe>
				  	</div></div>";

				  		//Deleting posts
	  			if (isset($_POST['delete_post_' . $post_id .''])){
		  			$delete_post_q = mysql_query("UPDATE posts SET delete_status='Yes' WHERE user_posted_to='$user_posted_to' && id='$post_id'");
		  			echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/profile.php?u=$serial_num\">";
	  			}
	  			else {
	  				//Do nothing
	  			}
	  		}
	  	?>
	  </div>
</div>