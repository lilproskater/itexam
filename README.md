<h1>IT Exam</h1>

<h2>About project</h2>
<p>This project is created for testing the knowledge on any theme you want.<br>
It would be much more interesting if this project will be used in a localnetwork, because it would be like a contest.</p>

<h2>Installation</h2>
<p>All you need is to install LAMP server if you are on GNU/LinuxOS and configure it.<br>
If you are on WindowsOS then just install OpenServer and configure that too the way you want.</p>

<h2>Configuration</h2>
<ol>
<li>In MySQL create a database to keep "itexam" information there. Ex db name: "itexam".<br>
It is better if encode type will be in "utf8mb4_unicode_520_ci"</li>
<li>Go to file "config.php" and correct the connection to your MySQL database you just created.<br>
</ol>

<h2>Adminpanel</h2>
<u><h3>Notice</h3></u>
<p>Default token for registrating an admin is 'VG9rZW4=' (the word "Token" encoded in Base64).<br>
If you want to change it then go to "adminpanel/registration.php" and change the checking token on line 20 to that you want.<br>
Also the information below is written as if you configured your DNS server and this site is called www.example.com.</p>
<ol>
   <li>Go to www.example.com/adminpanel and registrate yourself as admin.</li>
   <li>You will go to www.example.com/adminpanel/adminpanel.php, where you can control questions, profiles and results.</li>
   <li>There you can edit, add, delete questions and profiles. Also you can delete results.</li>
</ol>
