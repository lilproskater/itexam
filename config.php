<?php
    require 'src/libs/rb.php';
    R::setup('mysql:host=localhost;dbname=itexam', 'uname', 'upass');

    if(!R::testConnection())
        echo "DB is not connected! Check your connection again!";
    
    // This values stands for a range of grades that you create tests for
    $begin_grade = 0;
    $end_grade = 0;
    // If you this two values are 0, then test system will work as in study courses without a grade
    session_start();
?>
