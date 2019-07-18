<?php
    require './config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;

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
        
        if (R::count('profiles', 'username = ?', array($data['username'])) > 0) {
            $errors[] = 'Пользователь с данным логином уже существует';
        }
        if (empty($errors)) {
            $user = R::dispense('profiles');
            $user->username = $data['username'];
            $user->password = $data['password'];
            $user->name = $data['name'];
            $user->surname = $data['surname'];
            $user->grade = $data['grade'];
            $user->letter = $data['letter'];
            $user->date = date("d.m.Y H:i:s");
            R::store($user);
            header('Location: /index.php');
        }
        else {
            $show_errors = true;
        }
    }
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="src/css/registration.css">
	<title>IT Exam</title>
</head>
<body>
    <div class="container">
        <form action="./registration.php" method="POST">

        </form>
        <?php 
        /*
            if ($show_errors) {

            }
        */
        ?>
    </div>
</body>
</html>
