<?php
// تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// التحقق من وجود معرّف المستخدم في الجلسة
session_start();

// التحقق من أن المستخدم قد سجل الدخول (يمكنك تعديل ذلك بناءً على نظام تسجيل الدخول الخاص بك)
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// التحقق من وجود معرّف الحدث في الطلب
if (isset($_GET['ticket_id'])) {
    $ticket_id = $_GET['ticket_id'];

    // استعلام لجلب تفاصيل التذكرة من قاعدة البيانات
    $sql = "SELECT t.ticketnumber, t.seatnumber, t.date, t.time, t.place, e.name AS event_name, t.username 
            FROM ticket t
            JOIN events e ON t.event_id = e.id
            WHERE t.ticketnumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // جلب بيانات التذكرة
        $ticket = $result->fetch_assoc();
    } else {
        echo "التذكرة غير موجودة.";
        exit();
    }
} else {
    echo "معرف التذكرة غير موجود.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل التذكرة</title>
    <style>
        body {
            background-color: #333;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .ticket-container {
            background-color: #444;
            border-radius: 8px;
            padding: 30px;
            width: 80%;
            margin: 0 auto;
            max-width: 600px;
            text-align: center;
        }
        .ticket-container h1 {
            color: #ffa500;
            margin-bottom: 20px;
        }
        .ticket-details {
            margin-bottom: 20px;
        }
        .ticket-details p {
            font-size: 18px;
            margin: 5px 0;
        }
        .btn {
            background-color: #ffa500;
            padding: 10px 20px;
            border-radius: 5px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #ff8c00;
        }
    </style>
</head>
<body>

<div class="ticket-container">
    <h1>تفاصيل تذكرتك</h1>
    <div class="ticket-details">
        <p><strong>اسم المستخدم:</strong> <?php echo htmlspecialchars($ticket['username']); ?></p>
        <p><strong>اسم الحدث:</strong> <?php echo htmlspecialchars($ticket['event_name']); ?></p>
        <p><strong>التاريخ:</strong> <?php echo htmlspecialchars($ticket['date']); ?></p>
        <p><strong>الوقت:</strong> <?php echo htmlspecialchars($ticket['time']); ?></p>
        <p><strong>المكان:</strong> <?php echo htmlspecialchars($ticket['place']); ?></p>
        <p><strong>رقم المقعد:</strong> <?php echo htmlspecialchars($ticket['seatnumber']); ?></p>
        <p><strong>رقم التذكرة:</strong> <?php echo htmlspecialchars($ticket['ticketnumber']); ?></p>
    </div>
    <a href="events.php" class="btn">الرجوع إلى الأحداث</a>
</div>

</body>
</html>
