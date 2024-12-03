<?php include ("inc/header_inc.php"); ?><br><br>
<?php 

		//User's URL
	if (isset($_GET['u'])){
		$snumber = mysql_real_escape_string(strip_tags($_GET['u']));
		if (ctype_alnum($snumber)){
			$check = mysql_query("SELECT first_name, last_name, serial_num FROM users WHERE serial_num= '$snumber' ") or die(mysql_error());
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
 ?>
 <div class="container album_page"><br>
	 <h1 style="margin-top:25px;">ألبومات <?php echo $first_name." ".$last_name; ?></h1><hr>
	 <h3 style="text-indent:5px;"><a href='create_album.php'>إضافة </a>ألبوم جديد</h3>
	 <?php 

	 		//Get all the albums for each user
	 	$get_albums_q = mysql_query("SELECT * FROM photo_album WHERE created_by='$serial_num'") or die(mysql_error());
	 	$get_albums_num = mysql_num_rows($get_albums_q);
	 	if ($get_albums_num==0){
	 		echo "<br><center><h3>لا يوجد ألبومات حاليا</h3>
	 			  <h4>قم <a href='create_album.php'>بإنشاء ألبوم الآن !</a></h4></center>";
	 	}
	 	else {
		 	while ($row=mysql_fetch_assoc($get_albums_q)) {
		 		$id = $row['id'];
		 		$title = $row['title'];
		 		$created_by = $row['created_by'];
		 		$created_by_fname = $row['created_by_fname'];
		 		$created_by_lname = $row['created_by_lname'];
		 		$description = $row['description'];
		 		$date_created = $row['date_created'];
		 		$album_cover = $row['album_cover'];
		 		$get_photos = mysql_query("SELECT id FROM photos WHERE photo_id='$id'") or die(mysql_error());
		 		$photos_num_rows = mysql_num_rows($get_photos);
			 	echo "<div class='whole_album row'>
			 			   <div class='col-lg-6 col-md-7 col-sm-11 col-xs-11 left_side_album'>
				 		   		<a href='photos.php?album_id=".$id."&title=".$title."'><img src='".$album_cover."' alt='".$title."' title='".$title."' width='540' height='275' class='album_cover' /></a>
				 		   </div>
				 		   <div class='col-lg-6 col-md-4 col-sm-12 col-xs-12 right_side_album'><br><br><br>
					 			<h4><b>عنوان الألبوم : </b><a href='photos.php?album_id=".$id."&title=".$title."'>".$title."</a></h3>
					 			<h4><b>تاريخ الإنشاء : </b>".$date_created."</h3>
					 			<h4><b>معلومات عامة : </b>".$description."</h3>
					 			<h4><b>عدد الصور : </b>".$photos_num_rows."</h3>
				 		   </div>
			 		  </div>";
	 		}
	    }
	  ?>
</div><br>