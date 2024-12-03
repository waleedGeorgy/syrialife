<?php include("./inc/header_inc.php"); ?><br><br>
<div class='container'>
 <div class="container-fluid search_page">

	<?php
			//URL of the page is the search query
		if(isset($_GET['search'])){
			$search_value = $_GET['search'];
			if ($search_value==""){
				echo "<br><br><br><center><h2>ابدأ البحث عن أصدقائك الآن !</h2>
					  <h3>أدخل شيء ما في مربع اليحث</h3></center>";
			}
			else {
				
						//Search by search query
				$search_query = mysql_query("SELECT * FROM users WHERE first_name LIKE '%$search_value%' OR last_name LIKE '%$search_value%'") or die(mysql_error());
				$search_num_rows = mysql_num_rows($search_query);
				echo "<br><h1>نتائج البحث :</h1><hr>";
				if ($search_num_rows==0){
					echo "<br><center><h2>لا يوجد نتائج وفق البحث <b>".$search_value."</b></h2>
						  <h3>حاول البحث من جديد !</h3></center>";
				}
				else {
					while ($row=mysql_fetch_assoc($search_query)) {
							$serial_num = $row['serial_num'];
					  		$first_name = $row['first_name'];
					  		$last_name = $row['last_name'];
					  		$father_name = $row['father_name'];
					  		$bdate = $row['bdate'];
					  		$bio = $row['bio'];
					  		$profile_pic = $row['profile_pic'];
					  		echo "<div class='search_result'>";
					  		if ($profile_pic==""){
								echo"<a href='$serial_num'><img src='img/def_pic.jpg' title='$first_name $last_name' class='profile_pic_search' alt='$first_name $last_name' height='120' width='95' /></a>";
							}
							else {
								echo"<a href='$serial_num'><img src='users_data/profile_pics/$profile_pic' title='$first_name $last_name' class='profile_pic_search' alt='$first_name $last_name' height='120' width='95' /></a>";
							}
					  		echo "&nbsp&nbsp&nbsp<b>الاسم و النسبة : </b><a href='$serial_num'>".$first_name." ".$last_name."</a><br>";
					  		echo "&nbsp&nbsp&nbsp<b>اسم الأب : </b>".$father_name."</a><br>";
					  		echo "&nbsp&nbsp&nbsp<b>تاريخ الولادة : </b>".$bdate."<br>";
					  		if ($bio==""){
					  			echo "&nbsp&nbsp&nbsp<b>معلومات عامة : </b>لا يوجد أي معلومات";
					  		}
					  		else {
					  			echo "&nbsp&nbsp&nbsp<b>معلومات عامة : </b>".$bio;
					  		}
					  		echo "</div>";
				  	}
				}
			}
		}
	?>
 </div>
</div>