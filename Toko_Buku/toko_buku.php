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

        public function transaction(){

        }

        public function login($data){
            $username = $data['username'];
            $password = $data['password'];

            $query  = "SELECT * FROM user WHERE username = :username";
            $run    = $this->koneksi->prepare($query);
            $run->bindParam(':username', $username);
            $run->execute();

            $user   = $run->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id']    = $user['id'];
                    $_SESSION['username']   = $user['username'];

                    return [
                        'status'    => 'true',
                        'message'   => 'Login Berhasil',
                        'user'      => $user
                    ];
                }else{
                    return[
                        'status'    => 'false',
                        'message'   => 'Password salah'
                    ];
                }

            }else{
                return [
                    'status' => false,
                    'message'=> ' username tidak ditemukan'
                ];
            }
        }

        public function register($data){
            $username = $data['username'];
            $password = $data['password'];

            $hashPWD    = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO user (username, password) VALUES (:username, :password)";

            $run = $this->koneksi->prepare($query);
            $run->bindParam(':username', $username);
            $run->bindParam(':password', $hashPWD);

            if ($run->execute()) {
                return true;
            }else{
                return false;
            }
            
        }

    }

?>