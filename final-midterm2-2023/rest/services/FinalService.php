<?php
require_once __DIR__."/../dao/FinalDao.php";


use Firebase\JWT\JWT;

class FinalService {
    protected $dao;

    public function __construct(){
        $this->dao = new FinalDao();
    }

    
        public function login($email) {
            $user = $this->dao->login($email);
    
            if (!$user) {
                return null; // Or handle as per your application logic
            }
    
            // Generate JWT token
            $token = $this->generateJwtToken($user['id'], $user['email']);
    
            // You can modify the response to include the token if needed
            $user['token'] = $token;
    
            return $user;
        }
    
        protected function generateJwtToken($userId, $email) {
            global $jwt_secret; // Assuming you have defined this in your config.php
    
            $payload = [
                'user_id' => $userId,
                'email' => $email,
                'exp' => time() + (60 * 60) // Token expiration time (1 hour from now)
            ];
    
            return JWT::encode($payload, JWT_SECRET, 'HS256');
        
    }
    /** TODO
    * Implement service method to add new investor to investor table and cap-table
    */
    public function investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares){
        return $this->dao->investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares);
    }

    public function share_classes(){
        return $this->dao->share_classes();
    }

    public function share_class_categories(){
        return $this->dao->share_class_categories();
    }
}
?>
