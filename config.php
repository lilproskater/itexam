<?php
    require 'src/libs/rb.php';
    /* DATABASE CONNECTION CONFIG */
    R::setup('mysql:host=localhost;dbname=itexam', 'uname', 'upass');

    if(!R::testConnection())
        echo "DB is not connected! Check your connection again!";
    session_start();
    /* DATABASE CONNECTION CONFIG */

    /* OTHER CONFIGS */
    $school_test = array(8, 9, 10, 11); //grade numbers to hold tests
    $course_test = array("Английский язык", "Математика", "Физика", "Химия", 
                     "Биология", "История", "Русский язык", "Литература"); // subjects to hold tests
    $just_test = array("JUST_TEST"); // type of test for other purposes. Do not change it
    /* OTHER CONFIGS */
    $TYPE_OF_TEST = $course_test; // set this value to value you want above. Ex for School: $school_test
?>
