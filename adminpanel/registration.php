<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    $invalid_token = false;
    if (isset($data['do_signup'])) {
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
        if (empty($errors) && $data['token'] != 'VG9rZW4=')
            $invalid_token = true;
        if (R::count('admins', 'username = ?', array($data['username'])) > 0) {
            $errors[] = 'Администратор с данным логином уже существует';
        }
        if (empty($errors) && !$invalid_token) {
            $admin = R::dispense('admins');
            $admin->username = strtolower($data['username']);
            $admin->password = $data['password'];
            $admin->name = ucfirst(strtolower($data['name']));
            $admin->surname = ucfirst(strtolower($data['surname']));
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
	<title>IT Exam Adminpanel</title>
</head>
<body>
    <div class="container">
        <h1>Админ регистрация</h1>
        <form action="./registration.php" method="POST">
        	<div class="input-container">
                <input type="text" class="form-control input" name="name" placeholder="Имя" required value="<?=@$data['name']?>">
            </div>
            <div class="input-container">
                <input type="text" class="form-control input" name="surname" placeholder="Фамилия" required value="<?=@$data['surname']?>">
            </div>
            <div class="input-container">
                <input type="text" class="form-control input" name="username" placeholder="Имя пользователя" required value="<?=@$data['username']?>">
            </div>
            <div class="input-container">
                <input type="password" class="form-control input" name="password" placeholder="Пароль" required value="<?=@$data['password']?>">
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
            if ($show_errors) {
                echo '<h1 id="error">'.array_shift($errors).'</h1>';
            }
        ?>
    </div>
</body>
</html>
