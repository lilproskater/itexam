<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="data/styles/registration.css">
	<title>IT Exam</title>
</head>
<body>
    <form action="./index.php" method="POST">
        <div class="registration-box">
        	<div class="title-container">
        		<h1>Регистрация</h1>
        	</div>
      	    <div class="forms-container">
                <div class="name-container">
                    <input type="input" name="name" class="name" placeholder="Имя" required>
                </div>

                <div class="surname-container">
                    <input type="input" name="surname" class="surname" placeholder="Фамилия" required>
                </div>
                <div class="grade-title-container">
                  <h2>Класс: </h2>
                </div>
                <div class="grade-letter-container">
                    <select class="grade" name="grade" required>
                        <option></option>
                        <option>11</option>
                        <option>10</option>
                        <option>9</option>
                        <option>8</option>
                    </select>
                    <select class="letter" name="letter" required>
                        <option></option>
                        <option>А</option>
                        <option>Б</option>
                        <option>В</option>
                        <option>Г</option>
                        <option>Д</option>
                        <option>Е</option>
                    </select>
                </div>
                
                <div class="username-container">
                    <input type="input" name="username" class="username" placeholder="Логин" required>
                </div>
                
                <div class="password-container">
                    <input type="password" name="password" class="password" placeholder="Пароль" required>
                </div>
                
                <div class="confirm-password-container">
                    <input type="password" name="password" class="password" placeholder="Повторите пароль" required>
                </div>

                <div class="reg-container">
                    <button>Зарегистрироваться</button>
                </div>
                <div class="space"></div>                
            </div>
        </div>
    </form>
</body>
</html>