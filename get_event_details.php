<?php
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// استعلام لجلب جميع الأحداث من قاعدة البيانات
$sql = "SELECT id, name, location, date, time FROM events";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // عرض تفاصيل كل حدث في القائمة
    while($row = $result->fetch_assoc()) {
        // عرض تفاصيل الحدث
        echo "<div class='event'>";
        echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
        echo "<p><strong>المكان:</strong> " . htmlspecialchars($row['location']) . "</p>";
        echo "<p><strong>التاريخ:</strong> " . htmlspecialchars($row['date']) . "</p>";
        echo "<p><strong>الوقت:</strong> " . htmlspecialchars($row['time']) . "</p>";
        
        // زر عرض التفاصيل
        echo "<a href='event_details.php?event_id=" . $row['id'] . "' class='btn'>عرض التفاصيل</a>";
        echo "</div>";
    }
} else {
    echo "لا توجد أحداث حالياً.";
}

$conn->close();
?>
