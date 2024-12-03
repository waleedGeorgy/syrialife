<?php include ("inc/header_inc.php"); ?><br><br>
<?php 

		//URL is the album id to get all the pics for each album and the title just for showing
	if (isset($_GET['album_id']) && isset($_GET['title'])){
		$album_id = mysql_real_escape_string(strip_tags($_GET['album_id']));
		$title = mysql_real_escape_string(strip_tags($_GET['title']));
	}
 ?>
 <div class="container photos_page"><br>
	  <h1 style="margin-top:25px;">الألبوم : <a href='albums.php?u=<?php echo $user_snum; ?>'><?php echo $title; ?></a></h1><hr>
	  <div class='upload_album_img container-fluid'>
		 <form action="" method="POST" enctype="multipart/form-data">
		    <div class="upl_img_album"><input type="file" id="file" class="inp_file_album" name="album_pic" /></div>
		    <input type="submit" name="upload_pic" value="إضافة صورة" class="btn btn-danger pic_button_album">
		    <input type="text" name='caption' placeholder='بضع كلمات عن الصورة' id="post_area_photo" class="form-control">
		 </form>
	  </div><br>
	  <?php 

	  		//Uploading images
	  	if (isset($_POST['upload_pic'])){
	  		$img_caption = $_POST['caption'];
	  		$img_date = date("Y-m-d");
	  		$album_pic_img = $_FILES['album_pic'];
	  		$album_pic_name = @$_FILES['album_pic']['name'];
			$album_pic_type = @$_FILES['album_pic']['type'];
			$album_pic_size = @$_FILES['album_pic']['size'];
			if ($album_pic_img && $img_caption){
				if ((($album_pic_type=="image/jpeg") || ($album_pic_type=="image/jpg") || ($album_pic_type=="image/png")) && ($album_pic_size < 2000000)){
					$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRTSUVWXYZ0123456789";
					$random_dir_name = substr(str_shuffle($characters), 0, 15);
					mkdir("users_data/user_photos/$random_dir_name");
					move_uploaded_file(@$_FILES['album_pic']['tmp_name'], "users_data/user_photos/$random_dir_name/".$album_pic_name);
					$upload_album_pic = mysql_query("INSERT INTO photos VALUES ('','$album_id','$user_snum','$user_fname','$user_lname','$img_date','$img_caption','users_data/user_photos/$random_dir_name/$album_pic_name')");
					echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/photos.php?album_id=".$album_id."&title=".$title."\">";
				}
				else {
					echo "<br><center><h3>يجب أن لا يزيد حجم الصورة عن 2MB و أن تكون من النوع jpeg أو png</h3></center>";
				}
			}
			else {
				echo "<center><h3>يجب اختيار صورة و وضع شرح عنها</h3>";
			}
	  	}
	  	else {
	  			//Nothing
	  	}
	   ?>
  	<table class='table-condensed'><tr>
	 <?php 

	 		//Get all the photos for each album
	 	$get_photos_q = mysql_query("SELECT * FROM photos WHERE photo_id='$album_id'") or die(mysql_error());
	 	$get_photos_num = mysql_num_rows($get_photos_q);
	 	if ($get_photos_num==0){
	 		echo "<br><center><h3>لا يوجد صور ضمن هذا الألبوم</h3>
		 		  <h4>قم بإضافة صور الآن !</h4></center>";
	 	}
	 	else {
		 	while ($row=mysql_fetch_assoc($get_photos_q)) {
		 		$photo_id = $row['photo_id'];
				$added_by = $row['added_by'];
				$added_by_fname = $row['added_by_fname'];
				$added_by_lname = $row['added_by_lname'];
				$date_added = $row['date_added'];
				$caption = $row['caption'];
				$img_dir = $row['img_dir'];
			 	echo "<td><div class='whole_img'>
					 	<img src='".$img_dir."' title='".$date_added."' class='album_img img img-responsive img-rounded' alt='album_img' />
					 	<center><h4 class='caption'>".$caption."</h4></center>
			 		 </div></td>";
		    }
		}
	  	?>
    </tr></table>
</div>