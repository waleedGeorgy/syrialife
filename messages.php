<?php include("./inc/header_inc.php"); ?>
<div class="container messages_page"><br>
 <h1>الانتقال إلى <a href='read_msgs.php'>الرسائل المرسلة</a></h1>
  <div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 left_div">
	 <h2>الرسائل الجديدة</h2><hr class="head_line">
		<?php 

				//Showing the unread messages
			$get_messages = mysql_query("SELECT * FROM messages WHERE to_user='$user_snum' && read_status='No' && delete_status='No' ORDER BY id DESC") or die (mysql_error());
			$msg_count = mysql_num_rows($get_messages);
			if ($msg_count != 0){
				while ($message = mysql_fetch_assoc($get_messages)) {
					$msg_id = $message['id'];
					$from_user = $message['from_user'];
					$from_user_fname = $message['from_user_fname'];
					$from_user_lname = $message['from_user_lname'];
					$to_user = $message['to_user'];
					$to_user_fname = $message['from_user_fname'];
					$to_user_lname = $message['from_user_lname'];
					$msg_title = $message['msg_title'];
					$msg_content = $message['msg_content'];
					$msg_date = $message['date'];
					$read_status = $message['read_status'];
					$delete_status = $message['delete_status'];
		?>

	 		<!-- Script for opening messages on the same page -->
		<script language="javascript">
			function toggle_msg<?php echo $msg_id; ?>() {
		           var link = document.getElementById("show_msg<?php echo $msg_id; ?>");
		           var text = document.getElementById("msg_link<?php echo $msg_id; ?>");
		           if (link.style.display == "block") {
		              link.style.display = "none";
		           }
		           else
		           {
		             link.style.display = "block";
		           }
		         }
		</script>

		<?php
					if(strlen(utf8_decode($msg_title))>75){
						$msg_title = substr($msg_title, 0, 75).'...';
					}
					else {
						$msg_title = $msg_title;
					}

					if(strlen(utf8_decode($msg_content))>175){
						$msg_content = substr($msg_content, 0, 175).'...';
					}
					else {
						$msg_content = $msg_content;
					}

						//When pressing the opened message button it moves this message to the already read messages (Read_status = YES)
					if (isset($_POST['opened_' . $msg_id .''])){
						$opened_msg_query = mysql_query("UPDATE messages SET read_status='Yes' WHERE id='$msg_id'");
						echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/messages.php\">";
					}

					echo "<div class='income_msg'>
				  		   <h3>$msg_date&nbsp&nbsp|&nbsp&nbsp<a href='$from_user'>$from_user_fname $from_user_lname</a></h3>
				  		   <form action='messages.php' method='post'>
				  		   		<input type='submit' class='read_msg' name='opened_$msg_id' value='قرأت الرسالة' title='وضع الرسالة ضمن الرسائل المقروءة' /><br>
				  		   		<b>عنوان الرسالة :</b> <input type='button' name='open_msg' value='$msg_title' onClick='javascript:toggle_msg$msg_id();' />
				  		   </form>
				  		   <div id='show_msg$msg_id' style='display: none;'>
				  		    <div class='message_content'><p>$msg_content</p></div>
				  		   </div></div><br>";
				}
			}
			else {
				echo "<h4 style='text-align:center;font-size:22px'>لا يوجد لديك أي رسائل جديدة</h4>";
			}
		 ?>
	</div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 right_div">
      <h2>الرسائل المقروءة</h2><hr class="head_line">
		<?php 
				//Showing the old messages
			$get_messages = mysql_query("SELECT * FROM messages WHERE to_user='$user_snum' && read_status='Yes' && delete_status='No' ORDER BY id DESC") or die (mysql_error());
			$read_msg_count = mysql_num_rows($get_messages);
			if ($read_msg_count != 0){
				while ($message = mysql_fetch_assoc($get_messages)) {
					$msg_id = $message['id'];
					$from_user = $message['from_user'];
					$from_user_fname = $message['from_user_fname'];
					$from_user_lname = $message['from_user_lname'];
					$to_user = $message['to_user'];
					$to_user_fname = $message['from_user_fname'];
					$to_user_lname = $message['from_user_lname'];
					$msg_title = $message['msg_title'];
					$msg_content = $message['msg_content'];
					$msg_date = $message['date'];
					$read_status = $message['read_status'];
					$delete_status = $message['delete_status'];
		?>
		<script language="javascript">
			function toggle_msg<?php echo $msg_id; ?>() {
		           var link = document.getElementById("show_msg<?php echo $msg_id; ?>");
		           var text = document.getElementById("msg_link<?php echo $msg_id; ?>");
		           if (link.style.display == "block") {
		              link.style.display = "none";
		           }
		           else
		           {
		             link.style.display = "block";
		           }
		         }
		</script>
		<?php

					if(strlen(utf8_decode($msg_title))>75){
						$msg_title = substr($msg_title, 0, 75).'...';
					}
					else {
						$msg_title = $msg_title;
					}

					if(strlen(utf8_decode($msg_content))>175){
						$msg_content = substr($msg_content, 0, 175).'...';
					}
					else {
						$msg_content = $msg_content;
					}
						//Deleting the message when pressing the delete button
					if (isset($_POST['delete_' . $msg_id .''])){
						$msg_delete_q = mysql_query("UPDATE messages SET delete_status='Yes' WHERE id='$msg_id'");
						echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/messages.php\">";
					}

						//Replying to the message
					if(isset($_POST['reply_' . $msg_id .''])){
						echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/reply_msgs.php?u=$from_user\">";
					}

					echo "<div class='income_msg'>
				  		   <h3>$msg_date&nbsp&nbsp|&nbsp&nbsp<a href='$from_user'>$from_user_fname $from_user_lname</a></h3>
				  		   <form action='messages.php' method='post'>
				  		   		<input type='submit' class='read_msg' name='delete_$msg_id' value='حذف الرسالة' />
				  		   		<input type='submit' class='reply_msg' name='reply_$msg_id' value='الرد على الرسالة' /><br>
				  		   		<b>عنوان الرسالة :</b> <input type='button' href='javascript:void(0)' name='open_msg' value='$msg_title' onClick='javascript:toggle_msg$msg_id();' />
				  		   </form>
				  		   <div id='show_msg$msg_id' style='display: none;'>
				  		    <div class='message_content'><p>$msg_content</p></div>
				  		   </div></div><br>
				  		  ";
				}
			}
			else {
				echo "<h4 style='margin-right:25px;font-size:22px'>لا يوجد لديك أي رسائل مقروءة</h4>";
			}
		 ?>
    </div>
  </div>
</div>