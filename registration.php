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
        if ($TYPE_OF_TEST == $school_test) {
            if ($data['grade'] == '')
                $errors[] = 'Выберите класс';
            if ($data['letter'] == '')
                $errors[] = 'Выберите букву класса';
        }
        elseif ($TYPE_OF_TEST == $course_test) {
            if ($data['subject'] == '')
                $errors[] = 'Выберите предмет';
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
            switch ($TYPE_OF_TEST) {
                case $school_test:
                    $user->test_type = $data['grade'].' '.$data['letter'];
                    break;
                case $course_test:
                    $user->test_type = $data['subject'];
                    break;
                case $just_test:
                    $user->test_type = $just_test[0];
                    break;
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
            <?php if ($TYPE_OF_TEST == $school_test): ?>
                <font class="grade-txt">Класс:</font>
                <select class="grade" name="grade" required>
                    <option></option>
                    <?php
                        foreach ($school_test as $grade) {
                            echo '<option value="'.$grade.'"';
                            if(isset($data['grade']) && $data['grade'] == strval($grade))
                                echo ' selected="selected"';
                            echo '>'.$grade.'</option>';
                        }
                    ?>
                </select>
                <select class="letter" name="letter" required>
                    <option></option>
                    <?php 
                        $values = array('А', 'Б', 'В', 'Г', 'Д', 'Е');
                        for ($i = 0; $i < count($values); $i ++) {
                            echo '<option value='.current($values);
                            echo (isset($data['letter']) && $data['letter'] == current($values)) ? ' selected="selected">' : '>';
                            echo current($values).'</option>';
                            next($values);
                        }
                    ?>
                </select>
            <?php elseif ($TYPE_OF_TEST == $course_test): ?>
                <font class="subject-txt">Предмет:</font>
                <select class="subject" name="subject" required>
                    <option></option>
                    <?php
                        foreach ($course_test as $subject) {
                            echo '<option value="'.$subject.'"';
                            if(isset($data['subject']) && $data['subject'] == $subject)
                                echo ' selected="selected"';
                            echo '>'.$subject.'</option>';
                        }
                    ?>
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
                <button type="submit" class="btn btn-primary form-control button" name="do_signup">Зарегистрироваться</button>
            </div>
        </form>
        <?php 
            if ($show_errors)
                echo '<h1 id="error">'.array_shift($errors).'</h1>';
        ?>
    </div>
</body>
</html>
