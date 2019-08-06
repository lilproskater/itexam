<?php
    require __DIR__.'/../config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
    if (isset($data['do_signin'])) {
        $admin = R::findOne('admins', 'username = ?', array(strtolower($data['username'])));
        if (!empty($admin)) {
            if ($data['password'] == $admin->password) {
                $_SESSION['logged_admin'] = $admin;
                header('Location: ./adminpanel.php');
            }
            else
                $errors[] = "Не правильный пароль";
        } 
        else
            $errors[] = "Не правильный логин";
        if (!empty($errors)) {
            $show_errors = true;
            echo '<script>window.location.href = "./index.php#error"</script>';
        }
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/style.css">
    <title>IT Exam Admin Panel</title>
</head>
<body>
    <script type="text/javascript">
      function ShowPassword() {
          var input = document.getElementById("password");
          if (input.type == "password")
            input.type = "text";
          else
            input.type = "password";
      }
    </script>
    <div class="container">
        <h1 class="title">Админ панель</h1>
        <form action="./index.php" method="POST">
            <div class="input-container">
                <input type="text" class="form-control input" name="username" placeholder="Имя пользователя" value="<?php if (!in_array('Не правильный логин', $errors)) echo @$data['username']?>" required>
            </div>
            <div class="input-container">
                <input type="password" class="form-control input" id="password" name="password" placeholder="Пароль" required>
            </div>
            <div class="checkbox-container">
                <p style="float: left; font-family: sans-serif;">Показать пароль:</p>
                <input class="checkbox" style="margin-left: 10px;" type="checkbox" onclick="ShowPassword();">
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary form-control button" name="do_signin">Войти</button>
            </div>
        </form>
        <form action="./registration.php">
            <div class="button-container">
                <button type="submit" class="btn btn-primary form-control button" name="do_signup">Регистрация админа</button>
            </div>
        </form><br>
        <?php 
            if ($show_errors)
                echo '<h1 id="error">'.array_shift($errors).'</h1>';
        ?>
    </div>
</body>
</html>
