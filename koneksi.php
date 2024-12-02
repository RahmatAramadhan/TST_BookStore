<?php 
    $hostname   = 'mysql:host=localhost;dbname=toko_buku';
    $username   = 'root';
    $password   = '';

    $koneksi    = new pdo($hostname, $username, $password);

    if(!$koneksi){
        die('Connection failed : '.mysqli_connect_error());
    }

?>