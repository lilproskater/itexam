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
                echo '<script>window.location.href = "./exam.php#bottom_btn"</script>';
                break;
            }
        }

        if (!$show_fill_error) {
            $question_counter = 1;
            foreach ($questions as $question) {
                $index = 'Q'.$question_counter;
                $answer = $data[$index];
                if ($answer == strtoupper($question->right_answer))
                    $score ++;
                $question_counter ++;
            }
            $show_result = true;
        }
    }
  
    function get_mark($persent) {
        if ($persent <= 35) {
            return 2;
        }
        else if ($persent <= 60) {
            return 3;
        }
        else if ($persent <= 80) {
            return 4;
        }
        else {
            return 5;
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
    <?= (!$show_result) ? '<script src="src/js/timer.js"></script>' : ''?>
    <title>IT Exam</title>
</head>
<body>
    <?php if (@$_SESSION['logged_user'] == ''): ?>
        <div class="container ooops">
            <form action="./index.php" method="POST">
                <h1 class="ooops-title">Упс! Как вы тут оказались?)</h1>
                <h1 class="exit-text">Пожалуйста перейдите на главную страницу и авторизуйтесь)</h1>
                <button class="btn btn-success exit-btn" type="submit">На главную страницу</button>
            </form>
        </div>

    <?php else: ?>
        <?php if ($show_result) : ?>
            <script>sessionStorage.clear();</script>
            <div class="container">
                <form class="result-form" action="./logout.php" method="POST">
                    <?php if (R::count('results', 'username = ?', array($_SESSION['logged_user']->username)) > 0): ?>
                        <h1 class="passing-again-error" style="font-size: 48px;">Ошибка!</h1><br>
                        <h1 class="passing-again-error">Вы уже проходили тест! Пожалуйста выйдите с системы</h1>
                    <?php else: ?>
                        <?php
                            $persentage = round(100 * $score / R::count('questions'));
                            $answers = array();
                            for ($i = 1; $i <= R::count('questions'); $i ++)
                                array_push($answers, $data['Q'.$i]);
                            $result = R::dispense('results');
                            $result->name = $_SESSION['logged_user']->name;
                            $result->surname = $_SESSION['logged_user']->surname;
                            $result->answers = implode("\n", $answers);
                            $result->right_answers = $score;
                            $result->persentage = $persentage.'%';
                            $result->mark = get_mark($persentage);
                            $result->grade = $_SESSION['logged_user']->grade;
                            $result->letter = $_SESSION['logged_user']->letter;
                            $result->username = $_SESSION['logged_user']->username;
                            $result->date = date("d.m.Y H:i:s");
                            R::store($result);  
                        ?>
                        <h1 class="result-title">Тест закончен</h1>
                        <h1 class="result-text">Правильных ответов: <?= $score; ?> из <?= R::count('questions'); ?></h1>
                        <h1 class="result-text">Тест пройден на: <?= $persentage;?> %</h1>
                        <h1 class="result-text">Ваша оценка: <?= get_mark($persentage); ?></h1><br><br>
                        <h1 class="exit-text">Спасибо что прошли тест, ваши результаты сохранены.</h1>
                        <h1 class="exit-text">Пожалуйста нажмите "Выход" чтобы выйти из системы</h1>
                    <?php endif; ?>
                    <button class="btn btn-success exit-btn" type="submit">Выход</button>
                </form>
            </div>

        <?php else : ?>
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
                            <h1 id="time"></h1>
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
                            <button type="submit" id="bottom_btn" class="btn btn-success finish_btn" name="do_finish">Завершить</button>
                            <?php
                                if ($show_fill_error) {
                                    echo "Ответье на все вопросы";
                                }
                            ?>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
