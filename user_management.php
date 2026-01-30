<?php
require_once 'config.php';

class UserManagement {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // إنشاء مدرب
    public function createTrainer($user_id, $specialization, $experience_years, $bio) {
        $stmt = $this->conn->prepare("INSERT INTO trainers (user_id, specialization, experience_years, bio) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $specialization, $experience_years, $bio]);
    }
    
    // إنشاء عضو
    public function createMember($user_id, $membership_type, $join_date, $health_notes) {
        $stmt = $this->conn->prepare("INSERT INTO members (user_id, membership_type, join_date, health_notes) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $membership_type, $join_date, $health_notes]);
    }
    
    // الحصول على جميع المدربين
    public function getAllTrainers() {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.full_name, u.email, u.phone, t.trainer_id, t.specialization, t.experience_years, t.bio 
            FROM users u 
            JOIN trainers t ON u.user_id = t.user_id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // الحصول على مدرب معين
    public function getTrainer($trainer_id) {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.full_name, u.email, u.phone, t.trainer_id, t.specialization, t.experience_years, t.bio 
            FROM users u 
            JOIN trainers t ON u.user_id = t.user_id
            WHERE t.trainer_id = ?
        ");
        $stmt->execute([$trainer_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // تحديث معلومات المدرب
    public function updateTrainer($trainer_id, $specialization, $experience_years, $bio) {
        $stmt = $this->conn->prepare("UPDATE trainers SET specialization = ?, experience_years = ?, bio = ? WHERE trainer_id = ?");
        return $stmt->execute([$specialization, $experience_years, $bio, $trainer_id]);
    }
    
    // الحصول على جميع الأعضاء
    public function getAllMembers() {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.full_name, u.email, u.phone, m.member_id, m.membership_type, m.join_date 
            FROM users u 
            JOIN members m ON u.user_id = m.user_id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // الحصول على عضو معين
    public function getMember($member_id) {
        $stmt = $this->conn->prepare("
            SELECT u.user_id, u.full_name, u.email, u.phone, m.member_id, m.membership_type, m.join_date, m.health_notes 
            FROM users u 
            JOIN members m ON u.user_id = m.user_id
            WHERE m.member_id = ?
        ");
        $stmt->execute([$member_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // تحديث معلومات العضو
    public function updateMember($member_id, $membership_type, $health_notes) {
        $stmt = $this->conn->prepare("UPDATE members SET membership_type = ?, health_notes = ? WHERE member_id = ?");
        return $stmt->execute([$membership_type, $health_notes, $member_id]);
    }
}
?>