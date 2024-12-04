<?php 
    require "curl_helper.php";

    class toko_buku{
        private $koneksi;
        public function __construct($koneksi){
            $this->koneksi = $koneksi;
        }
        
        public function getAllBook(){
            
        }

        public function getBookById($id){
            $id = $_GET['id'];
            $query  = "SELECT * FROM buku WHERE id_buku = '$id'";
            $result = mysqli_query($this->koneksi, $query);
            $row    = mysqli_fetch_assoc($result);
            return $row;

        }

        public function getBookFromAPI(){
            $url = "http://localhost/Buku/DataBuku/api.php/getBook";
            $result = sendRequest($url, 'GET');
            $response = json_decode($result, true);

            if($response['HTTP_CODE'] == 200){
                $books = json_decode($response['response'], true);

                if (!empty($books)) {
                    return[
                        'status' => 'success',
                        'data' => $books
                    ];
                }else{
                    return [
                        'status' => 'error',
                        'message' => 'Tidak ada data'
                        ];
                }

            }else{
                return [
                    'status' => 'error',
                    'message' => 'Gagal mengambil data' . $response['HTTP_CODE']
                    ];
            }

        }

        public function transaction){
            

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