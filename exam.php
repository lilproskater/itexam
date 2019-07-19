<?php
  require "./config.php";
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.5">
	<link rel="stylesheet" href="src/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="src/css/exam.css">
	<script type="text/javascript" href="src/libs/jquery-3.4.1.min.js"></script>
	<title>IT Exam</title>
</head>
<body>
	<script type="text/javascript">
      //timer script
	</script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 info-container fixed-col">
            	<div class="info">
                    Имя: <?= $_SESSION['logged_user']->name; ?> <br>
                    Фамилия: <?= $_SESSION['logged_user']->surname; ?> <br>
                    Класс: <?= $_SESSION['logged_user']->grade, $_SESSION['logged_user']->letter; ?> <br>
                </div>
            </div>
            <div class="col-md-6 timer-container fixed-col">
                <div id="timer">
                    <!-- Timer here -->
                    03 : 00 : 00
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 content">
                <p><b>1. Question 1 ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</b></p>
                <p>A) 50 B) 20 C) 30 D) 40</p>
                <input type="radio" class="radio" name="Q1"><font class="font">A</font><br>
  				<input type="radio" class="radio" name="Q1"><font class="font">B</font><br>
 				<input type="radio" class="radio" name="Q1"><font class="font">C</font><br>  
 				<input type="radio" class="radio" name="Q1"><font class="font">D</font><br> 
            </div>
        </div>
    </div>
</body>
</html>
