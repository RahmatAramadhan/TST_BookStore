<?php 
    require_once 'koneksi.php';
    require_once 'data_buku.php';

    $dataBuku   = new data_buku($koneksi);
    $method     = $_SERVER['REQUEST_METHOD'];
    $endpoint   = $_SERVER['PATH_INFO'];
    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            if ($endpoint == '/getBook') {
                $result = $dataBuku->getBook();
                echo json_encode($result);
            }
            break;
        
        case 'POST':

            break;

        default:
            
            break;
    }

?>