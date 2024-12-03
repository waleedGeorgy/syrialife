<?php include("./inc/header_inc.php"); ?><br><br>

<div class="container create_album">
	<h1>إنشاء ألبوم جديد</h1><hr>
	<h3><a href="albums.php?u=<?php echo $user_snum; ?>">الرجوع </a>إلى الألبومات</h3><br>
	<form action="" method="post" enctype="multipart/form-data">
		<input type="text" name="album_title" placeholder='عنوان الألبوم' class="form-control">
		<textarea name="album_desc" placeholder='عن الألبوم...' class="form-control"></textarea><br>
		<div class='upload_cover'>
			<h4 class="upload_caption"><b>اختر صورة للألبوم</b></h4>
			<div class="create_album_upl"><input type="file" id="file" class="inp_file_album" name="album_cover" /></div>
		</div><br>
		<input type="submit" name="create_album" value="إنشاء" class="btn btn-danger form-control">
	</form>
	<?php 

			//Creating new album
		if (isset($_POST['create_album'])){
			$album_title = $_POST['album_title'];
			$album_desc = $_POST['album_desc']; 
			$album_date = date("Y-m-d");
			$album_cover_img = $_FILES['album_cover'];
			$album_cover_name = @$_FILES['album_cover']['name'];
			$album_cover_type = @$_FILES['album_cover']['type'];
			$album_cover_size = @$_FILES['album_cover']['size'];
			if ($album_title && $album_desc && $album_cover_img){
				if ((($album_cover_type=="image/jpeg") || ($album_cover_type=="image/jpg") || ($album_cover_type=="image/png")) && ($album_cover_size < 2000000)){
					$characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRTSUVWXYZ0123456789";
					$random_dir_name = substr(str_shuffle($characters), 0, 15);
					mkdir("users_data/album_covers/$random_dir_name");
					move_uploaded_file(@$_FILES['album_cover']['tmp_name'], "users_data/album_covers/$random_dir_name/".$album_cover_name);
					$create_album_query = mysql_query("INSERT INTO photo_album VALUES('','$album_title','$user_snum','$user_fname','$user_lname','$album_desc','$album_date','users_data/album_covers/$random_dir_name/$album_cover_name')");
					echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/albums.php?u=$user_snum\">";
				}
				else {
					echo "<br><center><h3>يجب أن لا يزيد حجم الصورة عن 2MB و أن تكون من النوع jpeg أو png</h3></center>";
				}
			}
			else {
				echo "<br><center><h3>يجب إدخال كل من عنوان و شرح الألبوم و اختبار صورة للألبوم</h3><center>";
			}
		}
		else {
			//Nothing
		}
	 ?>
</div>