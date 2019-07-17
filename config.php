<?php
  require './src/libs/rb.php';
  R::setup('mysql:host=localhost;dbname=itexam', 'uname', 'upass');
  
  if(!R::testConnection()){
                echo "DB is not connected! Check your connection again!";
  }
  
  session_start();
?>
