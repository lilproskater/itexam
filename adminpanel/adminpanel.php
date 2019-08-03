<?php 
    require __DIR__.'/../config.php';
    $data = $_POST;
    if (isset($data['do_logout'])) {
        header('Location: ./logout.php');
    }
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
        <form action="./">
            <h1>Хей админ, авторизируйся)</h1>
            <button type="submit">На главную админку =)</button>
        </form>
    <?php else: ?>
        <form action="./adminpanel.php" method="POST">
            <h1>Вы вошли админ!</h1>
            <button type="submit" name="do_logout">Выход</button>
        </form>
    <?php endif; ?>
</body>
</html>
