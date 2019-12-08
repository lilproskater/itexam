<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    $invalid_token = false;
    $regex_name_surname = "^([А-яЁё]+|[A-z]+)$";
    $regex_username = "^[A-z0-9_]{3,16}$";
    $regex_password = "^[A-z0-9!@#$%^&*()-_+=;:,.?\|`~{}]{6,}$";
    $regex_errors = array(
        "Используйте только кирилицу или латынь без цифр и спецсимволов",
        "Используйте только символы A-z, 0-9, и _. Минимальная длина: 3; Максимальная: 16",
        "Используйте только буквы (a–z, A–Z), цифры и символы ! @ # $ % ^ & * ( ) - _ + = ; : , . / ? \ | ` ~ { }. Минимальная длина: 6",
    );

    if (isset($data['do_signup'])) {
        if ($data['name'] == '')
            $errors[] = 'Заполните поле "Имя"';
        if ($data['surname'] == '')
            $errors[] = 'Заполните поле "Фамилия"';
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
        if (!preg_match('/'.$regex_name_surname.'/u', $data['name'])) {
            $errors[] = 'Поле "Имя: "'.$regex_errors[0];
        }
        if (!preg_match('/'.$regex_name_surname.'/u', $data['surname'])) {
            $errors[] = 'Поле "Фамилия: "'.$regex_errors[0];
        }
        if (!preg_match('/'.$regex_username.'/u', $data['username'])) {
            $errors[] = 'Поле "Логин: "'.$regex_errors[1];
        }
        if (!preg_match('/'.$regex_password.'/u', $data['password'])) {
            $errors[] = 'Поле "Пароль: "'.$regex_errors[2];
        }
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
                <input type="text" pattern="<?=$regex_name_surname?>" class="form-control input" name="name" placeholder="Имя" required title="<?=$regex_errors[0]?>" value="<?=@$data['name']?>">
            </div>
            <div class="input-container">
                <input type="text" pattern="<?=$regex_name_surname?>" class="form-control input" name="surname" placeholder="Фамилия" required title="<?=$regex_errors[0]?>" value="<?=@$data['surname']?>">
            </div>
            <div class="input-container">
                <input type="text" pattern="<?=$regex_username?>" class="form-control input" name="username" placeholder="Имя пользователя" required title="<?=$regex_errors[1]?>" value="<?=@$data['username']?>">
            </div>
            <div class="input-container">
                <input type="password" pattern="<?=$regex_password?>" class="form-control input" name="password" placeholder="Пароль" required title="<?=$regex_errors[2]?>" value="<?=@$data['password']?>">
            </div>
            <div class="input-container">
                <input type="password" class="form-control input" name="confirm_password" placeholder="Повторный пароль" required value="<?php if (@$data['confirm_password'] == @$data['password']) echo @$data['password']?>">
            </div>
            <div class="input-container">
                <input type="text" class="form-control input" name="token" placeholder="Токен подлинности" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary form-control button" name="do_signup">Зарегистрировать админа</button>
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
