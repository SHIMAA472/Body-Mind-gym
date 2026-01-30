<?php
require_once 'config.php';

class ClassManagement {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // إنشاء فصل جديد
    public function createClass($trainer_id, $name, $description, $schedule_date, $schedule_time, $max_participants) {
        $stmt = $this->conn->prepare("INSERT INTO classes (trainer_id, name, description, schedule_date, schedule_time, max_participants) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$trainer_id, $name, $description, $schedule_date, $schedule_time, $max_participants]);
    }
    
    // الحصول على جميع الفصول
    public function getAllClasses() {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.full_name as trainer_name, t.specialization 
            FROM classes c
            JOIN trainers t ON c.trainer_id = t.trainer_id
            JOIN users u ON t.user_id = u.user_id
            ORDER BY c.schedule_date, c.schedule_time
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // الحصول على فصول مدرب معين
    public function getTrainerClasses($trainer_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM classes 
            WHERE trainer_id = ?
            ORDER BY schedule_date, schedule_time
        ");
        $stmt->execute([$trainer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // الحصول على فصل معين
    public function getClass($class_id) {
        $stmt = $this->conn->prepare("
            SELECT c.*, u.full_name as trainer_name, t.specialization 
            FROM classes c
            JOIN trainers t ON c.trainer_id = t.trainer_id
            JOIN users u ON t.user_id = u.user_id
            WHERE c.class_id = ?
        ");
        $stmt->execute([$class_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // تحديث فصل
    public function updateClass($class_id, $name, $description, $schedule_date, $schedule_time, $max_participants) {
        $stmt = $this->conn->prepare("UPDATE classes SET name = ?, description = ?, schedule_date = ?, schedule_time = ?, max_participants = ? WHERE class_id = ?");
        return $stmt->execute([$name, $description, $schedule_date, $schedule_time, $max_participants, $class_id]);
    }
    
    // حذف فصل
    public function deleteClass($class_id) {
        $stmt = $this->conn->prepare("DELETE FROM classes WHERE class_id = ?");
        return $stmt->execute([$class_id]);
    }
    
    // الحصول على عدد الحجوزات لكل فصل
    public function getClassBookingsCount($class_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE service_id IN (SELECT service_id FROM services WHERE name LIKE '%class%') AND booking_date = (SELECT schedule_date FROM classes WHERE class_id = ?) AND booking_time = (SELECT schedule_time FROM classes WHERE class_id = ?)");
        $stmt->execute([$class_id, $class_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
}
?>
