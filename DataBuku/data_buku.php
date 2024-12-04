<?php 
    class data_buku{
        private $koneksi;
        public function __construct($koneksi){
            $this->koneksi = $koneksi;
        }

        public function getBook(){
            $query = "SELECT * FROM buku";
            $stmt = $this->koneksi->prepare($query);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
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