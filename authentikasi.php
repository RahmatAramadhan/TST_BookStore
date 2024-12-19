<?php 

    class authentikasi{
        private $koneksi;
        public function __construct($koneksi){
            $this->koneksi = $koneksi;
        }

        public function generateToken($username, $id){
            $key =  "TSTnilai_A";
            $payload = [
                "id" => $id,
                "username" => $username,
                "iat" => time(),
                "exp" => time() + 3600
            ];
            $base64payload = base64_encode(json_encode($payload));
            $signature = hash_hmac('sha256', $base64payload, $key);
            return $base64payload . "." . $signature;

        }

        public function saveActiveToken($id, $token){
            $query = "INSERT INTO token (id_user, token, expires_at) VALUES (:id, :token, :expires_at)";
            $stmt = $this->koneksi->prepare($query);
            $expiredAt = time() + 3600;
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires_at', $expiredAt);

            return $stmt->execute();
        }

        public function validateToken($token){
            $key = "TSTnilai_A";
            $part = explode('.', $token);

            if (count($part) !== 2) {
                return false;
            }

            $base64payload = $part[0];
            $signature = $part[1];

            $expectedSignature = hash_hmac('sha256', $base64payload, $key);
            if(!hash_equals($expectedSignature, $signature)){
                return false;
            }

            $payload = json_decode(base64_decode($base64payload), true);

            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return false;
            }

            return true;
        }
    }
?>