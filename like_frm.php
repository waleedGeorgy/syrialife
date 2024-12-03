<!DOCTYPE html>
<html dir="rtl">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/frame_css.css">
  <script type="text/javascript" src="bootstrap/js/prefixfree.min.js"></script>
<?php 
	 include ( "./inc/connect_inc.php" );
	 
	 	//Starting the session
	 session_start();
	 if (!isset($_SESSION["login_fname"])||(!isset($_SESSION["login_lname"]))||(!isset($_SESSION["login_snum"]))) {
		  $user_fname="";
		  $user_lname="";
		  $user_snum="";
	 }
	 else {
		  $user_fname = $_SESSION["login_fname"];
		  $user_lname = $_SESSION["login_lname"];
		  $user_snum = $_SESSION["login_snum"];
	 }

 		//Like frame based on the post ID
	if (isset($_GET['post_id'])){
		$post_id = mysql_real_escape_string(strip_tags($_GET['post_id']));
		if (ctype_alnum($post_id)){
			$total_likes=0;
			$get_all_likes = mysql_query("SELECT * FROM post_likes WHERE post_id='$post_id'") or die (mysql_error());
			if (mysql_num_rows($get_all_likes)===1){
				$get = mysql_fetch_assoc($get_all_likes);
				$total_likes = $get['total_likes'];
				$post_id = $get['post_id'];
			}
			else {
				die(mysql_error());
			}

					//Pressing like btn increases the likes
			if (isset($_POST['like_btn' . $post_id . ''])) {
				$like_add = $total_likes+1;
				$like = mysql_query("UPDATE post_likes SET total_likes='$like_add' WHERE post_id='$post_id'");
				$insert_user_like = mysql_query("INSERT INTO user_likes VALUES ('','$user_snum','$user_fname','$user_lname','$post_id')");
				header("Location: like_frm.php?post_id=$post_id");
			}
			else {
				//Nothing
			}

					//Pressing unlike btn decreases the likes
			if (isset($_POST['unlike_btn' . $post_id . ''])) {
				$like_remove = $total_likes-1;
				$unlike = mysql_query("UPDATE post_likes SET total_likes='$like_remove' WHERE post_id='$post_id'");
				$remove_user_like = mysql_query("DELETE FROM user_likes WHERE post_id='$post_id' AND user_snum='$user_snum'");
				header("Location: like_frm.php?post_id=$post_id");		
			}
			else {
				//Nothing
			}
		}
	}

				//Showing users who liked the post
		$like_user_fname = "";
		$like_user_lname = "";
		$get_user_likes = mysql_query("SELECT * FROM user_likes WHERE post_id='$post_id'") or die(mysql_error());
		while ($row = mysql_fetch_assoc($get_user_likes)){
			$like_id = $row['id'];
			$like_user = $row['user_snum'];
			$like_user_fname = $row['user_fname'];
			$like_user_lname = $row['user_lname'];
			$like_post_id = $row['post_id'];
			echo "<div class='user_like container-fluid'>
					<a href='$like_user' target='_parent'>".$like_user_fname." ".$like_user_lname."</a> ,
				  </div>";
		}

				//Check whether the post is liked
		$like_check = mysql_query("SELECT * FROM user_likes WHERE user_snum='$user_snum' AND post_id='$post_id'") or die (mysql_error());
		$like_check_rows = mysql_num_rows($like_check);
		if ($like_check_rows >= 1){
			echo"
			<div class='like_area'>
			<form action='like_frm.php?post_id=" . $post_id . "' method='post'>
		  		<input type='submit' name='unlike_btn" . $post_id . "' value='حذف الإعجاب' class='remove_like_btn'>
		  		<div class='like_num'>&nbsp&nbsp&nbsp الإعجابات : ".$total_likes."</div>
			</form></div>";
		}
		else if ($like_check_rows == 0){
			echo"
			<div class='like_area'>
			<form action='like_frm.php?post_id=" . $post_id . "' method='post'>
		  		<input type='submit' name='like_btn" . $post_id . "' value='إعجاب' class='like_btn'>
		  		<div class='like_num'>&nbsp&nbsp&nbsp الإعجابات : ".$total_likes ."</div>
			</form></div>";
		}
		else {
			die (mysql_error());
		}
?>