<?php
    require './config.php';
    $data = $_POST;
    $errors = array();
    $show_errors = false;
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
        if (!($begin_grade == 0 || $end_grade == 0)) {
            if ($data['grade'] == '')
                $errors[] = 'Выберите класс';
            if ($data['letter'] == '')
                $errors[] = 'Выберите букву класса';
        }
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
        if (R::count('profiles', 'username = ?', array(strtolower($data['username']))) > 0)
            $errors[] = 'Пользователь с данным логином уже существует';
        if (empty($errors)) {
            $user = R::dispense('profiles');
            $user->name = mb_convert_case(mb_strtolower($data['name']), MB_CASE_TITLE, "UTF-8");
            $user->surname = mb_convert_case(mb_strtolower($data['surname']), MB_CASE_TITLE, "UTF-8");
            if (!($begin_grade == 0 || $end_grade == 0)) {
                $user->grade = $data['grade'];
                $user->letter = $data['letter'];
            } 
            else {
                $user->grade = 0;
                $user->letter = '';
            }
            $user->username = mb_strtolower($data['username']);
            $user->password = $data['password'];
            $user->date = date("d.m.Y H:i:s");
            R::store($user);
            header('Location: ./');
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
    <link rel="stylesheet" href="src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="src/css/registration.css">
    <title>IT Exam</title>
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <form action="./registration.php" method="POST">
            <div class="input-container">
                <input type="text" pattern="<?=$regex_name_surname?>" class="form-control input" name="name" placeholder="Имя" required title="<?=$regex_errors[0]?>" value="<?php if (strpos(@$data['name'], ' ') == false) echo @$data['name']?>">
            </div>
            <div class="input-container">
                <input type="text" pattern="<?=$regex_name_surname?>" class="form-control input" name="surname" placeholder="Фамилия" required  title="<?=$regex_errors[0]?>" value="<?php if (strpos(@$data['surname'], ' ') == false) echo @$data['surname']?>">
            </div>
            <?php if (!($begin_grade == 0 || $end_grade == 0)) :?>
                <font class="grade-txt">Класс:</font>
                <select class="grade" name="grade" required>
                    <option></option>
                    <?php
                        for ($i = $begin_grade; $i <= $end_grade; $i ++) {
                            echo '<option value="'.$i.'"';
                            if(isset($data['grade']) && $data['grade'] == strval($i))
                                echo ' selected="selected"';
                            echo '>'.$i.'</option>';
                        }
                    ?>
                </select>
                <select class="letter" name="letter" required>
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
            <?php endif; ?>
            <div class="input-container">
                <input type="text" pattern="<?=$regex_username?>" class="form-control input" name="username" placeholder="Имя пользователя" required title="<?=$regex_errors[1]?>" value="<?php if (strpos(@$data['username'], ' ') == false) echo @$data['username']?>">
            </div>
            <div class="input-container">
                <input type="password" pattern="<?=$regex_password?>" class="form-control input" name="password" placeholder="Пароль" required title="<?=$regex_errors[2]?>" value="<?php if (strpos(@$data['password'], ' ') == false) echo @$data['password']?>">
            </div>
            <div class="input-container">
                <input type="password" class="form-control input" name="confirm_password" placeholder="Повторный пароль" required value="<?php if (@$data['confirm_password'] == @$data['password']) echo @$data['password']?>">
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary form-control button" name="do_signup" onclick="return Validate_form();">Зарегистрироваться</button>
            </div>
        </form>
        <?php 
            if ($show_errors)
                echo '<h1 id="error">'.array_shift($errors).'</h1>';
        ?>
    </div>
</body>
</html>
