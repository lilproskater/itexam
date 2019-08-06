<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    if (isset($data['do_edit'])) {
        if ($data['name'] == '') {
            $errors[] = 'Заполните поле "Имя"';
        }
        if ($data['surname'] == '') {
            $errors[] = 'Заполните поле "Фамилия"';
        }
        if ($data['grade'] == '') {
        	$errors[] = 'Выберите класс';
        }
        if ($data['letter'] == '') {
        	$errors[] = 'Выберите букву класса';
        }
        if ($data['username'] == '') {
            $errors[] = 'Заполните поле "Логин"';
        }
        if ($data['password'] == '') {
            $errors[] = 'Заполните поле "Пароль"';
        }
        if (strpos($data['name'], ' ') !== false) {
            $errors[] = 'Поле "Имя" не должно содержать пробелов';
        }
        if (strpos($data['surname'], ' ') !== false) {
            $errors[] = 'Поле "Фамилия" не должно содержать пробелов';
        }
        if (strpos($data['username'], ' ') !== false) {
            $errors[] = 'Поле "Логин" не должно содержать пробелов';
        }
        if ($data['confirm_password'] != $data['password']) {
            $errors[] = 'Пароли не совпадают';
        }

        if (empty($errors)) {
            $user = R::load('profiles', $_SESSION['editing_profile']->id);
            $user->name = ucfirst(strtolower($data['name']));
            $user->surname = ucfirst(strtolower($data['surname']));
            $user->grade = $data['grade'];
            $user->letter = $data['letter'];
            $user->username = strtolower($data['username']);
            $user->password = $data['password'];
            $user->date = date("d.m.Y H:i:s");
            R::store($user);
            unset($_SESSION['editing_profile']);
            header('Location: ./adminpanel.php');
        }
        else {
            $show_errors = true;
            echo '<script>window.location.href = "./edit_profile.php#error"</script>';
        }
    }
    if (isset($data['do_go_back'])) {
        header('Location: ./adminpanel.php');
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/edit_profile.css">
    <title>IT Exam Admin panel</title>
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
        <div class="container" style="max-width: 600px; min-width: 550px;">
            <h1>Изменить профиль</h1>
            <form action="./edit_profile.php" method="POST">
                <div class="input-container">
                    <input type="text" class="form-control input" name="name" placeholder="Имя" value="<?= @$_SESSION['editing_profile']->name ?>">
                </div>
                <div class="input-container">
                    <input type="text" class="form-control input" name="surname" placeholder="Фамилия" value="<?= @$_SESSION['editing_profile']->surname ?>">
                </div>
                <font class="grade-txt">Класс:</font>
                <select class="grade" name="grade">
                    <option></option>
                    <option value="11"
                    <?php if($_SESSION['editing_profile']->grade == '11') 
                              echo ' selected="selected"';
                    ?>>11</option>
                    <option value="10"
                    <?php if($_SESSION['editing_profile']->grade == '10') 
                              echo ' selected="selected"';
                    ?>>10</option>
                    <option value="9"
                    <?php if($_SESSION['editing_profile']->grade == '9') 
                              echo ' selected="selected"';
                    ?>>9</option>
                    <option value="8"
                    <?php if($_SESSION['editing_profile']->grade == '8') 
                              echo ' selected="selected"';
                    ?>>8</option>
                </select>
                <select class="letter" name="letter">
                    <option></option>
                    <option value="А"
                    <?php if($_SESSION['editing_profile']->letter == 'А') 
                              echo ' selected="selected"';
                    ?>>А</option>
                    <option value="Б"
                    <?php if($_SESSION['editing_profile']->letter == 'Б') 
                              echo ' selected="selected"';
                    ?>>Б</option>
                    <option value="В"
                    <?php if($_SESSION['editing_profile']->letter == 'В') 
                              echo ' selected="selected"';
                    ?>>В</option>
                    <option value="Г"
                    <?php if($_SESSION['editing_profile']->letter == 'Г') 
                              echo ' selected="selected"';
                    ?>>Г</option>
                    <option value="Д"
                    <?php if($_SESSION['editing_profile']->letter == 'Д') 
                              echo ' selected="selected"';
                    ?>>Д</option>
                    <option value="Е"
                    <?php if($_SESSION['editing_profile']->letter == 'Е') 
                              echo ' selected="selected"';
                    ?>>Е</option>
                </select>
                <div class="input-container">
                    <input type="text" class="form-control input" name="username" placeholder="Имя пользователя" value="<?= @$_SESSION['editing_profile']->name ?>">
                </div>
                <div class="input-container">
                    <input type="text" class="form-control input" name="password" placeholder="Пароль" value="<?= @$_SESSION['editing_profile']->password ?>">
                </div>
                <div class="input-container">
                    <input type="text" class="form-control input" name="confirm_password" placeholder="Повторный пароль" value="<?= @$_SESSION['editing_profile']->password ?>">
                </div>
                <button type="submit" class="btn btn-success form-control edit-btn" name="do_edit">Изменить профиль</button>
                <button type="submit" class="btn btn-primary form-control back-btn" name="do_go_back">Назад</button>
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
