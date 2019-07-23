<?php
    require './config.php';
    $user = R::findOne('profiles', 'username = ?', array($_SESSION['logged_user']->username));
    $user->status = "offline";
    R::store($user);
    unset($_SESSION['logged_user']);
    header('Location: /');
?>
