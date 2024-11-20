<?php 
    class toko_buku{
        private $koneksi;
        public function __construct($koneksi){
            $this->koneksi = $koneksi;
        }
        public function getAllBook(){
            $query  = "SELECT * FROM buku ";
            $result = mysqli_query($this->koneksi, $query);
            $buku   = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $buku[] = $row;
            }
            return $buku;
        }

        public function getBookById($id){
            $id = $_GET['id'];
            $query  = "SELECT * FROM buku WHERE id_buku = '$id'";
            $result = mysqli_query($this->koneksi, $query);
            $row    = mysqli_fetch_assoc($result);
            return $row;

        }

        public function addBookFrom(){
            
        }

    }

?>