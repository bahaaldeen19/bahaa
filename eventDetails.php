<?php
// بدء الجلسة
session_start();

// افترض أن هذه البيانات موجودة في الجلسة أو قاعدة البيانات
$eventDetails = [
    'eventName' => 'مباراة كرة القدم',
    'eventDate' => '2024-12-10',
    'eventTime' => '20:00',
    'eventPlace' => 'استاد الملك عبد الله',
    'userName' => 'أحمد محمد',
    'ticketPrice' => '100 ريال',
    'seatNumber' => 'A23',
];

// إضافة رسالة تأكيد الحجز
$bookingConfirmed = true;
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الحدث</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* استايلات مبدئية */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        h1, p {
            text-align: center;
            color: #f0ad4e;
        }
        .details {
            margin-top: 20px;
            text-align: center;
            color: #ddd;
        }
        .details p {
            margin: 10px 0;
        }
        .order-btn {
            display: block;
            background-color: #f0ad4e;
            color: #333;
            padding: 8px 15px; /* تصغير الزر */
            border-radius: 5px;
            text-align: center;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            width: auto;
            margin-left: auto;
            margin-right: auto;
        }
        .order-btn:hover {
            background-color: #e69e44;
        }
        .confirmation-msg {
            background-color: #5bc0de;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
            font-size: 1.2em;
        }
        .ticket-info {
            background-color: #444;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            color: #f0f0f0;
            font-size: 1.1em;
        }
        .ticket-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($bookingConfirmed): ?>
            <div class="confirmation-msg">
                تم تأكيد الحجز بنجاح!
            </div>
        <?php endif; ?>

        <h1>تفاصيل الحدث</h1>
        <div class="details">
            <p><strong>الحدث:</strong> <?php echo $eventDetails['eventName']; ?></p>
            <p><strong>التاريخ:</strong> <?php echo $eventDetails['eventDate']; ?></p>
            <p><strong>الوقت:</strong> <?php echo $eventDetails['eventTime']; ?></p>
            <p><strong>المكان:</strong> <?php echo $eventDetails['eventPlace']; ?></p>
            <p><strong>الاسم:</strong> <?php echo $eventDetails['userName']; ?></p>
        </div>

        <!-- معلومات التذكرة -->
        <div class="ticket-info">
            <p><strong>سعر التذكرة:</strong> <?php echo $eventDetails['ticketPrice']; ?></p>
            <p><strong>رقم الكرسي:</strong> <?php echo $eventDetails['seatNumber']; ?></p>
        </div>

        <!-- زر طلب الطعام -->
        <a href="foodorder.php" class="order-btn">طلب طعام</a>
    </div>
</body>
</html>
