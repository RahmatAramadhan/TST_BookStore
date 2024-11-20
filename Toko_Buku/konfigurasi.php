<?php 
    $hostname   = 'localhost';
    $username   = 'root';
    $password   = '';
    $database   = 'Toko_Buku';

    $koneksi    = mysqli_connect($hostname, $username, $password, $database);

    if(!$koneksi){
        die('Connection failed : '.mysqli_connect_error());
    }

?>