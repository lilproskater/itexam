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

    if (isset($data['do_clear_questions']))
        R::wipe('questions');
    if (isset($data['do_clear_profiles']))
        R::wipe('profiles');
    if (isset($data['do_clear_results']))
        R::wipe('results');

    if (isset($data['do_logout'])) {
        unset($_SESSION['show_questions']);
        unset($_SESSION['show_profiles']);
        unset($_SESSION['show_results']);
        header('Location: ./');
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
                        <form action="./add_question.php" onsubmit="sessionStorage.clear();">
                            <button type="submit" class="btn btn-success add-btn">Добавить вопрос</button>
                        </form>
                        <form action="./adminpanel.php" method="POST" onsubmit="return Submit_Wipe();">
                            <button type="submit" class="btn btn-danger clear-table-btn" name="do_clear_questions">Очистить таблицу</button>
                        </form>
                    <?php elseif ($show_profiles): ?>
                        <form action="./add_profile.php" onsubmit="sessionStorage.clear();">
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
                    <form action="./adminpanel.php" method="POST">
                        <button type="submit" class="btn btn-success logout-btn" name="do_logout" onclick="sessionStorage.clear();">Выход</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 sidebar">
                    <form action="./adminpanel.php" method="POST">
                        <button class="btn-success btn sidebar-btn" name="do_show_questions" onclick="sessionStorage.clear();">Вопросы</button><br>
                        <button class="btn-success btn sidebar-btn" name="do_show_profiles" onclick="sessionStorage.clear();">Профили</button><br>
                        <button class="btn-success btn sidebar-btn" name="do_show_results" onclick="sessionStorage.clear();">Результаты</button><br>
                    </form>
                </div>
                <div class="col-md-10 col-sm-10 content">
                    <?php if ($show_questions): ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>id</th>
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
                            $questions = R::findAll('questions');
                            foreach ($questions as $question) {
                                echo '<tr>';
                                echo '<td>'.$question->id.'</td>';
                                echo '<td>'.$question->question.'</td>';
                                echo '<td>'.$question->a.'</td>';
                                echo '<td>'.$question->b.'</td>';
                                echo '<td>'.$question->c.'</td>';
                                echo '<td>'.$question->d.'</td>';
                                echo '<td>'.$question->right_answer.'</td>';
                                echo '<form action="./adminpanel.php" method="POST">';
                                echo '<td><button class="btn btn-success" name="do_edit_question'.$question->id.'">Изменить</button></td>';
                                echo '</form>';
                                echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del_Question();">';
                                echo '<td><button class="btn btn-danger" name="do_del_question'.$question->id.'">Удалить</button></td>';
                                echo '</form>';
                                echo '</tr>';
                            }
                        ?>
                            </tbody>
                        </table>
                    <?php elseif ($show_profiles): ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Имя</th>
                                    <th>Фамилия</th>
                                    <th>Класс</th>
                                    <th>Логин</th>
                                    <th>Пароль</th>
                                    <th>Время регистрации</th>
                                    <th>Изменение профиля</th>
                                    <th>Удаление профиля</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                            $profiles = R::findAll('profiles');
                            foreach ($profiles as $profile) {
                                echo '<tr>';
                                echo '<td>'.$profile->id.'</td>';
                                echo '<td>'.$profile->name.'</td>';
                                echo '<td>'.$profile->surname.'</td>';
                                echo '<td>'.$profile->grade.$profile->letter.'</td>';
                                echo '<td>'.$profile->username.'</td>';
                                echo '<td>'.$profile->password.'</td>';
                                echo '<td>'.$profile->date.'</td>';
                                echo '<form action="./adminpanel.php" method="POST">';
                                echo '<td><button class="btn btn-success" name="do_edit_profile'.$profile->id.'">Изменить</button></td>';
                                echo '</form>';
                                echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del_Profile();">';
                                echo '<td><button class="btn btn-danger" name="do_del_profile'.$profile->id.'">Удалить</button></td>';
                                echo '</form>';
                                echo '</tr>';
                            }
                        ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Имя</th>
                                    <th>Фамилия</th>
                                    <th>Класс</th>
                                    <th>Ответы пользователя</th>
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
                            $results = R::findAll('results');
                            foreach ($results as $result) {
                                echo '<tr>';
                                echo '<td>'.$result->id.'</td>';
                                echo '<td>'.$result->name.'</td>';
                                echo '<td>'.$result->surname.'</td>';
                                echo '<td>'.$result->grade.$result->letter.'</td>';
                                $all_right_answers = array();
                                $questions = R::findAll('questions');
                                foreach ($questions as $question) {
                                    $all_right_answers[] = $question->right_answer;
                                }
                                $all_right_answers = implode('', $all_right_answers);
                                echo '<td>';
                                for ($i = 0; $i < strlen($result->answers); $i ++)
                                    echo ($i + 1).') '.$result->answers[$i].' '.(($i < strlen($all_right_answers)) ? @$all_right_answers[$i] : 'NULL').' '.(($result->answers[$i] == @$all_right_answers[$i]) ? '✓' : '✖').'<br>';
                                echo '</td>';
                                echo '<td>'.$result->right_answers.'</td>';
                                echo '<td>'.$result->persentage.'</td>';
                                echo '<td>'.$result->mark.'</td>';
                                echo '<td>'.$result->username.'</td>';
                                echo '<td>'.$result->date.'</td>';
                                echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del_Result();">';
                                echo '<td><button class="btn btn-danger" name="do_del_result'.$result->id.'">Удалить</button></td>';
                                echo '</form>';
                                echo '</tr>';
                            }
                        ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
