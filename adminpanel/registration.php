<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    $invalid_token = false;
    if (isset($data['do_signup'])) {
        if (strpos($data['name'], ' ') !== false)
            $errors[] = 'Поле "Имя" не должно содержать пробелов';
        if (strpos($data['surname'], ' ') !== false)
            $errors[] = 'Поле "Фамилия" не должно содержать пробелов';
        if (strpos($data['username'], ' ') !== false)
            $errors[] = 'Поле "Логин" не должно содержать пробелов';
        if ($data['confirm_password'] != $data['password'])
            $errors[] = 'Пароли не совпадают';
        if (empty($errors) && $data['token'] != 'VG9rZW4=')
            $invalid_token = true;
        if (R::count('admins', 'username = ?', array(strtolower($data['username']))) > 0)
            $errors[] = 'Администратор с данным логином уже существует';
        if (empty($errors) && !$invalid_token) {
            $admin = R::dispense('admins');
            $admin->name = mb_convert_case(mb_strtolower($data['name']), MB_CASE_TITLE, "UTF-8");
            $admin->surname = mb_convert_case(mb_strtolower($data['surname']), MB_CASE_TITLE, "UTF-8");
            $admin->username = mb_strtolower($data['username']);
            $admin->password = $data['password'];
            $admin->date = date("d.m.Y H:i:s");
            R::store($admin);
            header('Location: ./index.php');
        }
        else {
            $show_errors = true;
            echo '<script>window.location.href = "./registration.php#error"</script>';
        }
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/registration.css">
    <title>IT Exam Admin Panel</title>
</head>
<body>
    <div class="container">
        <h1>Админ регистрация</h1>
        <form action="./registration.php" method="POST">
            <div class="input-container">
                <input type="text" pattern="^[А-яЁё]+|[A-z]+$" class="form-control input" name="name" placeholder="Имя" required title="Используйте только кирилицу или латынь без цифр и спецсимволов" value="<?=@$data['name']?>">
            </div>
            <div class="input-container">
                <input type="text" pattern="^[А-яЁё]+|[A-z]+$" class="form-control input" name="surname" placeholder="Фамилия" required title="Используйте только кирилицу или латынь без цифр и спецсимволов" value="<?=@$data['surname']?>">
            </div>
            <div class="input-container">
                <input type="text" pattern="^[A-z0-9_]{3,16}$" class="form-control input" name="username" placeholder="Имя пользователя" required title="Используйте только символы A-z, 0-9, и _. Минимальная длина: 3; Максимальная: 16" value="<?=@$data['username']?>">
            </div>
            <div class="input-container">
                <input type="password" pattern="^[A-z0-9!@#$%^&*()-_+=;:,./?\|`~{}]{6,}$" class="form-control input" name="password" placeholder="Пароль" required title="Используйте только буквы (a–z, A–Z), цифры и символы ! @ # $ % ^ & * ( ) - _ + = ; : , . / ? \ | ` ~ { }. Минимальная длина: 6" value="<?=@$data['password']?>">
            </div>
            <div class="input-container">
                <input type="password" class="form-control input" name="confirm_password" placeholder="Повторный пароль" required value="<?php if (@$data['confirm_password'] == @$data['password']) echo @$data['password']?>">
            </div>
            <div class="input-container">
                <input type="text" class="form-control input" name="token" placeholder="Токен подлинности" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary form-control button" name="do_signup" onclick="return Validate_form();">Зарегистрировать админа</button>
            </div>
        </form><br>
        <?php 
            if ($invalid_token) {
                echo '<script>';
                echo 'alert("Не правильный токен!")';
                echo '</script>';
            }  
            if ($show_errors)
                echo '<h1 id="error">'.array_shift($errors).'</h1>';
        ?>
    </div>
</body>
</html>
