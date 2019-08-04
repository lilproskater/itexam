<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    if(isset($data['do_add'])){
        $answers = array('A', 'B', 'C', 'D');
        if (in_array(strtoupper($data['right_answer']), $answers)) {
            $question = R::dispense('questions');
            $question->question = $data['question'];
            $question->a = $data['answer_a'];
            $question->b = $data['answer_b'];
            $question->c = $data['answer_c'];
            $question->d = $data['answer_d'];
            $question->right_answer = strtoupper($data['right_answer']);
            R::store($question);
            header('Location: ./adminpanel.php');
    	}
        else {
            $errors[] = 'Правильный ответ дан не правильно';
        }
        if (!empty($errors)) {
            $show_errors = true;
            echo '<script>window.location.href = "./add.php#error"</script>';
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
    <link rel="stylesheet" href="../src/css/add.css">
</head>
<body>
<br><br>
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
            <form action="./add.php" method="POST">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вопрос: </label>
                            <textarea type="text" class="form-control" name="question"><?=@$data['question']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа А: </label>
                            <textarea type="text" class="form-control" name="answer_a"><?=@$data['answer_a']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа B: </label>
                            <textarea type="text" class="form-control" name="answer_b"><?=@$data['answer_b']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа C: </label>
                            <textarea type="text" class="form-control" name="answer_c"><?=@$data['answer_c']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Вариант ответа D: </label>
                            <textarea type="text" class="form-control" name="answer_d"><?=@$data['answer_d']?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>Правильный ответ (буква): </label>
                            <input type="text" class="form-control input" maxlength="1" name="right_answer"></input>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success add-btn" name="do_add">Добавить вопрос</button>
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
 
