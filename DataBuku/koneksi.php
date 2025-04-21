<?php 
    $hostname   = 'mysql:host=localhost;dbname=Book_Store';
    $username   = 'rahmat';
    $password   = 'Rahmat32*';

    $koneksi    = new pdo($hostname, $username, $password);

    if(!$koneksi){
        die('Connection failed : '.mysqli_connect_error());
    }

?>