<?php include("./inc/header_inc.php") ?>
<?php 
	if ($user_snum){
			//Continue
	}
	else {
		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/index.php\">";
		exit();
	}
?>
<br>
<div class='container reminders'>
 <h1>السلامات</h1><hr>
 <?php 

 		//Checking for reminders and showing them to the users
	$search_reminders = mysql_query("SELECT * FROM reminders WHERE to_user='$user_snum'") or die(mysql_error());
	$reminders_num = mysql_num_rows($search_reminders);
	if ($reminders_num == 0){
		echo "<h3 style='text-align:center;'>لا يوجد لديك أي سلامات حاليا</h3>";
		echo "<h5 style='text-align:center;font-size:18px'>قم بالسلام على أصدقاءك !</h5>";
	}
	else {
	  while($reminder = mysql_fetch_assoc($search_reminders)){
		$from_user = $reminder['from_user'];
		$from_user_fname = $reminder['from_user_fname'];
		$from_user_lname = $reminder['from_user_lname'];
		$to_user = $reminder['to_user'];
		$to_user_fname = $reminder['to_user_fname'];
		$to_user_lname = $reminder['to_user_lname'];
		if (isset($_POST['reminder_reply_'.$from_user])){
			$remove_reminder = mysql_query("DELETE FROM reminders WHERE from_user='$from_user' && to_user='$to_user'");
			$new_reminder = mysql_query("INSERT INTO reminders VALUES ('','$user_snum','$user_fname','$user_lname','$from_user','$from_user_fname','$from_user_lname')");
			header("Location: reminders.php");
			echo "<p>تم رد السلام".$from_user_fname." ".$from_user_lname."</p>";
 		}
			echo "<form action='reminders.php' method='POST' class='reminders_form container-fluid'>
		 		<h4>لديك سلام من <a href='$from_user'>$from_user_fname $from_user_lname </a>!</h4>
		 		<input type='submit' name='reminder_reply_$from_user' class='reminder_reply btn btn-danger' value='رد السلام' />
		 	<hr></form>
			";
		}
	}
 ?>
</div>