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
    
    }
?>