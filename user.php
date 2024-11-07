<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // دالة تسجيل الدخول
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username AND password = :password";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true; // نجاح تسجيل الدخول
        }

        return false; // فشل تسجيل الدخول
    }
}
?>
