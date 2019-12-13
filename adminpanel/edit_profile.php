<?php
    require __DIR__.'/../config.php';
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

    if (isset($data['do_edit'])) {
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

        if (empty($errors)) {
            $user = R::load('profiles', $_SESSION['editing_profile']->id);
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
            R::store($user);
            unset($_SESSION['editing_profile']);
            header('Location: ./adminpanel.php');
        }
        else {
            $show_errors = true;
            echo '<script>window.location.href = "./edit_profile.php#error"</script>';
        }
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../src/css/edit_profile.css">
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
            <h1>Изменить профиль</h1>
            <form action="./edit_profile.php" method="POST">
                <div class="input-container">
                    <input type="text" pattern="<?=$regex_name_surname?>" class="form-control input" name="name" placeholder="Имя" required title="<?=$regex_errors[0]?>" value="<?= @$_SESSION['editing_profile']->name ?>">
                </div>
                <div class="input-container">
                    <input type="text" pattern="<?=$regex_name_surname?>" class="form-control input" name="surname" placeholder="Фамилия" required title="<?=$regex_errors[0]?>" value="<?= @$_SESSION['editing_profile']->surname ?>">
                </div>
                <?php if ($TYPE_OF_TEST == $school_test): ?>
	                <font class="grade-txt">Класс:</font>
	                <select class="grade" name="grade" required>
	                    <option></option>
	                    <?php
	                        foreach ($school_test as $grade) {
	                            echo '<option value="'.$grade.'"';
                                if((isset($data['grade']) && $data['grade'] == strval($grade)) || explode(' ', $_SESSION['editing_profile']->test_type)[0] == $grade)
	                                echo ' selected="selected"';
	                            echo '>'.$grade.'</option>';
	                        }
	                    ?>
	                </select>
	                <select class="letter" name="letter" required>
	                    <option></option>
	                    <option value="А"
	                    <?php if((isset($data['letter']) && $data['letter'] == 'А') || explode(' ', $_SESSION['editing_profile']->test_type)[1] == 'А') 
	                              echo ' selected="selected"';
	                    ?>>А</option>
	                    <option value="Б"
	                    <?php if((isset($data['letter']) && $data['letter'] == 'Б') || explode(' ', $_SESSION['editing_profile']->test_type)[1] == 'Б') 
	                              echo ' selected="selected"';
	                    ?>>Б</option>
	                    <option value="В"
	                    <?php if((isset($data['letter']) && $data['letter'] == 'В') || explode(' ', $_SESSION['editing_profile']->test_type)[1] == 'В') 
	                              echo ' selected="selected"';
	                    ?>>В</option>
	                    <option value="Г"
	                    <?php if((isset($data['letter']) && $data['letter'] == 'Г') || explode(' ', $_SESSION['editing_profile']->test_type)[1] == 'Г') 
	                              echo ' selected="selected"';
	                    ?>>Г</option>
	                    <option value="Д"
	                    <?php if((isset($data['letter']) && $data['letter'] == 'Д') || explode(' ', $_SESSION['editing_profile']->test_type)[1] == 'Д') 
	                              echo ' selected="selected"';
	                    ?>>Д</option>
	                    <option value="Е"
	                    <?php if((isset($data['letter']) && $data['letter'] == 'Е') || explode(' ', $_SESSION['editing_profile']->test_type)[1] == 'Е') 
	                              echo ' selected="selected"';
	                    ?>>Е</option>
	                </select>
            	<?php elseif ($TYPE_OF_TEST == $course_test): ?>
	                <font class="subject-txt">Предмет:</font>
	                <select class="subject" name="subject" required>
	                    <option></option>
	                    <?php
	                        foreach ($course_test as $subject) {
	                            echo '<option value="'.$subject.'"';
	                            if((isset($data['subject']) && $data['subject'] == $subject) || $_SESSION['editing_profile']->test_type == $subject)
	                                echo ' selected="selected"';
	                            echo '>'.$subject.'</option>';
	                        }
	                    ?>
	                </select>
            <?php endif; ?>
                <div class="input-container">
                    <input type="text" pattern="<?=$regex_username?>" class="form-control input" name="username" placeholder="Имя пользователя" required title="<?=$regex_errors[1]?>" value="<?= @$_SESSION['editing_profile']->username ?>">
                </div>
                <div class="input-container">
                    <input type="text" pattern="<?=$regex_password?>" class="form-control input" name="password" placeholder="Пароль" required title="<?=$regex_errors[2]?>" value="<?= @$_SESSION['editing_profile']->password ?>">
                </div>
                <div class="input-container">
                    <input type="text" class="form-control input" name="confirm_password" placeholder="Повторный пароль" required value="<?= @$_SESSION['editing_profile']->password ?>">
                </div>
                <button type="submit" class="btn btn-success form-control edit-btn" name="do_edit" onclick="return Validate_form();">Изменить профиль</button>
            </form>
            <form action="./adminpanel.php" method="POST">
                <button type="submit" class="btn btn-primary form-control back-btn" name="do_go_back">Назад</button>
            </form>
            <?php 
                if ($show_errors)
                    echo '<h1 id="error">'.array_shift($errors).'</h1>';
            ?>
        </div>
    <?php endif; ?>
</body>
</html>
