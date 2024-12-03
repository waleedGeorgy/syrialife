<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
</head>
<?php

		//Connection to SQL DB and enabling the arabic chars
   $conn = mysqli_connect("localhost","root","") or die ("تعذر الاتصال بالمخدم.") ;
   mysqli_set_charset($conn,'utf8');
   mysqli_select_db($conn,"syrialife") or die ("تعذر الاتصال بقاعدة البيانات.");
 ?>
</html>
