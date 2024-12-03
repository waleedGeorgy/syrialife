<?php include ( "inc/header_inc.php" ); ?>
<?php include ("./inc/login_inc.php"); ?>
<?php 

        //Sign Up Script
	if (isset($_POST['reg'])){
		   $fname = mysql_real_escape_string(strip_tags($_POST['fname']));
		   $lname = mysql_real_escape_string(strip_tags($_POST['lname']));
		   $ftname = mysql_real_escape_string(strip_tags($_POST['ftname']));
		   $mtname = mysql_real_escape_string(strip_tags($_POST['mtname']));
		   $date = mysql_real_escape_string(strip_tags($_POST['date']));
		   $snum = mysql_real_escape_string(strip_tags($_POST['snum']));
		   $pwd1 = mysql_real_escape_string(strip_tags($_POST['password1']));
		   $pwd2 = mysql_real_escape_string(strip_tags($_POST['password2']));
		   if($fname && $lname && $ftname && $mtname && $date && $snum && $pwd1 && $pwd2){
		     if($pwd1==$pwd2){
				  $s_check = mysql_query("SELECT serial_num FROM users WHERE serial_num='$snum'") or die(mysql_error());
				  $s_row_check = mysql_num_rows($s_check);
					if ($s_row_check == 0){
					 if (is_numeric($snum)&&strlen($snum)==11){
					 	if ((strlen(utf8_decode($fname))>2&&strlen(utf8_decode($fname))<13)||(strlen(utf8_decode($lname))>2&&strlen(utf8_decode($lname))<13)){
					 		if (strlen(utf8_decode($pwd1))>5&&strlen(utf8_decode($pwd1))<30){
					 			$pwd1 = md5($pwd1);
					 			$pwd2 = md5($pwd2);
					 			$query = mysql_query("INSERT INTO users VALUES ('','$fname','$lname','$ftname','$mtname','$date','$snum','$pwd1','','','','','');");
								echo ("<div class='sign_up_success'><h3>تمت عملية التسجيل بنجاح !</h3></div>");
					 		}
					 		else {echo "<div class='sign_error'><p>يجب أن يتراوح طول كلمة السر ما بين 6 و 20 محرفا . </p></div>";}
					 	}
					 	else {echo "<div class='sign_error'><p>يجب أن يكون طول كل من الاسم و النسبة و اسم الأب و اسم الأم ما بين 3 و 12 محرفا . </p></div>";}
					 }
					 else {echo "<div class='sign_error'><p>يجب أن يتألف الرقم الوطني من أرقام فقط و أن يكون 11 رقما . </p></div>";}
					}
					else {echo "<div class='sign_error'><p>تم تسجيل حساب مسبق بهذا الرقم الوطني . </p></div>";}
			  }
			 else {echo "<div class='sign_error'><p>لا يوجد تطابق في كلمة السر ! </p></div>";}
		   }
		   else
		   {echo "<div class='sign_error'><p>جميع الحقول مطلوبة ! </p></div>";}
		  }
 ?>
 <div class="container sign_page">
  <h2 style="text-align:center;font-size:40px">إنشاء حساب / Sign Up</h2>
  <h4 style="text-align:center">قمت بالتسجيل مسبقا؟ اضغط <a href="" data-toggle="modal" data-target="#myModal1">هنا</a> و انتقل إلى حسابك مباشرة</h4><br>
  		<!-- Start of Modal -->
   <div class="modal fade" id="myModal1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
          <h1 class="modal-title" id="myModalLabel" style="text-align:center">تسجيل الدخول / Log In</h1>
        </div>
        <div class="modal-body">
          <form action="sign.php" method="post" name="logform" class="form-horizontal" role="form">
    	    <?php include ("./inc/modal_inc.php"); ?>
        </div>
   		<!-- End of Modal -->

   		<!-- Sign Up Form -->
  <form action="sign.php" method="POST" name="signform" class="form-horizontal" role="form">
    <div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="text" class="form-control" name="fname" id="sname" placeholder="الاسم" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">الاسم:</label>
    </div>
	<div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="text" class="form-control" name="lname" id="slname" placeholder="النسبة" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">النسبة:</label>
    </div>
    <div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="text" class="form-control" name="ftname" id="sfaname" placeholder="اسم الأب" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">اسم الأب:</label>
    </div>
	<div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="text" class="form-control" name="mtname" id="smoname" placeholder="اسم الأم" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">اسم الأم:</label>
    </div>
    <div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="date" class="form-control" name="date" id="sdate" placeholder="تاريخ الولادة" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">تاريخ الولادة</label>
    </div>
	<div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="text" class="form-control" name="snum" id="ssenum" placeholder="الرقم الوطني" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">الرقم الوطني:</label>
    </div>
	<div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="password" class="form-control" name="password1" id="spassword" placeholder="كلمة السر" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">كلمة المرور :</label>
    </div>
	<div class="form-group">
      <div class="col-sm-10 col-xs-8">
        <input type="password" class="form-control" name="password2" id="spwd" placeholder="تأكيد كلمة السر" required>
      </div>
	  <label style="font-size:25px" class="control-label col-sm-2 col-xs-4">تأكيد كلمة المرور:</label>
    </div>
    <hr><div class="form-group">        
        <button type="submit" name="reg" class="btn btn-danger">تسجيل / Sign Up</button>
    </div>
  </form>
  		<!-- End of Sign Up Form -->
 </div>

</body>
<?php include ( "./inc/footer_inc.php" ); ?>