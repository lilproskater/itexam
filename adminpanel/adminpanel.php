<?php 
    require __DIR__.'/../config.php';
    $data = $_POST;
    $show_questions = true;
    $show_profiles = false;
    $show_results = false;
    if (isset($data['do_show_questions'])) {
        $show_questions = true;
        echo '<script>sessionStorage.clear();</script>';
    }
    if (isset($data['do_show_profiles'])) {
        $show_profiles = true;
        $show_questions = false;
    }
    if (isset($data['do_show_results'])) {
        $show_results = true;
        $show_questions = false;
    }
    if (isset($data['do_clear_table'])) {
        R::wipe('questions');
    }
    $questions = R::findAll('questions');
    foreach ($questions as $question) {
        if (isset($data['do_del_q'.$question->id])) {
            $del_bean = R::load('questions', $question->id);
            R::trash($del_bean);
        }
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
        function Submit_Del() {
            return confirm("Вы действительно хотите удалить вопрос?");
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
                        <form action="./add.php" onsubmit="sessionStorage.clear();">
                            <button type="submit" class="btn btn-success add-btn">Добавить вопрос</button>
                        </form>
                        <form action="./adminpanel.php" method="POST" onsubmit="return Submit_Wipe();">
                            <button type="submit" class="btn btn-danger clear-table-btn" name="do_clear_table">Очистить таблицу</button>
                        </form>
                    <?php endif; ?>
                    <form action="./logout.php" onsubmit="sessionStorage.clear();">
                        <button type="submit" class="btn btn-success logout-btn">Выход</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 sidebar">
                    <form action="./adminpanel.php" method="POST">
                        <button class="btn-success btn sidebar-btn" name="do_show_questions">Вопросы</button><br>
                        <button class="btn-success btn sidebar-btn" name="do_show_profiles">Профили</button><br>
                        <button class="btn-success btn sidebar-btn" name="do_show_results">Результаты</button><br>
                    </form>
                </div>
                <div class="col-md-10 col-sm-10 content">
                    <?php if ($show_questions): ?>
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
                            $questions = R::findAll('questions');
                            foreach ($questions as $question) {
                                echo '<tr>';
                                echo '<td id="q'.$question->id.'">'.$question->id.'</td>';
                                echo '<td>'.$question->question.'</td>';
                                echo '<td>'.$question->a.'</td>';
                                echo '<td>'.$question->b.'</td>';
                                echo '<td>'.$question->c.'</td>';
                                echo '<td>'.$question->d.'</td>';
                                echo '<td>'.$question->right_answer.'</td>';
                                echo '<td><button class="btn btn-success">Изменить</button></td>';
                                echo '<form action="./adminpanel.php" method="POST" onsubmit="return Submit_Del();">';
                                echo '<td><button class="btn btn-danger" name="do_del_q'.$question->id.'">Удалить</button></td>';
                                echo '</form>';
                                echo '</tr>';
                            }
                        ?>
                            </tbody>
                        </table>
                    <?php elseif ($show_profiles): ?>
                        Show Profiles
                    <?php else: ?>
                        Show Results
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
