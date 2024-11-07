<?php
require_once 'Database.php';

class Event {
    private $db;
    private $conn;

    // تعديل البناء ليقبل كائن Database كمعامل
    public function __construct($db) {
        $this->db = $db;
        $this->conn = $this->db->connect();
    }

    // جلب جميع الأحداث
    public function getAllEvents() {
        $sql = "SELECT id, name, available FROM events";
        $result = $this->conn->query($sql);
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        return $events;
    }

    // جلب تفاصيل حدث محدد
    public function getEventDetails($eventId) {
        $sql = "SELECT * FROM events WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
