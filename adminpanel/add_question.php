<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    $show_stored_msg = false;
    if(isset($data['do_add'])){
        if (trim($data['question']) == '')
            $errors[] = 'Поле "Вопрос" не должно быть пустым';
        if (trim($data['answer_a']) == '')
            $errors[] = 'Поле "Вариант ответа A" не должно быть пустым';
        if (trim($data['answer_b']) == '')
            $errors[] = 'Поле "Вариант ответа B" не должно быть пустым';
        if (trim($data['answer_c']) == '')
            $errors[] = 'Поле "Вариант ответа C" не должно быть пустым';
        if (trim($data['answer_d']) == '')
            $errors[] = 'Поле "Вариант ответа D" не должно быть пустым';
        if (trim($data['right_answer']) == '')
            $errors[] = 'Поле "Правильный ответ" не должно быть пустым';
        if (!empty($errors)) {
            $show_errors = true;
            echo '<script>window.location.href = "./add_question.php#error"</script>';
        } else {
            $question = R::dispense('questions');
            $question->question = $data['question'];
            $question->a = $data['answer_a'];
            $question->b = $data['answer_b'];
            $question->c = $data['answer_c'];
            $question->d = $data['answer_d'];
            $question->right_answer = $data['right_answer'];
            R::store($question);
            $show_stored_msg = true;
            $_POST = array();
            $data = $_POST;
            echo '<script>window.location.href = "./add_question.php#success"</script>';
        }
    }

    if (isset($data['do_go_back']))
        header('Location: ./adminpanel.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IT Exam Admin panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.3">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/add_question.css">
</head>
<body>
    <?php if (!isset($_SESSION['logged_admin'])): ?>
        <div class="container ooops">
            <form action="./">
                <h1 class="title">Хей админ, авторизируйся)</h1>
                <button class="btn btn-success exit-btn" type="submit">На главную админку =)</button>
            </form>
        </div>

    <?php else: ?>
        <div class="container">
            <h1 class="add-title">Добавить вопрос</h1>
            <form action="./add_question.php" method="POST">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вопрос: </label>
                            <textarea type="text" class="form-control" name="question"><?php if (trim(@$data['question']) != '') echo @$data['question']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа А: </label>
                            <textarea type="text" class="form-control" name="answer_a"><?php if (trim(@$data['answer_a']) != '') echo @$data['answer_a']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа B: </label>
                            <textarea type="text" class="form-control" name="answer_b"><?php if (trim(@$data['answer_b']) != '') echo @$data['answer_b']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа C: </label>
                            <textarea type="text" class="form-control" name="answer_c"><?php if (trim(@$data['answer_c']) != '') echo @$data['answer_c']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа D: </label>
                            <textarea type="text" class="form-control" name="answer_d"><?php if (trim(@$data['answer_d']) != '') echo @$data['answer_d']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Правильный ответ (буква): </label><br>
                            <select class="right_answer" name="right_answer">
                                <option></option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success add-btn" name="do_add">Добавить вопрос</button>
                <button type="submit" class="btn btn-primary back-btn" name="do_go_back">Назад</button>
            </form>
            <?php 
                if ($show_errors)
                    echo '<h1 id="error">'.array_shift($errors).'</h1>';
                if ($show_stored_msg)
                    echo '<h1 id="success">Вопрос был успешно добавлен</h1>';
            ?>
        </div>
    <?php endif; ?>
</body>
</html>
 
