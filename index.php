<?php
    require './config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    if (isset($data['do_signin'])) {
        $user = R::findOne('profiles', 'username = ?', array($data['username']));
        if (!empty($user)) {
            if ($data['password'] == $user->password) {
                $_SESSION['logged_user'] = $user;
                header('Location: /exam.php');
            }
            else {
                $errors[] = "Не правильный пароль";
            }
        } 
        else {
             $errors[] = "Не правильный логин";
        }
        if (!empty($errors)) {
            $show_errors = true;
        }
    }
    if (isset($data['do_signup'])) {
        header('Location: /registration.php');
    }
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="src/styles/index.css">
	<title>IT Exam</title>
</head>
<body>
    <form action="./index.php" method="POST">
        <div class="login-box">
        	<div class="title-container">
        		<h1>Вход</h1>
        	</div>
      	    <div class="forms-container">
                <div class="username-container">
                    <input type="input" name="username" class="username" placeholder="Логин">
                </div>
                <div class="password-container">
                    <input type="password" name="password" class="password" placeholder="Пароль">
                </div>
                <div class="enter-container">
                    <button name="do_signin" type="submit">Войти</button>
                </div>
                <div class="reg-container">
                    <button name="do_signup" type="submit">Регистрация</button>
                </div>
                <?php
                    if ($show_errors) {
                        echo '<center><h1 class="errors">'.array_shift($errors).'</h1></center>';
                    }
                ?>
            </div>
        </div>
    </form>
</body>
</html>
