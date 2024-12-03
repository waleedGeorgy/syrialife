<!DOCTYPE html>
<html dir="rtl">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/frame_css.css">
  <script type="text/javascript" src="bootstrap/js/prefixfree.min.js"></script>
 <?php 
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
 ?>

	<?php 
	
			/* URL of this page is the post id , so that each post gets it's comments ,plus the user who added the post , to point out
			who's post someone has commented on */
		include ( "./inc/connect_inc.php" );
		$post_id = mysql_real_escape_string($_GET['post_id']);
		$added_by = mysql_real_escape_string($_GET['added_by']);
		$added_by_fname = mysql_real_escape_string($_GET['added_by_fname']);
		$added_by_lname = mysql_real_escape_string($_GET['added_by_lname']);
	?>
	
	<script language="javascript">

			//Script for opening divs on the same page
		function toggle_comment() {
	           var link = document.getElementById("show_comment");
	           var text = document.getElementById("comment_link");
	           if (link.style.display == "block") {
	              link.style.display = "none";
	           }
	           else
	           {
	             link.style.display = "block";
	           }
	    }

	    	//Script for taking enter btn as the input btn
     	function process(e) {
		    var code = (e.keyCode ? e.keyCode : e.which);
		    if (code == 13) {
		        document.forms[0].submit();
		    }
		}

	</script>

<?php 

		//Posting comments on a post
	if (isset($_POST['comment_area' . $post_id . ''])){
		$content = $_POST['comment_area' . $post_id . ''];
		$delete_status = 'No';
		$insert_comment = mysql_query("INSERT INTO comments VALUES ('','$content','$user_snum','$user_fname','$user_lname','$added_by','$added_by_fname','$added_by_lname','$post_id','$delete_status')");
	}
	else {
		//Do nothing
	}
 ?>

	<div class='enter_comment'>
		<a href='javascript:;' style="position:relative;left:5px;top:6px" onClick='javascript:toggle_comment()'><img src='img/comment.png' class='comment_icon' />تعليق</a>
	</div>
	<div id='show_comment' class='comments'>
	  <form action="comment_frm.php?post_id=<?php echo $post_id; ?>&added_by=<?php echo $added_by; ?>&added_by_fname=<?php echo $added_by_fname; ?>&added_by_lname=<?php echo $added_by_lname; ?>" method="post">
	    <textarea rows="1" placeholder='تعليقك...' onkeypress="process(event, this)" class="comment_area" name='comment_area<?php echo $post_id; ?>'></textarea>
	  </form>
	</div>
<div class='container-fluid'>
	<?php

			//Showing comments specific for each post
		$get_comment_q = mysql_query("SELECT * FROM comments WHERE post_id='$post_id' && delete_status='No' ORDER BY id DESC") or die (mysql_error());
		$comment_count = mysql_num_rows($get_comment_q);
		$comment_body = "";
		if ($comment_count != 0){
			while ($comments = mysql_fetch_assoc($get_comment_q)) {
				$comment_id = $comments['id'];
				$comment_body = $comments['content'];
				$posted_by = $comments['posted_by'];
				$posted_by_fname = $comments['posted_by_fname'];
				$posted_by_lname = $comments['posted_by_lname'];
				$get_post_pic = mysql_query("SELECT * FROM users WHERE serial_num='$posted_by'");
				$comment_pic_row = mysql_fetch_assoc($get_post_pic);
				$comment_pic = $comment_pic_row['profile_pic'];
				if ($comment_pic==""){
					echo "<a href='$posted_by'><img src='img/def_pic.jpg' class='comment_pic' title='$posted_by_fname $posted_by_lname' alt='$posted_by_fname $posted_by_lname' height='70' width='55' /></a>";
				}
				else {
					echo "<a href='$posted_by'><img src='users_data/profile_pics/$comment_pic' class='comment_pic' title='$posted_by_fname $posted_by_lname' alt='$posted_by_fname $posted_by_lname' height='70' width='55' /></a>";
				}
				echo "<form action='comment_frm.php?post_id=$post_id&added_by=$added_by&added_by_fname=$added_by_fname&added_by_lname=$added_by_lname' method='post' id='delete_comment_form'>
	  					 	<input type='submit' name='delete_comment_$comment_id' class='delete_comment_btn' title='حذف التعليق' value='X' />
	  				  </form>";
	  				  
	  				  	//Deleting comments
	  			if (isset($_POST['delete_comment_' . $comment_id .''])){ 
		  			$delete_comment_q = mysql_query("UPDATE comments SET delete_status='Yes' WHERE id='$comment_id'");
		  			echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/comment_frm.php?post_id=$post_id&added_by=$added_by&added_by_fname=$added_by_fname&added_by_lname=$added_by_lname\">";
				}
				else {
					//Nothing
				}
				echo "<a href='$posted_by' target='_parent'><p class='user_comment'><b>".$posted_by_fname." ".$posted_by_lname."</b></p></a><p class='post_comment'>".$comment_body."</p><hr>";
			}
		}
		else {
			echo "<p class='no_comment'>لا يوجد تعليقات حاليا</p>";
		}
	?>
</div>