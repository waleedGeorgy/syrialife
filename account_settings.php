<?php include ("./inc/header_inc.php"); ?>
<?php 
	if ($user_snum){
		//Continue
	}
	else {
		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/index.php\">";
		exit();
	}
 ?>
 <?php 
 
 			//Update password
	$update_pwd = @$_POST['update_pwd'];
	$old_pwd = mysql_real_escape_string(strip_tags(@$_POST['old_pwd']));
	$new_pwd = mysql_real_escape_string(strip_tags(@$_POST['new_pwd']));
	$repeat_pwd = mysql_real_escape_string(strip_tags(@$_POST['new_pwd2']));
	if (isset($update_pwd)) {
	  if($old_pwd && $new_pwd && $repeat_pwd){
		$pwd_query = mysql_query("SELECT * FROM users WHERE serial_num='$user_snum'");
		 while ($row = mysql_fetch_assoc($pwd_query)) {
		 	$db_pwd = $row['password'];
		 	$old_pwd_md5 = md5($old_pwd);
		 	if ($db_pwd == $old_pwd_md5) {
		 		if ((strlen(utf8_decode($new_pwd))>5)&&(strlen(utf8_decode($new_pwd))<25)) {
		 		 if($new_pwd == $repeat_pwd){
		 		 	$new_pwd_md5 = md5($new_pwd);
		 			$repeat_pwd_md5 = md5($repeat_pwd);
		 			$update_pwd_query = mysql_query("UPDATE users SET password='$new_pwd_md5' WHERE serial_num='$user_snum'");
		 			echo "<div class='settings_success'><p>تم تغيير كلمة السر بنجاح</p></div>";
		 			header("Location: account_settings.php");
		 		 }
		 			else {
		 				echo "<div class='settings_fail'><p>لا يوجد تطابق في كلمة السر</p></div>";
		 			}
		 		}
		 		else {
		 			echo "<div class='settings_fail'><p>يجب أن تتراوح كلمة السر ما بين 6 و 24 محرفا</p></div>";
		 		}
		 	}
		 	else {
		 		echo "<div class='settings_fail'><p>تم إدخال كلمة سر قديمة خاطئة</p></div>";
		 	}
		 }
	  }
	  else {
	  	echo "<div class='settings_fail'><p>يجب إدخال جميع الحقول</p></div>";
	  }
	}

			//Update FName, LName, Bio
	$get_info = mysql_query("SELECT first_name, last_name, bio FROM users WHERE serial_num='$user_snum'");
	$get_row = mysql_fetch_assoc($get_info);
	$db_fname = $get_row['first_name']; 
	$db_lname = $get_row['last_name']; 
	$db_bio = $get_row['bio'];
	$update_info = @$_POST['update_info'];
	$f_name = mysql_real_escape_string(strip_tags(@$_POST['first_name']));
	$l_name = mysql_real_escape_string(strip_tags(@$_POST['last_name']));
	$personal_info = mysql_real_escape_string(strip_tags(@$_POST['personal_info']));
	if (isset($update_info)){
		if ((strlen(utf8_decode($f_name))>2)&&(strlen(utf8_decode($f_name))<13)) {
			if ((strlen(utf8_decode($l_name))>2)&&(strlen(utf8_decode($l_name))<13)) {
			$update_user_info = mysql_query("UPDATE users SET first_name='$f_name', last_name='$l_name', bio='$personal_info' WHERE serial_num='$user_snum'");
			$update_user_posts = mysql_query("UPDATE posts SET added_by_fname='$f_name', added_by_lname='$l_name' WHERE added_by='$user_snum'");
			$update_user_comments = mysql_query("UPDATE comments SET posted_by_fname='$f_name', posted_by_lname='$l_name' WHERE posted_by='$user_snum'");
			$update_user_comments1 = mysql_query("UPDATE comments SET posted_to_fname='$f_name', posted_to_lname='$l_name' WHERE posted_by='$user_snum'");
			$update_user_likes = mysql_query("UPDATE user_likes SET user_fname='$f_name', user_lname='$l_name' WHERE user_snum='$user_snum'");
			$update_user_friend_reqs = mysql_query("UPDATE friend_reqs SET from_user_fname='$f_name', from_user_lname='$l_name' WHERE from_user='$user_snum'");
			$update_user_friend_reqs1 = mysql_query("UPDATE friend_reqs SET to_user_fname='$f_name', to_user_lname='$l_name' WHERE to_user='$user_snum'");
			$update_user_messages = mysql_query("UPDATE messages SET from_user_fname='$f_name', from_user_lname='$l_name' WHERE from_user='$user_snum'");
			$update_user_messages1 = mysql_query("UPDATE messages SET to_user_fname='$f_name', to_user_lname='$l_name' WHERE to_user='$user_snum'");
			$update_user_reminders = mysql_query("UPDATE reminders SET from_user_fname='$f_name', from_user_lname='$l_name' WHERE from_user='$user_snum'");
			$update_user_reminders1 = mysql_query("UPDATE reminders SET to_user_fname='$f_name', to_user_lname='$l_name' WHERE to_user='$user_snum'");
			$_SESSION["login_fname"] = $f_name;
			$_SESSION["login_lname"] = $l_name;
			echo "<div class='settings_success'><p>تم تغيير المعلومات الشخصية بنجاح</p></div>";
			header("Location: account_settings.php");
			}
			else {echo "<div class='settings_fail'><p>يجب أن تكون النسبة بين 2 و 12 محرف</p></div>";}
		}
		else {echo "<div class='settings_fail'><p>يجب أن يكون الاسم بين 2 و 12 محرف</p></div>";}
	}
	else {

	}

		//Update Profile Picture
	$profile_pic_check = mysql_query("SELECT profile_pic FROM users WHERE serial_num='$user_snum'");
	$get_profile_pic = mysql_fetch_assoc($profile_pic_check);
	$profile_pic_db = $get_profile_pic['profile_pic'];
	if ($profile_pic_db==""){
		$profile_pic = "img/def_pic.jpg";
	}
	else {
		$profile_pic = "users_data/profile_pics/".$profile_pic_db;
	}

	if (isset($_FILES['profile_pic'])) {
		$profile_pic_name = @$_FILES['profile_pic']['name'];
		$profile_pic_type = @$_FILES['profile_pic']['type'];
		$profile_pic_size = @$_FILES['profile_pic']['size'];
		if ((($profile_pic_type=="image/jpeg") || ($profile_pic_type=="image/jpg") || ($profile_pic_type=="image/png")) && ($profile_pic_size < 2000000)){
			$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRTSUVWXYZ0123456789";
			$random_dir_name = substr(str_shuffle($characters), 0, 15);
			mkdir("users_data/profile_pics/$random_dir_name");
			move_uploaded_file(@$_FILES['profile_pic']['tmp_name'], "users_data/profile_pics/$random_dir_name/".$profile_pic_name);
			$upload_profile_pic = mysql_query("UPDATE users SET profile_pic='$random_dir_name/$profile_pic_name' WHERE serial_num='$user_snum'");
			header("Location: account_settings.php");
		}
		else {echo "يجب أن لا يزيد حجم الصورة عن 2MB و أن تكون من النوع .jpeg أو .png";
		}
	}

			//Update Cover Photo
	$cover_pic_check = mysql_query("SELECT cover_pic FROM users WHERE serial_num='$user_snum'");
	$get_cover_pic = mysql_fetch_assoc($cover_pic_check);
	$cover_pic_db = $get_cover_pic['cover_pic'];
	if ($cover_pic_db==""){
		$cover_pic = "img/def_cover.jpg";
	}
	else {
		$cover_pic = "users_data/cover_pics/".$cover_pic_db;
	}

	if (isset($_POST['upload_cover'])) {
		$cover_img = $_FILES['cover_pic'];
		$cover_pic_name = @$_FILES['cover_pic']['name'];
		$cover_pic_type = @$_FILES['cover_pic']['type'];
		$cover_pic_size = @$_FILES['cover_pic']['size'];
		if ((($cover_pic_type=="image/jpeg") || ($cover_pic_type=="image/jpg") || ($cover_pic_type=="image/png")) && ($cover_pic_size < 3000000)){
			$characters_cover = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRTSUVWXYZ0123456789";
			$random_dir_name_cover = substr(str_shuffle($characters_cover), 0, 15);
			mkdir("users_data/cover_pics/$random_dir_name_cover");
			move_uploaded_file(@$_FILES['cover_pic']['tmp_name'], "users_data/cover_pics/$random_dir_name_cover/".$cover_pic_name);
			$upload_cover_pic = mysql_query("UPDATE users SET cover_pic='$random_dir_name_cover/$cover_pic_name' WHERE serial_num='$user_snum'");
			header("Location: account_settings.php");
		}
		else {echo "يجب أن لا يزيد حجم الصورة عن 3MB و أن تكون من النوع .jpeg أو .png";
		}
	}

		//Closing accounts
	if (isset($_POST['close_no'])){
		header("Location: account_settings.php");
	}
	if (isset($_POST['close_yes'])){
		$close_query = mysql_query("UPDATE users SET closed='yes' WHERE serial_num='$user_snum'");
		session_unset();
		session_destroy();
		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/index.php\">";
	}
 ?>

	 <script type="text/javascript">
	 
	 	//Script for opening a div on the same page
	 	$(document).ready(function(){
	     $('a').on('click',function(){
	        var aID = $(this).attr('href');
	        var elem = $(''+aID).html();
	        $('.target').html(elem);
	     });
		});
	 </script>
 
