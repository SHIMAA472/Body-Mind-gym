<?php
require_once 'config.php';

class ServiceManagement {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // إنشاء خدمة جديدة
    public function createService($name, $description, $price, $duration) {
        $stmt = $this->conn->prepare("INSERT INTO services (name, description, price, duration) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $duration]);
    }
    
    // الحصول على جميع الخدمات
    public function getAllServices() {
        $stmt = $this->conn->prepare("SELECT * FROM services ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // الحصول على خدمة معينة
    public function getService($service_id) {
        $stmt = $this->conn->prepare("SELECT * FROM services WHERE service_id = ?");
        $stmt->execute([$service_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // تحديث خدمة
    public function updateService($service_id, $name, $description, $price, $duration) {
        $stmt = $this->conn->prepare("UPDATE services SET name = ?, description = ?, price = ?, duration = ? WHERE service_id = ?");
        return $stmt->execute([$name, $description, $price, $duration, $service_id]);
    }
    
    // حذف خدمة
    public function deleteService($service_id) {
        $stmt = $this->conn->prepare("DELETE FROM services WHERE service_id = ?");
        return $stmt->execute([$service_id]);
    }
    
    // الحصول على خدمات المدرب
    public function getTrainerServices($trainer_id) {
        $stmt = $this->conn->prepare("
            SELECT s.* FROM services s
            JOIN trainer_services ts ON s.service_id = ts.service_id
            WHERE ts.trainer_id = ?
        ");
        $stmt->execute([$trainer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>