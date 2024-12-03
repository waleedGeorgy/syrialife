<?php include ( "./inc/header_inc.php" ) ?>
<?php include ("./inc/login_inc.php"); ?>

		<!--Main Container-->
  <div class="container index_page">
    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12" id="logodiv"><br><br><br><br><br><br><br>
	 <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
	     <div class="flipper">
		  <div class="front">
		   <img src="img/logo1.png" />
		  </div>
		  <div class="back">
		   <img src="img/logob.png" />
		  </div>
	     </div>
        </div>
    </div>
    <br><br>
    <div class="col-lg-6 col-lg-offset-2 col-md-8 col-sm-7 col-xs-12"><br><br>
     <h1 style="text-indent:30px;font-size:52px">أهلا بكم في <i>SyriaLife</i>!</h1><br>
	 <h3>يمكنكم من هنا تسجيل حساب جديد بالموقع , الدخول إلى حساب منشأ مسبقا , أو الدخول باسم مؤسساتي . <a href="#"><b>اضغط هنا</b></a> للمزيد من المعلومات </h3><hr>
	 <a href="sign.php"><h3 class="btn btn-info">الاشتراك بالموقع</h3></a>
	 <a href="" data-toggle="modal" data-target="#myModal"><h3 class="btn btn-info">مشترك مسبقا</h3></a><br>
	 <div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		   <div class="modal-dialog">
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
	          <h1 class="modal-title" id="myModalLabel" style="text-align:center">تسجيل الدخول / Log In</h1>
	        </div>
	        <div class="modal-body">
	          <form action="index.php" method="post" name="logform" class="form-horizontal" role="form">
		  	  <?php include ("./inc/modal_inc.php"); ?>
	        </div>
		     <a href="#"><h3 class="btn btn-info">الدخول باسم مؤسساتي</h3></a>
	        </div>
	       </div>
		<!--End of Main Container-->
</body>
		<!--End Of Body-->
<?php include ( "./inc/footer_inc.php" ); ?>