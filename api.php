<?php 
    require_once 'koneksi.php';
    require_once 'toko_buku.php';

    $tokoBuku   = new toko_buku($koneksi);
    $method     = $_SERVER['REQUEST_METHOD'];
    $endpoint   = $_SERVER['PATH_INFO'];
    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            
            break;
        
        case 'POST':
            if ($endpoint == '/register') {
                $data   = json_decode(file_get_contents('php://input'), true);
                $result = $tokoBuku->register($data);
                echo json_encode(['success' => $result]);
            }
            break;

        default:
            # code...
            break;
    }

?>