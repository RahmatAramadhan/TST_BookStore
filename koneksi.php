<?php 
    $hostname   = 'mysql:host=localhost;dbname=toko_buku';
    $username   = 'desasub_admin';
    $password   = 'CyberNilaiA';

    $koneksi    = new pdo($hostname, $username, $password);

    if(!$koneksi){
        die('Connection failed : '.mysqli_connect_error());
    }

?>