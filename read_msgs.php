<?php include("./inc/header_inc.php"); ?>
<div class="container read_msgs"><br>
 <h2>الرسائل المرسلة</h2>
 <h3><a href='messages.php'>الرجوع </a>إلى الرسائل الجديدة</h3><hr>
   		<?php 
      
            //Show sent messages
  			$get_msg_q = mysql_query("SELECT * FROM messages WHERE from_user='$user_snum' AND remove_status='No' ORDER BY id DESC") or die (mysql_error());
  			$results = mysql_num_rows($get_msg_q);
  			while($row_value = mysql_fetch_assoc($get_msg_q)){
    			$id = $row_value['id'];
    			$from_user = $row_value['from_user'];
    			$from_user_fname = $row_value['from_user_fname'];
    			$from_user_lname = $row_value['from_user_lname'];
    			$to_user = $row_value['to_user'];
    			$to_user_fname = $row_value['to_user_fname'];
    			$to_user_lname = $row_value['to_user_lname'];
    			$msg_title = $row_value['msg_title'];
    			$msg_content = $row_value['msg_content'];
    			$msg_date = $row_value['date'];
          if (isset($_POST['delete_read_' . $id . ''])){
            $remove_read_msg = mysql_query("UPDATE messages SET remove_status='Yes' WHERE id='$id'");
            echo "<meta http-equiv=\"refresh\" content=\"0; url=http://localhost/test/read_msgs.php\">";
          }
    				echo "<div class='msg_read'>
            <form action='read_msgs.php' method='post'>
              <input type='submit' name='delete_read_$id' class='delete_read_msg' value='X' />
            </form>
    				<h3>".$msg_date."&nbsp&nbsp|&nbsp&nbspإلى " ."<a href='$to_user'>".$to_user_fname." ".$to_user_lname."</a></h3>
    				<h3><b>عنوان الرسالة : </b>".$msg_title."</h3>
    				<h4><b>المحتوى : </b>".$msg_content."</h4></div>";
  			}
  		 ?>
</div>