<div class="container config_content">
	<div class="row">
	  <h1 style="text-indent:15px">تغيير إعدادات الحساب</h1><hr><br>
		<div class="col-lg-7 col-md-9 col-sm-8 col-xs-8">

		  	<!-- Password Change div -->
			<div id="pwd_change">
			 <center>
			  <form action="account_settings.php" method="POST" class="form-horizontal" role="form">
			  	<div class="form-group">
			      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <input type="text" class="form-control" name="old_pwd" id="old_pwd" placeholder="كلمة السر القديمة">
			      </div>
			    </div>
			    <div class="form-group">
			      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <input type="password" class="form-control" name="new_pwd" id="new_pwd" placeholder="كلمة السر الجديدة">
			      </div>
			    </div>
			    <div class="form-group">
			      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <input type="password" class="form-control" name="new_pwd2" id="new_pwd2" placeholder="تأكيد كلمة السر">
			      </div>
			    </div>
			    <div class="form-group">        
			       <button type="submit" name="update_pwd" id="update_pwd" class="btn btn-danger conf_button">تغيير</button>
			    </div>
			  </form>
			 </center>
			</div>

			 <!-- Info Change div -->
			<div id="info_change">
			 <center>
			  <form action="account_settings.php" method="POST" class="form-horizontal" role="form">
				<div class="form-group">
			      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $db_fname; ?>">
			      </div>
			    </div>
			    <div class="form-group">
			      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $db_lname; ?>">
			      </div>
			    </div>
			    <div class="form-group">
			      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <textarea name="personal_info" class="form-control" rows="6" id="personal_info"><?php echo $db_bio; ?></textarea>
			      </div>
			    </div>
			    <div class="form-group">        
			       <button type="submit" name="update_info" id="update_info" class="btn btn-danger conf_button">تغيير</button>
			    </div>
			  </form>
			 </center>
			</div>

		  	<!-- Profile Pic Change div -->
		  <div id="pic_change">
		   <center>
		  	 <form action="" method="POST" enctype="multipart/form-data">
		  		<img src="<?php echo $profile_pic; ?>" height="190" width="160" alt='الصورة الشخصية' class="default_pic" /><br>
			    <div class="upl_img"><input type="file" id="file" class="inp_file" name="profile_pic" /></div>
			    <input type="submit" name="upload_pic" value="تغيير" class="btn btn-danger conf_button">
		  	 </form>
		   </center>
		  </div>

		   	<!-- Cover Pic Change div -->
		  <div id="cover_change">
		   <center>
		  	 <form action="" method="POST" enctype="multipart/form-data">
		  		<img src="<?php echo $cover_pic; ?>" alt="صورة الغلاف" width="320" height="190" class="default_pic"><br>
			    <div class="upl_img"><input type="file" id="file" class="inp_file" name="cover_pic" /></div>
			    <input type="submit" name="upload_cover" value="تغيير" class="btn btn-danger conf_button">
		  	 </form>
		   </center>
		  </div>

		  <!-- Close account div -->
		  <div id="close_account">
		  	<center>
		  		<h3>هل تريد إغلاق حسابك ؟</h3><hr>
		  		<form action="" method="post" class="form-horizontal">
		  		  <div class="form-group">
		  			<input type='submit' value='لا أريد إغلاق حسابي' name="close_no" class="close_btn">&nbsp&nbsp
		  			<input type='submit' value='نعم أغلق حسابي' name="close_yes" class="close_btn">
		  		  </div>
		  		</form>
		  	</center>
		  </div>

		  	<!-- Target div -->
		  <div class="target">
			 <h4 id="settings_info">اختر ما تريد تغييره من القائمة</h4>
		  </div>
		  
		</div>
		
			<!-- Links div -->
		<div class="col-lg-4 col-lg-offset-1 col-md-3 col-sm-4 col-xs-4">
		   <div class="options">
		     <ul>
		     	<li><a href="#pwd_change">كلمة السر</a></li>
		     	<li><a href="#info_change">المعلومات الشخصية</a></li>
		     	<li><a href="#pic_change">الصورة الشخصية</a></li>
		     	<li><a href="#cover_change">صورة الغلاف</a></li>
		     	<li><a href="#close_account">إغلاق الحساب</a></li>
		     </ul>
		   </div>
		</div>
    </div>
</div>