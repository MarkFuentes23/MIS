<?php
require_once 'lib/database.php';
use lib\Database;

class authModel {
    private $db;
    
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Function para sa secure na pag-login (ginagamit ang prepared statements at password_verify)
    public function login($username, $password){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($user && password_verify($password, $user['password'])){
            return $user;
        }
        return false;
    }
}
?>
