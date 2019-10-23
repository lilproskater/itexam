<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    $show_stored_msg = false;
    if (isset($data['do_add'])) {
        if ($data['name'] == '')
            $errors[] = 'Заполните поле "Имя"';
        if ($data['surname'] == '')
            $errors[] = 'Заполните поле "Фамилия"';
        if ($data['grade'] == '')
        	$errors[] = 'Выберите класс';
        if ($data['letter'] == '')
        	$errors[] = 'Выберите букву класса';
        if ($data['username'] == '')
            $errors[] = 'Заполните поле "Логин"';
        if ($data['password'] == '')
            $errors[] = 'Заполните поле "Пароль"';
        if (strpos($data['name'], ' ') !== false)
            $errors[] = 'Поле "Имя" не должно содержать пробелов';
        if (strpos($data['surname'], ' ') !== false)
            $errors[] = 'Поле "Фамилия" не должно содержать пробелов';
        if (strpos($data['username'], ' ') !== false)
            $errors[] = 'Поле "Логин" не должно содержать пробелов';
        if ($data['confirm_password'] != $data['password'])
            $errors[] = 'Пароли не совпадают';

        if (R::count('profiles', 'username = ?', array(strtolower($data['username']))) > 0)
            $errors[] = 'Профиль с данным логином уже существует';

        if (empty($errors)) {
            $user = R::dispense('profiles');
            $user->name = mb_convert_case(mb_strtolower($data['name']), MB_CASE_TITLE, "UTF-8");
            $user->surname = mb_convert_case(mb_strtolower($data['surname']), MB_CASE_TITLE, "UTF-8");
            $user->grade = $data['grade'];
            $user->letter = $data['letter'];
            $user->username = mb_strtolower($data['username']);
            $user->password = $data['password'];
            $user->date = date("d.m.Y H:i:s");
            R::store($user);
            $show_stored_msg = true;
            $_POST = array();
            $data = $_POST;
            echo '<script>window.location.href = "./add_profile.php#success"</script>';
        }
        else {
            $show_errors = true;
            echo '<script>window.location.href = "./add_profile.php#error"</script>';
        }
    }
    if (isset($data['do_go_back']))
        header('Location: ./adminpanel.php');
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/add_profile.css">
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
            <h1>Добавить профиль</h1>
            <form action="./add_profile.php" method="POST">
                <div class="input-container">
                    <input type="text" pattern="^[А-яЁё]+|[A-z]+$" class="form-control input" name="name" placeholder="Имя" title="Используйте только кирилицу или латынь без цифр и спецсимволов" value="<?php if (strpos(@$data['name'], ' ') == false) echo @$data['name']?>">
                </div>
                <div class="input-container">
                    <input type="text" pattern="^[А-яЁё]+|[A-z]+$" class="form-control input" name="surname" placeholder="Фамилия" title="Используйте только кирилицу или латынь без цифр и спецсимволов" value="<?php if (strpos(@$data['surname'], ' ') == false) echo @$data['surname']?>">
                </div>
                <font class="grade-txt">Класс:</font>
                <select class="grade" name="grade">
                    <option></option>
                    <option value="11"
                    <?php if(isset($data['grade']) && $data['grade'] == '11') 
                              echo ' selected="selected"';
                    ?>>11</option>
                    <option value="10"
                    <?php if(isset($data['grade']) && $data['grade'] == '10') 
                              echo ' selected="selected"';
                    ?>>10</option>
                    <option value="9"
                    <?php if(isset($data['grade']) && $data['grade'] == '9') 
                              echo ' selected="selected"';
                    ?>>9</option>
                    <option value="8"
                    <?php if(isset($data['grade']) && $data['grade'] == '8') 
                              echo ' selected="selected"';
                    ?>>8</option>
                </select>
                <select class="letter" name="letter">
                    <option></option>
                    <option value="А"
                    <?php if(isset($data['letter']) && $data['letter'] == 'А') 
                              echo ' selected="selected"';
                    ?>>А</option>
                    <option value="Б"
                    <?php if(isset($data['letter']) && $data['letter'] == 'Б') 
                              echo ' selected="selected"';
                    ?>>Б</option>
                    <option value="В"
                    <?php if(isset($data['letter']) && $data['letter'] == 'В') 
                              echo ' selected="selected"';
                    ?>>В</option>
                    <option value="Г"
                    <?php if(isset($data['letter']) && $data['letter'] == 'Г') 
                              echo ' selected="selected"';
                    ?>>Г</option>
                    <option value="Д"
                    <?php if(isset($data['letter']) && $data['letter'] == 'Д') 
                              echo ' selected="selected"';
                    ?>>Д</option>
                    <option value="Е"
                    <?php if(isset($data['letter']) && $data['letter'] == 'Е') 
                              echo ' selected="selected"';
                    ?>>Е</option>
                </select>
                <div class="input-container">
                    <input type="text" pattern="^[A-z0-9_]{3,16}$" class="form-control input" name="username" placeholder="Имя пользователя" title="Используйте только символы A-z, 0-9, и _. Минимальная длина: 3; Максимальная: 16" value="<?php if (strpos(@$data['username'], ' ') == false) echo @$data['username']?>">
                </div>
                <div class="input-container">
                    <input type="text" pattern="^[A-z0-9!@#$%^&*()-_+=;:,./?\|`~{}]{6,}$" class="form-control input" name="password" placeholder="Пароль" title="Используйте только буквы (a–z, A–Z), цифры и символы ! @ # $ % ^ & * ( ) - _ + = ; : , . / ? \ | ` ~ { }. Минимальная длина: 6" value="<?php if (strpos(@$data['password'], ' ') == false) echo @$data['password']?>">
                </div>
                <div class="input-container">
                    <input type="text" class="form-control input" name="confirm_password" placeholder="Повторный пароль" value="<?php if (@$data['confirm_password'] == @$data['password']) echo @$data['password']?>">
                </div>
                <button type="submit" class="btn btn-success form-control add-btn" name="do_add" onclick="return Validate_form();">Добавить профиль</button>
                <button type="submit" class="btn btn-primary form-control back-btn" name="do_go_back">Назад</button>
            </form>
            <?php 
                if ($show_errors)
                    echo '<h1 id="error">'.array_shift($errors).'</h1>';
                if ($show_stored_msg)
                    echo '<h1 id="success">Профиль был успешно добавлен</h1>';
            ?>
        </div>
    <?php endif; ?>
</body>
</html>
