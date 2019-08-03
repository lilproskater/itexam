<?php
    require 'src/libs/rb.php';
    R::setup('mysql:host=192.168.1.108;dbname=itexam', 'mirodil', 'akbarjonim99');

    if(!R::testConnection()){
        echo "DB is not connected! Check your connection again!";
    }

    session_start();
?>
