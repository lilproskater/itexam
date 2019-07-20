<?php
  require "./config.php";
  $data = $_POST;
  $questions = R::findAll('questions');
  $question_number = 1;
  $show_fill_error = false;
  $show_result = false;
  $score = 0;
  if(isset($data['do_finish'])) {
	  for ($i = 1; $i <= R::count('questions'); $i ++) {
	  	  $index = 'Q'.$i;
	  	  if (!isset($data[$index])) {
	  	      $show_fill_error = true;
	  	      break;
	  	  }
	  }

      if (!$show_fill_error) {
          $question_counter = 1;
	      foreach ($questions as $question) {
	          $index = 'Q'.$question_counter;
	          $answer = $data[$index];
	          if ($answer == $question->right_answer)
	              $score ++;
	          $question_counter ++;
	      }
	      $show_result = true;
	  }
  }
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
    	<form action="./exam.php" method="POST">
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
                            $index = 'Q'.$question_number;
	                    	echo '<input type="radio" class="radio" name="'.$index.'" value="A"';
	                    	echo (empty($data[$index]) || $data[$index] != 'A') ? '' : ' checked="checked"';
	                    	echo '><font class="font">A</font><br>';

	                    	echo '<input type="radio" class="radio" name="'.$index.'" value="B"';
	                    	echo (empty($data[$index]) || $data[$index] != 'B') ? '' : ' checked="checked"';
	                    	echo '><font class="font">B</font><br>';

	                    	echo '<input type="radio" class="radio" name="'.$index.'" value="C"';
	                    	echo (empty($data[$index]) || $data[$index] != 'C') ? '' : ' checked="checked"';
	                    	echo '><font class="font">C</font><br>';

	                    	echo '<input type="radio" class="radio" name="'.$index.'" value="D"';
	                    	echo (empty($data[$index]) || $data[$index] != 'D') ? '' : ' checked="checked"';
	                    	echo '><font class="font">D</font><br>';
	                    	
	                    	echo '<br><br>';
	                    	$question_number ++;
	                    }
	                ?>
	                <button type="submit" class="btn btn-success finish_btn" name="do_finish">Завершить</button>
	                <?php
                        if ($show_fill_error) {
                        	echo "Ответье на все вопросы";
                        }
                        if ($show_result) {
                            echo 'Вы ответили правильно на '.$score.' ответов.'; 
                        }
	                ?>
	            </div>
	        </div>
        </form>
    </div>
</body>
</html>
