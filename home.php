<?php 
include ("./inc/header_inc.php");
?>
   <div class="container-fluid home_posts">
    <div class="row">
     <div class='col-lg-3 col-md-3 col-sm-3 col-xs-3'>
     	<div class='side_options'>
     		<ul class="side_links">
     			<li><a href='albums.php?u=<?php echo $user_snum; ?>'>الصور</a></li>
     			<li><a href='#'>الأصدقاء</a></li>
     			<li><a href='../pro/index.php'>خدمات</a></li>
     			<li><a href='mobile/index.php'>إعلانات</a></li>
     		</ul>
     	</div><br>
     	<div class="side_imgs">
     		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			  <div class="carousel-inner" role="listbox">
			    <div class="item active">
			      <img src="img/5.jpg" class="img-rounded" alt="Leem fashion">
			    </div>
			    <div class="item">
			      <img src="img/55.jpg" class="img-rounded" alt="Leem fashion">
			    </div>
			  </div>
			 </div>
		</div><br>
		<div class='side_imgs'>
			 <div id="myCarousel1" class="carousel slide" data-ride="carousel">
			  <div class="carousel-inner" role="listbox">
			    <div class="item active">
			      <img src="img/4.jpg" class="img-rounded" alt="Haram transfer">
			    </div>
			    <div class="item">
			      <img src="img/41.jpg" class="img-rounded" alt="Haram transfer">
			    </div>
			  </div>
			 </div>
		</div>
     </div>
     <div class='col-lg-9 col-md-9 col-sm-9 col-xs-9'>
      <div class="container-fluid">
       <div class="postForm_home row">
         <form action="home.php" method="post">
	      	<input type="submit" name="send_post" value="نشر" id="post_button" class="col-lg-2 col-md-2 col-sm-2 col-xs-2 btn btn-danger" />
	      	<textarea id="post_area" name="post" placeholder="ماذا ستقول ؟!..." class="col-lg-9 col-md-9 col-md-offset-1 col-sm-9 col-sm-offset-1 col-xs-9 col-xs-offset-1"></textarea>
         </form>
       </div>
     </div><br>
    <?php 

    		//Creating posts and inserting them into DB , creating an entry for each post for likes
    	$post = mysql_real_escape_string(strip_tags(@$_POST["post"]));
		 if ($post != "") {
		 	$date_added =  date("Y-m-d");
		 	$added_by = $user_snum;
		 	$added_by_fname = $user_fname;
		 	$added_by_lname = $user_lname;
		 	$delete_status = "No";
		 	$total_likes = 0;
			$post_command = mysql_query("INSERT INTO posts VALUES ('','$post','$date_added','$added_by','$added_by_fname','$added_by_lname','$user_snum','$delete_status')");
			$get_post_id = mysql_query("SELECT id FROM posts ORDER BY id DESC LIMIT 1");
			$post_id = mysql_fetch_assoc($get_post_id);
			$id = $post_id['id'];
			$post_likes = mysql_query("INSERT INTO post_likes VALUES ('','$total_likes','$id')");
		}
		else {
			//Nothing
		}
	?>
	<?php

			//Showing friends' posts on home page
	 $get_friends_q = mysql_query("SELECT friends FROM users WHERE serial_num='$user_snum'");
	 $friends_row = mysql_fetch_assoc($get_friends_q);
	 $friends_values = $friends_row['friends'];
	 if ($friends_values != ""){
	 	$friends_array = explode(",",$friends_values);
	 	foreach ($friends_array as $key => $value) {
	 		$get_frnd_post = mysql_query("SELECT * FROM posts WHERE added_by='$value' AND user_posted_to='$value' AND delete_status='No' ORDER BY date_added DESC") or die (mysql_error());
	 		while ($frnd_post = mysql_fetch_assoc($get_frnd_post)){
	 			$post_id = $frnd_post["id"];
				$frnd_body = $frnd_post["body"];
				$frnd_date_added = $frnd_post["date_added"];
				$added_by = $frnd_post["added_by"];
				$added_by_fname = $frnd_post["added_by_fname"];
				$added_by_lname = $frnd_post["added_by_lname"];
				$frnd_user_posted_to = $frnd_post["user_posted_to"];
				$get_frnd_pic_q = mysql_query("SELECT * FROM users WHERE serial_num='$value'") or die (mysql_error());
				$get_frnd_pic = mysql_fetch_assoc($get_frnd_pic_q);
				$frnd_pic = $get_frnd_pic['profile_pic'];
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
		echo "<div class='whole_post'>
		   <div class='row'>
			<div class='col-lg-11 col-md-11 col-sm-10 col-xs-10'>
			  <div class='posted_by_home'>";
				if ($value == $added_by){
					echo "<a href='$value'>$added_by_fname $added_by_lname</a><br>
						  $frnd_date_added";
				}
				else {
					echo "<a href='$value'>$user_fname $user_lname</a>&nbsp;<img src='./img/arrow1.png' />&nbsp;<a href='$user_snum'>$added_by_fname added_by_lname</a><br>
						  $frnd_date_added";
				}
			  echo " </div>
					</div>
				      <div class='col-lg-1 col-md-1 col-sm-2 col-xs-2'>
					    <a href='$value'><img src='users_data/profile_pics/$frnd_pic' alt='$added_by_fname $added_by_lname' title='$added_by_fname $added_by_lname' class='profile_post_pic_home' height='80' width='70' /></a>
				    </div>
				  </div>
				  <div class='row'>
				    <div class='post_content_home'><h4 id='the_post_home'>$frnd_body</h4></div>
				  </div>
				  <div class='row'>
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
	 		}
	 	}	
	 }
	 
	 			//Showing own posts on home page
	 $show_posts = mysql_query("SELECT * FROM posts WHERE user_posted_to='$user_snum' && delete_status='No' ORDER BY date_added DESC") or die (mysql_error());
	 while ($row = mysql_fetch_assoc($show_posts)){
		$post_id = $row["id"];
		$body = $row["body"];
		$date_added = $row["date_added"];
		$added_by = $row["added_by"];
		$added_by_fname = $row["added_by_fname"];
		$added_by_lname = $row["added_by_lname"];
		$user_posted_to = $row["user_posted_to"];

			//User posted pic
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
		echo "<div class='whole_post'>
			   <div class='row'>
				<div class='col-lg-11 col-md-11 col-sm-10 col-xs-10'>";
				if ($user_snum == $user_posted_to){
						echo "<form action='home.php' method='post' id='delete_form_home'>
	  					 		<input type='submit' name='delete_post_$post_id' class='delete_btn' title='حذف المنشور' value='X' />
	  					  	  </form>";
					};
				echo "<div class='posted_by_home'>";
					if ($user_snum == $added_by){
						echo "<a href='$added_by'>$added_by_fname $added_by_lname</a><br>
							  $date_added";
					}
					else {
						echo "<a href='$added_by'>$added_by_fname $added_by_lname</a>&nbsp;<h5 class='glyphicon glyphicon-triangle-left'></h5>&nbsp;<a href='$user_snum'>$user_fname $user_lname</a><br>
							  $date_added";
					}
				echo "   </div>
						</div>
					      <div class='col-lg-1 col-md-1 col-sm-2 col-xs-2'>
						    <a href='$added_by'><img src='users_data/profile_pics/$posted_prof_pic' alt='$added_by_fname $added_by_lname' title='$added_by_fname $added_by_lname' class='profile_post_pic_home' height='80' width='70' /></a>
					    </div>
					  </div>
					  <div class='row'>
					    <div class='post_content_home'><h4 id='the_post_home'>$body</h4></div>
					  </div>
					  <div class='row'>
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

					  		//Deleting post from home page
					  if (isset($_POST['delete_post_' . $post_id .''])){
		  					$delete_post_q = mysql_query("UPDATE posts SET delete_status='Yes' WHERE user_posted_to='$user_posted_to' && id='$post_id'");
		  					echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/home.php\">";
	  				  }
	  				  else {
	  				  	//Nothing
	  				  }
	 }
	?>
	 </div>
	</div>
  </div>