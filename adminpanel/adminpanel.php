<?php 
    require __DIR__.'/../config.php';
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <link rel="stylesheet" href="../src/bootstrap/css/bootstrap.css">
    <title>IT Exam Adminpanel</title>
</head>
<body>
    <?php if (!isset($_SESSION['logged_admin'])): ?>
        Хей админ, авторизируйся)
    <?php else: ?>
        Вы вошли админ!
    <?php endif; ?>
</body>
</html>
