 <?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    if(isset($data['do_edit'])){
        if (trim($data['question']) == '') {
            $errors[] = 'Поле "Вопрос" не должно быть пустым';
        }
        if (trim($data['answer_a']) == '') {
            $errors[] = 'Поле "Вариант ответа A" не должно быть пустым';
        }
        if (trim($data['answer_b']) == '') {
            $errors[] = 'Поле "Вариант ответа B" не должно быть пустым';
        }
        if (trim($data['answer_c']) == '') {
            $errors[] = 'Поле "Вариант ответа C" не должно быть пустым';
        }
        if (trim($data['answer_d']) == '') {
            $errors[] = 'Поле "Вариант ответа D" не должно быть пустым';
        }
        if (trim($data['right_answer']) == '') {
            $errors[] = 'Поле "Правильный ответ" не должно быть пустым';
        }
        if (!empty($errors)) {
            $show_errors = true;
            echo '<script>window.location.href = "./edit_question.php#error"</script>';
        } else {
        	$question = R::load('questions', $_SESSION['editing_question']->id);
            $question->question = $data['question'];
            $question->a = $data['answer_a'];
            $question->b = $data['answer_b'];
            $question->c = $data['answer_c'];
            $question->d = $data['answer_d'];
            $question->right_answer = $data['right_answer'];
            R::store($question);
            unset($_SESSION['editing_question']);
            header('Location: ./adminpanel.php');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IT Exam Admin panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.3">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/edit_question.css">
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
            <h1 class="edit-title">Изменить вопрос</h1>
            <form action="./edit_question.php" method="POST">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вопрос: </label>
                            <textarea type="text" class="form-control" name="question"><?= @$_SESSION['editing_question']->question ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа А: </label>
                            <textarea type="text" class="form-control" name="answer_a"><?= @$_SESSION['editing_question']->a ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа B: </label>
                            <textarea type="text" class="form-control" name="answer_b"><?= @$_SESSION['editing_question']->b ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа C: </label>
                            <textarea type="text" class="form-control" name="answer_c"><?= @$_SESSION['editing_question']->c ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа D: </label>
                            <textarea type="text" class="form-control" name="answer_d"><?= @$_SESSION['editing_question']->d ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Правильный ответ (буква): </label><br>
                            <select class="right_answer" name="right_answer">
                                <option></option>
                                <option value="A" 
                                <?php if($_SESSION['editing_question']->right_answer == 'A')
                                    echo ' selected="selected"';
                                ?>>A</option>
                                <option value="B" 
                                <?php if($_SESSION['editing_question']->right_answer == 'B')
                                    echo ' selected="selected"';
                                ?>>B</option>
                                <option value="C" 
                                <?php if($_SESSION['editing_question']->right_answer == 'C')
                                    echo ' selected="selected"';
                                ?>>C</option>
                                <option value="D" 
                                <?php if($_SESSION['editing_question']->right_answer == 'D')
                                    echo ' selected="selected"';
                                ?>>D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success edit-btn" name="do_edit">Изменить вопрос</button>
            </form>
            <?php 
                if ($show_errors) {
                    echo '<h1 id="error">'.array_shift($errors).'</h1>';
                }
            ?>
        </div>
    <?php endif; ?>
</body>
</html>
 
