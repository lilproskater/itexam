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
    <link rel="stylesheet" href="src/styles/registration.css">
	<title>IT Exam</title>
</head>
<body>
    <form action="./registration.php" method="POST">
        <div class="registration-box">
        	<div class="title-container">
        		<h1>Регистрация</h1>
        	</div>
      	    <div class="forms-container">
                <div class="name-container">
                    <input type="input" name="name" class="name" placeholder="Имя" required value="<?=@$data['name']?>">
                </div>

                <div class="surname-container">
                    <input type="input" name="surname" class="surname" placeholder="Фамилия" required value="<?=@$data['surname']?>">
                </div>
                <div class="grade-title-container">
                  <h2>Класс: </h2>
                </div>
                <div class="grade-letter-container">
                    <select class="grade" name="grade" required>
                        <option></option>
                        <option>11</option>
                        <option>10</option>
                        <option>9</option>
                        <option>8</option>
                    </select>
                    <select class="letter" name="letter" required>
                        <option></option>
                        <option>А</option>
                        <option>Б</option>
                        <option>В</option>
                        <option>Г</option>
                        <option>Д</option>
                        <option>Е</option>
                    </select>
                </div>
                
                <div class="username-container">
                    <input type="input" name="username" class="username" placeholder="Логин" required value="<?=@$data['username']?>">
                </div>
                
                <div class="password-container">
                    <input type="password" name="password" class="password" placeholder="Пароль" required>
                </div>
                
                <div class="confirm-password-container">
                    <input type="password" name="confirm_password" class="password" placeholder="Повторите пароль" required>
                </div>

                <div class="reg-container">
                    <button name="do_signup">Зарегистрироваться</button>
                </div>
                <?php
                    if ($show_errors) {
                        echo '<center><h1 class="errors">'.array_shift($errors).'</h1></center>';
                    }
                ?>
                <div class="space"></div>                
            </div>
        </div>
    </form>
</body>
</html>
