<?php
require_once 'config.php';

class Auth {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // تسجيل مستخدم جديد
    public function register($full_name, $email, $password, $role, $phone = null) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, password, role, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $email, $hashed_password, $role, $phone]);
        
        return $this->conn->lastInsertId();
    }
    
    // تسجيل دخول
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // إضافة معلومات إضافية حسب الدور
            if ($user['role'] == 'trainer') {
                $trainer_info = $this->getTrainerInfo($user['user_id']);
                $_SESSION['trainer_id'] = $trainer_info['trainer_id'];
                $_SESSION['specialization'] = $trainer_info['specialization'];
            } elseif ($user['role'] == 'member') {
                $member_info = $this->getMemberInfo($user['user_id']);
                $_SESSION['member_id'] = $member_info['member_id'];
            }
            
            return true;
        }
        
        return false;
    }
    
    // تسجيل خروج
    public function logout() {
        session_unset();
        session_destroy();
    }
    
    // الحصول على معلومات المدرب
    private function getTrainerInfo($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM trainers WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // الحصول على معلومات العضو
    private function getMemberInfo($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM members WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // التحقق من تسجيل الدخول
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // التحقق من دور المستخدم
    public function checkRole($required_role) {
        if (!$this->isLoggedIn() || $_SESSION['role'] != $required_role) {
            header("Location: login.php");
            exit();
        }
    }
}
?>