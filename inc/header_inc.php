<?php
include ( "./inc/connect_inc.php" );

    //Starting the logged in user session
session_start();
 if (!isset($_SESSION["login_fname"])||(!isset($_SESSION["login_lname"]))||(!isset($_SESSION["login_snum"]))) {
    $user_fname="";
    $user_lname="";
    $user_snum="";
 }
 else {
    $user_fname = $_SESSION["login_fname"];
    $user_lname = $_SESSION["login_lname"];
    $user_snum = $_SESSION["login_snum"];
 }
?>

<?php

      //Counting the unread messages
  $unread_msg_q = mysqli_query($conn, "SELECT read_status FROM messages WHERE to_user='$user_snum' && read_status='No'");
  $unread_row_num = mysqli_num_rows($unread_msg_q);
  $unread_row_num_show = "(".$unread_row_num.")";

    //Counting friend requests
  $friend_reqs_q = mysqli_query($conn, "SELECT id FROM friend_reqs WHERE to_user='$user_snum'");
  $friend_reqs_num = mysqli_num_rows($friend_reqs_q);
  $friend_reqs_num_show = "(".$friend_reqs_num.")";

    //Counting the reminders
  $reminders_q = mysqli_query($conn, "SELECT id FROM reminders WHERE to_user='$user_snum'");
  $reminders_num = mysqli_num_rows($reminders_q);
  $reminders_num_show = "(".$reminders_num.")";

    //Counting all the relations
  $all_relations = $unread_row_num + $friend_reqs_num + $reminders_num;
  $all_relations = "(".$all_relations.")";
?>

<!DOCTYPE html>
<html dir="rtl">

<head>
  <meta charset="UTF-8">
  <title>SyriaLife</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/mycss.css">
  <script type="text/javascript" src="bootstrap/js/prefixfree.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/jquery.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/myscript.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <!--Header Row-->
  <div class="row header navbar navbar-default navbar-fixed-top">
     <div class="logo col-lg-3 col-md-2 col-sm-3 col-xs-3">
      <img src="img/logoa.png" />
     </div>
     <div class="searchbox col-lg-3 col-md-4 col-sm-4 col-xs-4">
      <form action="search.php" method="GET" id="search" class="form-horizontal">
       <div class="form-group">
        <input type="text" class="form-control" name="search" placeholder="ابحث هنا">
       </div>
      </form>
     </div>
     <?php

        //Change the content of the header depending on whether the user is logged in or not
     if(!$user_fname || !$user_lname || !$user_snum){
      echo '<div class="navmenu col-lg-4 col-md-5 col-sm-4 col-xs-4">
               <nav class="navbar navbar-default">
                 <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar_collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                </div>
            <div class="collapse navbar-collapse" id="navbar_collapse">
              <ul class="nav navbar-nav navbar-right">
                  <li><a href="#" style="color:#f1f1f1;">اسم مؤسساتي</a></li>
                  <li><a href="sign.php" style="color:#f1f1f1;">حساب جديد</a></li>
                  <li><a href="index.php" style="color:#f1f1f1;">الأساسية</a></li>
              </ul>
          </div>
        </nav>
       </div>';
     }
     else {
        echo '<div class="navmenu col-lg-4 col-md-5 col-sm-4 col-xs-4">
             <nav class="navbar navbar-default">
              <div class="container-fluid">
               <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed toggle_btn" data-toggle="collapse" data-target="#navbar_collapse" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar" style="background-color:#c48327;"></span>
                  <span class="icon-bar" style="background-color:#c48327;"></span>
                  <span class="icon-bar" style="background-color:#c48327;"></span>
                </button>
              </div>
          <div class="collapse navbar-collapse" id="navbar_collapse">
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
               <a href="#" class="dropdown-toggle" style="color:#f1f1f1;font-size:18px" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">الإعدادت <span class="caret"></span></a>
                 <ul class="dropdown-menu" style="text-align:right">
                   <li><a href="account_settings.php">إعدادات الحساب</a></li>
                   <li><a href="logout.php">خروج</a></li>
                 </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" style="color:#f1f1f1;font-size:18px" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">العلاقات '.$all_relations.' <span class="caret"></span></a>
                  <ul class="dropdown-menu" style="text-align:right">
                    <li><a href="friend_reqs.php">طلبات الصداقة '.$friend_reqs_num_show.'</a></li>
                    <li><a href="messages.php">الرسائل '.$unread_row_num_show.'</a></li>
                    <li><a href="reminders.php">السلامات '.$reminders_num_show.'</a></li>
                    <li><a href="albums.php?u='.$user_snum.'">الألبومات و الصور</a></li>
                  </ul>
              </li>
              <li><a href="' .$user_snum. '" style="color:#f1f1f1;">'.$user_fname.'</a></li>
              <li><a href="home.php" style="color:#f1f1f1;">الأساسية</a></li>
            </ul>
          </div>
        </div>
      </nav>
     </div>';
     }
     ?>
  </div>
