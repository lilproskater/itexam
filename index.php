<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="data/styles/index.css">
	<title>IT Exam</title>
</head>
<body>
    <form action="./index.php" method="POST">
        <div class="login-box">
        	<div class="title-container">
        		<h1>Вход</h1>
        	</div>
      	    <div class="forms-container">
                <div class="username-container">
                    <input type="input" name="username" class="username" placeholder="Логин" required>
                </div>
                <div class="password-container">
                    <input type="password" name="password" class="password" placeholder="Пароль" required>
                </div>
                <div class="enter-container">
                    <button>Войти</button>
                </div>
                <div class="reg-container">
                    <button>Регистрация</button>
                </div>
            </div>
        </div>
    </form>
</body>
</html>