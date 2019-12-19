<?php
    require "./config.php";
    $data = $_POST;
    $questions_search_key = $_SESSION['logged_user']->test_type;
    if ($TYPE_OF_TEST == $school_test)
        $questions_search_key = explode(' ', $questions_search_key)[0];
    $show_fill_error = false;
    $show_result = false;
    $score = 0;
    $questions = R::findAll('questions', 'test_type=?', array($questions_search_key));
    $questions_count = count($questions);
    if(isset($data['do_finish'])) {
        for ($question_counter = 1; $question_counter <= $questions_count; $question_counter ++) {
            $index = 'Q'.$question_counter;
            if (!isset($data[$index])) {
                $show_fill_error = true;
                $error_index = $index;
                break;
            }
        }
        if (!$show_fill_error) {
            $question_counter = 1;
            $right_answers = array();
            foreach ($questions as $question) {
                $index = 'Q'.$question_counter;
                $answer = $data[$index];
                $right_answers[] = $question->right_answer;
                if ($answer == strtoupper($question->right_answer))
                    $score ++;
                $question_counter ++;
            }
            $show_result = true;
        }
    }
  
    function get_mark($persent) {
        global $TYPE_OF_TEST, $course_test;
        if ($TYPE_OF_TEST != $course_test) {
            if ($persent <= 35)
                return 2;
            else if ($persent <= 60)
                return 3;
            else if ($persent <= 80)
                return 4;
            else
                return 5;
        }
        else {
            $levels = array('Начинающий', 'Лучше новичка', 'Средний', 'Хороший', 'Отличный', 'Великолепный');
            if ($_SESSION->selected_type == 'Английский язык')
                $levels = array('Beginner', 'Elementary', 'Pre-Intermediate', 'Intermediate', 'Upper-Intermediate', 'Advanced');
            if ($persent <= 20)
                return $levels[0];
            else if ($persent <= 35)
                return $levels[1];
            else if ($persent <= 50)
                return $levels[2];
            else if ($persent <= 67)
                return $levels[3];
            else if ($persent <= 79)
                return $levels[4];
            else
                return $levels[5];
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
    <title>IT Exam</title>
</head>
<body>
    <script type="text/javascript">
        function Submit_finish() {
            return confirm("Вы действительно хотите закончить тест?");
        }
    </script>
    <?php if ($questions_count == 0): ?>
        <div class="container ooops">
            <form action="./">
                <h1 class="ooops-title">Упс! Для вас тестов пока нет!</h1>
                <h1 class="exit-text">Пожалуйста перейдите на главную страницу)</h1>
                <button class="btn btn-success exit-btn" type="submit">На главную страницу</button>
            </form>
        </div>
    <?php die(0); endif; ?>

    <?php if (!isset($_SESSION['logged_user'])): ?>
        <div class="container ooops">
            <form action="./">
                <h1 class="ooops-title">Упс! Как вы тут оказались?)</h1>
                <h1 class="exit-text">Пожалуйста перейдите на главную страницу и авторизуйтесь)</h1>
                <button class="btn btn-success exit-btn" type="submit">На главную страницу</button>
            </form>
        </div>

    <?php else: ?>
        <?php if ($show_result) : ?>
            <script>sessionStorage.clear();</script>
            <div class="container">
                <form class="result-form" action="./logout.php">
                    <?php if (R::count('results', 'username = ?', array($_SESSION['logged_user']->username)) > 0): ?>
                        <h1 class="passing-again-error" style="font-size: 48px;">Ошибка!</h1><br>
                        <h1 class="passing-again-error">Вы уже проходили тест! Пожалуйста выйдите с системы</h1>
                    <?php else: ?>
                        <?php
                            if ($questions_count != 0)
                                $persentage = round(100 * $score / $questions_count);
                            else 
                                $persentage = 0;
                            $answers = array();
                            for ($i = 1; $i <= $questions_count; $i ++)
                                $answers[] = $data['Q'.$i];
                            $result = R::dispense('results');
                            $result->name = $_SESSION['logged_user']->name;
                            $result->surname = $_SESSION['logged_user']->surname;
                            $result->test_type = $_SESSION['logged_user']->test_type;
                            $result->right_answers = implode('', $right_answers);
                            $result->answers = implode('', $answers);
                            $result->score = $score;
                            $result->persentage = $persentage.'%';
                            $result->mark = get_mark($persentage);
                            $result->username = $_SESSION['logged_user']->username;
                            $result->date = date("d.m.Y H:i:s");
                            R::store($result);  
                        ?>
                        <h1 class="result-title">Тест закончен</h1>
                        <h1 class="result-text">Правильных ответов: <?= $score; ?> из <?= $questions_count; ?></h1>
                        <h1 class="result-text">Тест пройден на: <?= $persentage;?> %</h1>
                        <h1 class="result-text">Ваша оценка: <?= get_mark($persentage); ?></h1><br><br>
                        <h1 class="exit-text">Спасибо что прошли тест, ваши результаты сохранены.</h1>
                        <h1 class="exit-text">Пожалуйста нажмите "Выход" чтобы выйти из системы</h1>
                    <?php endif; ?>
                    <button class="btn btn-success exit-btn" type="submit">Выход</button>
                </form>
            </div>

        <?php else : ?>
            <script src="src/js/timer.js"></script>
            <div class="container-fluid">
                <form action="./exam.php" method="POST" onsubmit="return Submit_finish();">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 info-container fixed-col">
                            <?php if ($TYPE_OF_TEST != $just_test): ?>
                                <div class="info">
                            <?php else: ?>
                                <div class="info" style="margin-top: 20px;">
                            <?php endif; ?>
                                <b>Имя: </b><i><?= $_SESSION['logged_user']->name; ?></i><br>
                                <b>Фамилия: </b><i><?= $_SESSION['logged_user']->surname; ?></i><br>
                                <?php if ($TYPE_OF_TEST != $just_test) :?>
                                    <?php
                                        if ($TYPE_OF_TEST == $school_test)
                                            echo '<b>Класс: </b><i>';
                                        elseif ($TYPE_OF_TEST == $course_test)
                                            echo '<b>Предмет: </b><i>';
                                    ?>
                                    <?= $_SESSION['logged_user']->test_type; ?></i><br>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 timer-container fixed-col">
                            <h1 id="time"></h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 content">
                            <?php
                                $question_counter = 1;
                                $ans_options = array('A', 'B', 'C', 'D');
                                foreach ($questions as $question) {
                                    reset($ans_options);
                                    $index = 'Q'.$question_counter;
                                    echo '<p><b id="'.$index.'">'.$question_counter.'. '.$question->question.'</b></p>';
                                    echo '<p> '.current($ans_options).') '.$question->a.'<br>'.next($ans_options).') '.$question->b.'<br>';
                                    echo next($ans_options).') '.$question->c.'<br>'.next($ans_options).') '.$question->d.'</p>';
                                    reset($ans_options);
                                    for ($i = 0; $i < count($ans_options); $i ++) {
                                        echo '<input type="radio" class="radio" name="'.$index.'" value="'.current($ans_options).'"';
                                        echo (empty($data[$index]) || $data[$index] != current($ans_options)) ? '' : ' checked="checked"';
                                        echo '><font class="font">'.current($ans_options).'</font><br>';
                                        next($ans_options);
                                    }
                                    echo '<br><br>';
                                    $question_counter ++;
                                }
                            ?>
                            <button type="submit" id="bottom_btn" class="btn btn-success finish_btn" name="do_finish">Завершить</button>
                            <?php
                                if ($show_fill_error) {
                                    echo '<script>element = document.getElementById("'.$error_index.'");';
                                    echo 'element.style.color = "#ff0000";</script>';
                                    echo '<script>window.scroll(0, element.offsetTop);</script>';
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
