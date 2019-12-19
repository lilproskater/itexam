<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    if (!isset($_SESSION['show_questions']))
        $_SESSION['show_questions'] = true;
    if (!isset($_SESSION['show_profiles']))
        $_SESSION['show_profiles'] = false;
    if (!isset($_SESSION['show_results']))
        $_SESSION['show_results'] = false;

    if (isset($data['do_show_questions'])) {
        $_SESSION['show_questions'] = true;
        $_SESSION['show_profiles'] = false;
        $_SESSION['show_results'] = false;
    }
    if (isset($data['do_show_profiles'])) {
        $_SESSION['show_questions'] = false;
        $_SESSION['show_profiles'] = true;
        $_SESSION['show_results'] = false;
    }
    if (isset($data['do_show_results'])) {
        $_SESSION['show_questions'] = false;
        $_SESSION['show_profiles'] = false;
        $_SESSION['show_results'] = true;
    }
    $show_questions = $_SESSION['show_questions'];
    $show_profiles = $_SESSION['show_profiles'];
    $show_results = $_SESSION['show_results'];

    $questions = R::findAll('questions');
    $profiles = R::findAll('profiles');
    $results = R::findAll('results');
    foreach ($questions as $question) {
        if (isset($data['do_del_question'.$question->id])) {
            $del_bean = R::load('questions', $question->id);
            R::trash($del_bean);
        }
        if (isset($data['do_edit_question'.$question->id])) {
            $_SESSION['editing_question'] = $question;
            header('Location: ./edit_question.php');
        }
    }
    foreach ($profiles as $profile) {
        if (isset($data['do_del_profile'.$profile->id])) {
            $del_bean = R::load('profiles', $profile->id);
            R::trash($del_bean);
        }
        if (isset($data['do_edit_profile'.$profile->id])) {
            $_SESSION['editing_profile'] = $profile;
            header('Location: ./edit_profile.php');
        }
    }
    foreach ($results as $result) {
        if (isset($data['do_del_result'.$result->id])) {
            $del_bean = R::load('results', $result->id);
            R::trash($del_bean);
        }
    }
    if (isset($data['selected_type']))
        $_SESSION['selected_type'] = $data['selected_type'];
    if (!isset($_SESSION['selected_type']))
        $_SESSION['selected_type'] = $TYPE_OF_TEST[0];
    if (!in_array($_SESSION['selected_type'], $TYPE_OF_TEST)) {
        // if TEST_TYPE has been changed, unset $_POST($data) values that can delete or shuffle other data on page reload with POST submit
        unset($data['do_clear_questions']);
        unset($data['do_clear_profiles']);
        unset($data['do_clear_results']);
        unset($data['do_shuffle_answers']);
        $_SESSION['selected_type'] = $TYPE_OF_TEST[0];
    }
    if (isset($data['do_shuffle_answers'])) {
        $questions = R::getAll('SELECT * FROM questions WHERE test_type=?', array($_SESSION['selected_type']));
        foreach ($questions as $question) {
            $answers_arr = [];
            $answers_arr[] = $question['a'];
            $answers_arr[] = $question['b'];
            $answers_arr[] = $question['c'];
            $answers_arr[] = $question['d'];
            $key = $question['right_answer'];
            $key = $answers_arr[array_search($key, array('A', 'B', 'C','D'))];
            shuffle($answers_arr);
            $key = array_search($key, $answers_arr);
            $answers_list = ["A", "B", "C", "D"];
            $key = $answers_list[$key];
            $changing_question = R::findOne('questions', 'id=?', array($question['id']));
            $changing_question->a = $answers_arr[0];
            $changing_question->b = $answers_arr[1];
            $changing_question->c = $answers_arr[2];
            $changing_question->d = $answers_arr[3];
            $changing_question->right_answer = $key;
            R::store($changing_question);
        }
    }
    if (isset($data['do_clear_questions'])) {
        $del_questions = R::findAll('questions', 'test_type=?', array($_SESSION['selected_type']));
        foreach ($del_questions as $del_question)
            R::trash(R::load('questions', $del_question->id));
    }
    if (isset($data['do_clear_profiles'])) {
        $del_profiles = R::findAll('profiles', 'test_type=?', array($_SESSION['selected_type']));
        foreach ($del_profiles as $del_profile)
            R::trash(R::load('profiles', $del_profile->id));
    }
    if (isset($data['do_clear_results'])) {
        $del_results = R::findAll('results', 'test_type=?', array($_SESSION['selected_type']));
        foreach ($del_results as $del_result)
            R::trash(R::load('results', $del_result->id));
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.1">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/adminpanel.css">
    <title>IT Exam Admin panel</title>
</head>
<body>
    <script type="text/javascript">
        window.onload = function () {
            window.scroll(0, sessionStorage.getItem('scrollPosition'));
        }
        window.addEventListener('scroll', function() {
            sessionStorage.setItem("scrollPosition", pageYOffset);
        });
        function Submit_Del_Question() {
            return confirm("Вы действительно хотите удалить вопрос?");
        }
        function Submit_Del_Profile() {
            return confirm("Вы действительно хотите удалить профиль?");
        }
        function Submit_Del_Result() {
            return confirm("Вы действительно хотите удалить результат?");
        }
        function Submit_Wipe() {
            return confirm("Вы действительно хотите очистить таблицу?");
        }
        function Submit_Shuffle() {
            return confirm("Вы действительно хотите перемешать ответы на вопросы?");
        }
    </script>
    <?php if (!isset($_SESSION['logged_admin'])): ?>
        <div class="container ooops">
            <form action="./">
                <h1 class="title">Хей админ, авторизируйся)</h1>
                <button class="btn btn-success exit-btn" type="submit">На главную админку =)</button>
            </form>
        </div>

    <?php else: ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 header-info">
                    <div class="inner-info">
                        <h1 class="header-title">Админ панель</h1>
                        <font class="admin-uname-text"><?= ucfirst(strtolower($_SESSION['logged_admin']->username)) ?></font>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 header-right">
                    <?php if ($show_questions): ?>
                        <form action="./add_question.php" onsubmit="sessionStorage.setItem('scrollPosition', 0);">
                            <button type="submit" class="btn btn-success add-btn">Добавить вопрос</button>
                        </form>
                        <form action="./adminpanel.php" method="POST" onsubmit="return Submit_Wipe();">
                            <button type="submit" class="btn btn-danger clear-table-btn" name="do_clear_questions">Очистить таблицу</button>
                        </form>
                        <form action="./adminpanel.php" method="POST" onsubmit="return Submit_Shuffle();">
                            <button type="submit" class="btn btn-success shuffle-btn" name="do_shuffle_answers">Перемешать ответы</button>
                        </form>
                    <?php elseif ($show_profiles): ?>
                        <form action="./add_profile.php" onsubmit="sessionStorage.setItem('scrollPosition', 0);">
                            <button type="submit" class="btn btn-success add-btn">Добавить профиль</button>
                        </form>
                        <form action="./adminpanel.php" method="POST" onsubmit="return Submit_Wipe();">
                            <button type="submit" class="btn btn-danger clear-table-btn" name="do_clear_profiles">Очистить таблицу</button>
                        </form>
                    <?php else: ?>
                        <form action="./adminpanel.php" method="POST" onsubmit="return Submit_Wipe();">
                            <button type="submit" class="btn btn-danger clear-table-btn" name="do_clear_results" style="right: 210px;">Очистить таблицу</button>
                        </form>
                    <?php endif; ?>
                    <form action="./logout.php" method="POST">
                        <button type="submit" class="btn btn-success logout-btn" name="do_logout" onclick="sessionStorage.setItem('scrollPosition', 0);">Выход</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 sidebar">
                    <form action="./adminpanel.php" method="POST">
                        <button class="btn-success btn sidebar-btn" name="do_show_questions" onclick="sessionStorage.setItem('scrollPosition', 0);">Вопросы</button><br>
                        <button class="btn-success btn sidebar-btn" name="do_show_profiles" onclick="sessionStorage.setItem('scrollPosition', 0);">Профили</button><br>
                        <button class="btn-success btn sidebar-btn" name="do_show_results" onclick="sessionStorage.setItem('scrollPosition', 0);">Результаты</button><br>
                    </form>
                    <?php if ($TYPE_OF_TEST != $just_test): ?>
                        <?php if ($TYPE_OF_TEST == $school_test): ?>
                            <h4 style="margin-top: 15px; color: #000000; text-align: center;">
                                Класс:
                            </h4>
                        <?php elseif ($TYPE_OF_TEST == $course_test): ?>
                            <h4 style="margin-top: 15px; color: #000000; text-align: center;">
                                Предмет:
                            </h4>
                        <?php endif; ?>
                        <form action="./adminpanel.php" method="POST">
                            <select class="btn-success grade-select subject-select" name="selected_type" onchange="this.form.submit();">
                                <?php
                                    if ($TYPE_OF_TEST == $school_test) {
                                        foreach ($school_test as $grade) {
                                            echo '<option value="'.$grade.'"';
                                            if (explode(' ', $_SESSION['selected_type'])[0] == strval($grade))
                                                echo ' selected="selected"';
                                            echo '>'.$grade.'</option>';
                                        }
                                    }
                                    elseif ($TYPE_OF_TEST == $course_test) {
                                        foreach ($course_test as $subject) {
                                            echo '<option value="'.$subject.'"';
                                            if ($_SESSION['selected_type'] == $subject)
                                                echo ' selected="selected"';
                                            echo '>'.$subject.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="col-md-10 col-sm-10 content">
                    <?php if ($show_questions): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Вопрос</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                        <th>Правильный ответ</th>
                                        <th>Изменение вопроса</th>
                                        <th>Удаление вопроса</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                                $questions = R::findAll('questions', 'test_type=?', array($_SESSION['selected_type']));
                                $counter = 1; 
                                foreach ($questions as $question) {
                                    echo '<tr>';
                                    echo '<td>'.$counter.'</td>';
                                    echo '<td>'.$question['question'].'</td>';
                                    echo '<td>'.$question['a'].'</td>';
                                    echo '<td>'.$question['b'].'</td>';
                                    echo '<td>'.$question['c'].'</td>';
                                    echo '<td>'.$question['d'].'</td>';
                                    echo '<td>'.$question['right_answer'].'</td>';
                                    echo '<form action="./adminpanel.php" method="POST">';
                                    echo '<td><button class="btn btn-success" name="do_edit_question'.$question['id'].'">Изменить</button></td>';
                                    echo '</form>';
                                    echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del_Question();">';
                                    echo '<td><button class="btn btn-danger" name="do_del_question'.$question['id'].'">Удалить</button></td>';
                                    echo '</form>';
                                    echo '</tr>';
                                    $counter ++;
                                }
                            ?>
                                </tbody>
                            </table>
                        </div>
                    <?php elseif ($show_profiles): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Имя</th>
                                        <th>Фамилия</th>
                                        <?php 
                                            if ($TYPE_OF_TEST == $school_test)
                                                echo "<th>Класс</th>";
                                        ?>
                                        <th>Логин</th>
                                        <th>Пароль</th>
                                        <th>Время регистрации</th>
                                        <th>Изменение профиля</th>
                                        <th>Удаление профиля</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                                $profiles = R::findAll('profiles', 'test_type=?', array($_SESSION['selected_type']));
                                $counter = 1; 
                                foreach ($profiles as $profile) {
                                    echo '<tr>';
                                    echo '<td>'.$counter.'</td>';
                                    echo '<td>'.$profile['name'].'</td>';
                                    echo '<td>'.$profile['surname'].'</td>';
                                    if ($TYPE_OF_TEST == $school_test)
                                        echo '<td>'.$profile['test_type'].'</td>';
                                    echo '<td>'.$profile['username'].'</td>';
                                    echo '<td>'.$profile['password'].'</td>';
                                    echo '<td>'.$profile['date'].'</td>';
                                    echo '<form action="./adminpanel.php" method="POST">';
                                    echo '<td><button class="btn btn-success" name="do_edit_profile'.$profile['id'].'">Изменить</button></td>';
                                    echo '</form>';
                                    echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del_Profile();">';
                                    echo '<td><button class="btn btn-danger" name="do_del_profile'.$profile['id'].'">Удалить</button></td>';
                                    echo '</form>';
                                    echo '</tr>';
                                    $counter ++;
                                }
                            ?>
                                </tbody>
                            </table>
                        </div>    
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Имя</th>
                                        <th>Фамилия</th>
                                        <?php 
                                            if ($TYPE_OF_TEST == $school_test)
                                                echo "<th>Класс</th>";
                                        ?>
                                        <th class="answers">Ответы</th>
                                        <th>Правильных ответов</th>
                                        <th>Проценты</th>
                                        <th>Оценка</th>
                                        <th>Логин</th>
                                        <th>Время сдачи</th>
                                        <th>Удалить резултат</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                                $results = R::findAll('results', 'test_type=?', array($_SESSION['selected_type']));
                                $counter = 1;
                                foreach ($results as $result) {
                                    echo '<tr>';
                                    echo '<td>'.$counter.'</td>';
                                    echo '<td>'.$result['name'].'</td>';
                                    echo '<td>'.$result['surname'].'</td>';
                                    $right_answers = str_split($result->right_answers);
                                    if ($TYPE_OF_TEST == $school_test)
                                        echo '<td>'.$result->test_type.'</td>';
                                    echo '<td class="answers">';
                                    for ($i = 0; $i < strlen($result->answers); $i ++) {
                                        if ($result->answers[$i] == $right_answers[$i]) $symbol = '✔';
                                        else $symbol = '✘';
                                        echo ($i + 1).') '.$right_answers[$i].' '.$result->answers[$i].' '.$symbol.'<br>';
                                    }
                                    echo '</td>';
                                    echo '<td>'.$result->score.'</td>';
                                    echo '<td>'.$result->persentage.'</td>';
                                    echo '<td>'.$result->mark.'</td>';
                                    echo '<td>'.$result->username.'</td>';
                                    echo '<td>'.$result->date.'</td>';
                                    echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del_Result();">';
                                    echo '<td><button class="btn btn-danger" name="do_del_result'.$result->id.'">Удалить</button></td>';
                                    echo '</form>';
                                    echo '</tr>';
                                    $counter ++;
                                }
                            ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
