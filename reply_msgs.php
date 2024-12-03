<?php include("./inc/header_inc.php"); ?>
<?php 
	if (isset($_GET['u'])){
		$snumber = mysql_real_escape_string($_GET['u']);
		if (ctype_alnum($snumber)){
			$check = mysql_query("SELECT first_name, last_name, serial_num FROM users WHERE serial_num= '$snumber' ");
			if (mysql_num_rows($check)==1) {
				$get = mysql_fetch_assoc($check);
				$first_name = $get["first_name"];
				$last_name = $get["last_name"];
				$serial_num = $get["serial_num"];
			} 
		}
	}
?>
<br><br>
<div class="container sent_msgs">
	<?php

			//Composing and sending the message to DB
		if ($user_snum == $serial_num){
			header("Location: $user_snum");
		}
		else {
			echo "<div class='container-fluid message_compose'>
				  <form action='send_msgs.php?u=$serial_num' method='POST'>
					  <h2>ماذا ستقول ل$first_name ؟</h2><hr>
					  <div class='row'>
					      <input type='text' class='msg_title' name='msg_title' placeholder='موضوع الرسالة...' />
					  	  <br><input type='submit' name='send_msg' value='إرسال' class='btn btn-danger send_msg col-lg-3 col-md-3 col-sm-4 col-xs-3' />
						  <textarea name='msg_compose' rows ='6' placeholder='ماذا في بالك...' class='msg_area col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-8 col-sm-offset-1 col-xs-9'></textarea>
					  </div>
				  </form>
				</div>
			";
		}
		
			//Sending messages to other users
		if (isset($_POST['send_msg'])){
			$msg_content = mysql_real_escape_string(strip_tags($_POST['msg_compose']));
			$msg_title = mysql_real_escape_string(strip_tags($_POST['msg_title']));
			$msg_date = date("Y-m-d");
			$read_status = "No";
			$delete_status = "No";
			if($msg_content == ""){
				echo "<h3 class='fail_msg'>لا يمكن إرسال رسالة فارغة</h3>";
			}
			else
			if($msg_title == ""){
				echo "<h3 class='fail_msg'>لا يمكن إرسال رسالة من دون عنوان</h3>";
			}
			else {
			$send_msg = mysql_query("INSERT INTO messages VALUES ('','$user_snum','$user_fname','$user_lname','$serial_num','$first_name','$last_name','$msg_title','$msg_content','$msg_date','$read_status','$delete_status')");
				echo "<h3 class='success_msg'>تم إرسال الرسالة !</h3>";
			}
		}
		else {
				//do nothing
		}
	?>
</div>