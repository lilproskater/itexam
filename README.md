<h1>IT Exam</h1>

Hello and welcome to itexam repository. This project was created by Sultanov Mirodil in 2019 and all the license information 
is written in license.txt

<h2>About project</h2>
This project is created for testing the knowledge on any theme you want.<br>
It would be much more interesting if this project will be used in a localnetwork, because it would be like a contest.

<h2>Installation</h2>
All you need is to install LAMP server if you are on GNU/LinuxOS and configure it.<br>
If you are on WindowsOS then just install OpenServer and configure that too the way you want.

<h2>Configuration</h2>
<ol>
<li>In MySQL create a database to keep "itexam" information there. Ex db name: "itexam".<br>
It is better if encode type will be in "utf8_general_ci"</li>
<li>Go to file "config.php" and correct the connection to your MySQL database you just created.<br>

//Manage questions and adminpanel guide here...

<b><li></b>Default token for registrating an admin is 'VG9rZW4=' (the word "Token" encoded in Base64).<br>
   If you want to change it then go to "adminpanel/registration.php" and change the checking token on line 20 to that you want.</li>
</ol>
