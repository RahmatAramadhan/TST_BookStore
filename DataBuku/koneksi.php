<?php 
    $hostname   = 'mysql:host=localhost;dbname=desasumb_data_buku';
    $username   = 'desasub_admin';
    $password   = 'CyberNilaiA';

    $koneksi    = new pdo($hostname, $username, $password);

    if(!$koneksi){
        die('Connection failed : '.mysqli_connect_error());
    }

?>