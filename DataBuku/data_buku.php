<?php 
    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', 60); 

    class data_buku{
        private $koneksi;
        public function __construct($koneksi){
            $this->koneksi = $koneksi;
            if (!$this->koneksi) {
                error_log("Database connection failed");
            }
        }

        public function getBook(){
            $query = "SELECT * FROM buku";
            $stmt = $this->koneksi->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            array_walk_recursive($data, function (&$item) {
                if (is_string($item)) {
                    $item = mb_convert_encoding($item, 'UTF-8', 'auto');
                }
            });
    
            $response = json_encode([
                "success" => [
                    "status" => "true",
                    "message" => "Data buku berhasil ditampilkan",
                    "data" => $data
                ]
            ], JSON_UNESCAPED_UNICODE);
    
            if (!$response) {
                error_log("JSON Error: " . json_last_error_msg());
            }
            return $response;
        }

        public function getBookById($id){
            $query = "SELECT * FROM buku WHERE id = :id";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                return[
                    'status' => 'success',
                    'data' => $data
                ];
            }else{
                return[
                    'status' => 'error',
                    'message' => 'Buku tidak ditemukan'
                ];
            }
        }
    
    }
?>