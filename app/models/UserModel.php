<?php
class UserModel extends Model {
    public function register($username, $password){
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if($stmt->fetch()) return false;
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$username, $hashedPassword]);
    }

    public function login($username, $password){
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])){
            unset($user['password']);
            return $user;
        }
        return false;
    }
    
    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        return $result['count'];
    }
}