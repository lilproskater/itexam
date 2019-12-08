<?php
    require __DIR__.'/../config.php';
    unset($_SESSION['logged_admin']);
    unset($_SESSION['show_questions']);
    unset($_SESSION['show_profiles']);
    unset($_SESSION['show_results']);
    unset($_SESSION['selected_type']);
    header('Location: ./');
?>