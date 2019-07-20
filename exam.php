<?php
  require "./config.php";
  $questions = R::findAll('questions');
  $question_number = 1;
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
            <div class="col-md-6 col-sm-6 info-container fixed-col">
            	<div class="info">
                    Имя: <?= $_SESSION['logged_user']->name; ?> <br>
                    Фамилия: <?= $_SESSION['logged_user']->surname; ?> <br>
                    Класс: <?= $_SESSION['logged_user']->grade, $_SESSION['logged_user']->letter; ?> <br>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 timer-container fixed-col">
                <div id="timer">
                    <!-- Timer here -->
                    03 : 00 : 00
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 content">
                <?php
                    foreach ($questions as $question) {
                    	echo '<p><b>'.$question_number.'. '.$question->question.'</b></p>';
                    	echo '<p> A) '.$question->a.' B) '.$question->b.' C) '.$question->c.' D) '.$question->d.'</p>';
                    	echo '<input type="radio" class="radio" name="Q'.$question_number.'" value="A"><font class="font">A</font><br>';
                    	echo '<input type="radio" class="radio" name="Q'.$question_number.'" value="B"><font class="font">B</font><br>';
                    	echo '<input type="radio" class="radio" name="Q'.$question_number.'" value="C"><font class="font">C</font><br>';
                    	echo '<input type="radio" class="radio" name="Q'.$question_number.'" value="D"><font class="font">D</font><br>';
                    	echo '<br><br>';
                    	$question_number ++;
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
