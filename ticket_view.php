<?php
session_start();
include_once 'db.php';

// إنشاء كائن من كلاس Database
$database = new Database();
$db = $database->getConnection();

// التحقق من أن المستخدم قد سجل الدخول
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة

// استعلام لجلب تفاصيل التذكرة
$query = "SELECT * FROM ticket WHERE username = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<h2>تفاصيل التذكرة</h2>";
    echo "<p>اسم المستخدم: " . $ticket['username'] . "</p>";
    echo "<p>اسم الحدث: " . $ticket['eventname'] . "</p>";
    echo "<p>المكان: " . $ticket['place'] . "</p>";
    echo "<p>التاريخ: " . $ticket['date'] . "</p>";
    echo "<p>الوقت: " . $ticket['time'] . "</p>";
    echo "<p>رقم التذكرة: " . $ticket['ticketnumber'] . "</p>";
    echo "<p>رقم المقعد: " . $ticket['seatnumber'] . "</p>";
} else {
    echo "<p>لا توجد تذكرة لهذا المستخدم.</p>";
}
?>
