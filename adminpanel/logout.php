<?php
    require __DIR__.'/../config.php';
    unset($_SESSION['logged_admin']);
    header('Location: ./');
?>