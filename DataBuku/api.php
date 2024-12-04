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
            }elseif($endpoint == '/getBookById'){
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $result = $dataBuku->getBookById($id);
                    echo json_encode($result);
                }else{
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'ID buku tidak disertakan'
                    ]);
                }
            }
            break;
        
        case 'POST':

            break;

        default:
            
            break;
    }

?>