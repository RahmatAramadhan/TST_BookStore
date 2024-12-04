<?php 
    require_once 'koneksi.php';
    require_once 'toko_buku.php';

    $tokoBuku   = new toko_buku($koneksi);
    $method     = $_SERVER['REQUEST_METHOD'];
    $endpoint   = $_SERVER['PATH_INFO'];
    header('Content-Type: application/json');

    switch ($method) {
        case 'GET':
            if ($endpoint == '/getBook') {
                $result = $tokoBuku->getBookFromAPI();
                echo json_encode($result);
            }elseif($endpoint == '/getBookById'){
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $result = $tokoBuku->getBookById($id);
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
            if ($endpoint == '/register') {
                $data   = json_decode(file_get_contents('php://input'), true);
                $result = $tokoBuku->register($data);
                echo json_encode(['success' => $result]);
            }else if($endpoint == '/login'){
                $data   = json_decode(file_get_contents('php://input'), true);
                $result = $tokoBuku->login($data);
                echo json_encode(['success' => $result]);
            }elseif($endpoint == '/transaction'){
                $data   = json_decode(file_get_contents('php://input'), true);
                if (isset($data['id_buku']) && isset($data['id_user'])) {
                    $id_buku = $data['id_buku'];
                    $id_user = $data['id_user'];

                    $result = $tokoBuku->transaction($id_buku, $id_user);
                    echo json_encode(['success' => $result]);
                }else{
                    echo json_encode([
                    'status' => 'error',
                    'message' => 'ID buku dan ID user tidak disertakan'
                    ]);
                }
            }
            break;

        default:
            # code...
            break;
    }

?>