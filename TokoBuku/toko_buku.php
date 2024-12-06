<?php 
    require "curl_helper.php";
    require "authentikasi.php";

    class toko_buku{
        private $koneksi;
        public function __construct($koneksi){
            $this->koneksi = $koneksi;
        }

        public function getBookById($id){
            $url = "http://localhost/Buku/DataBuku/api.php/getBookById?id=".urlencode($id);
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

        public function transaction($id_buku, $id_user){
            $query = "INSERT INTO transaksi (id_buku, id_user, total_harga) VALUES (:id_buku, :id_user, :total_harga)";

            $total_harga = 0;
            $run = $this->koneksi->prepare($query);
            $run->bindParam(':id_buku', $id_buku);
            $run->bindParam(':id_user', $id_user);
            $run->bindParam(':total_harga', $total_harga);

            if ($run->execute()) {
                return true;
            }else{
                return false;
            }
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
                    
                    $auth = new authentikasi($this->koneksi);
                    $token = $auth->generateToken($user['username'], $user['id']);
                    $auth->saveActiveToken($user['id'], $token);

                    return [
                        'status'    => 'true',
                        'message'   => 'Login Berhasil',
                        'user'      => $user,
                        'token'     => $token
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