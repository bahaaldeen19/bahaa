<?php
session_start();

// إذا لم يتم تسجيل اسم المستخدم في الجلسة، نعتبره مستخدمًا جديدًا
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'guest_' . uniqid(); // اسم مستخدم افتراضي فريد
}

// مصفوفة لتخزين المراجعة الخاصة بالمستخدم
if (!isset($_SESSION['user_review'])) {
    $_SESSION['user_review'] = null;
}

// التحقق من إرسال البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $eventID = intval($_POST['event_id']);
    $ticketNumber = intval($_POST['ticket_number']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    // التحقق من صحة الإدخالات
    $errors = [];
    if (!preg_match("/^[a-zA-Z]+$/", $username)) {
        $errors[] = "اسم المستخدم يجب أن يحتوي على حروف فقط.";
    }
    if ($eventID <= 0) {
        $errors[] = "رقم الحدث يجب أن يكون رقمًا موجبًا.";
    }
    if ($ticketNumber <= 0) {
        $errors[] = "رقم التذكرة يجب أن يكون رقمًا موجبًا.";
    }
    if ($rating <= 0 || $rating > 5) {
        $errors[] = "التقييم يجب أن يكون بين 1 و 5.";
    }

    // إذا لم تكن هناك أخطاء
    if (empty($errors)) {
        // إذا كان الطلب تحديثًا، قم بتحديث المراجعة
        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            $_SESSION['user_review'] = [
                'username' => $username,
                'eventID' => $eventID,
                'ticketNumber' => $ticketNumber,
                'rating' => $rating,
                'comment' => $comment,
            ];
        } else {
            // إضافة مراجعة جديدة
            $_SESSION['user_review'] = [
                'username' => $username,
                'eventID' => $eventID,
                'ticketNumber' => $ticketNumber,
                'rating' => $rating,
                'comment' => $comment,
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المراجعات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .reviews {
            max-width: 600px;
            margin: 20px auto;
        }
        .review {
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>إدارة المراجعات</h1>

    <!-- عرض الأخطاء إن وجدت -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- نموذج إدخال المراجعة -->
    <form action="" method="POST">
        <label for="username">اسم المستخدم:</label>
        <input type="text" name="username" id="username" required>

        <label for="event_id">رقم الحدث:</label>
        <input type="number" name="event_id" id="event_id" required>

        <label for="ticket_number">رقم التذكرة:</label>
        <input type="number" name="ticket_number" id="ticket_number" required>

        <label for="rating">التقييم (1-5):</label>
        <input type="number" name="rating" id="rating" min="1" max="5" required>

        <label for="comment">المراجعة:</label>
        <textarea name="comment" id="comment" rows="4" required></textarea>

        <button type="submit" name="action" value="submit">إرسال المراجعة</button>
    </form>

    <!-- عرض المراجعة الخاصة بالمستخدم -->
    <div class="reviews">
        <?php if (!empty($_SESSION['user_review'])): ?>
            <div class="review">
                <p><strong>اسم المستخدم:</strong> <?= htmlspecialchars($_SESSION['user_review']['username']) ?></p>
                <p><strong>رقم الحدث:</strong> <?= htmlspecialchars($_SESSION['user_review']['eventID']) ?></p>
                <p><strong>رقم التذكرة:</strong> <?= htmlspecialchars($_SESSION['user_review']['ticketNumber']) ?></p>
                <p><strong>التقييم:</strong> <?= htmlspecialchars($_SESSION['user_review']['rating']) ?></p>
                <p><strong>المراجعة:</strong> <?= nl2br(htmlspecialchars($_SESSION['user_review']['comment'])) ?></p>
                <form action="" method="POST" style="display: inline;">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($_SESSION['user_review']['username']) ?>">
                    <input type="hidden" name="event_id" value="<?= htmlspecialchars($_SESSION['user_review']['eventID']) ?>">
                    <input type="hidden" name="ticket_number" value="<?= htmlspecialchars($_SESSION['user_review']['ticketNumber']) ?>">
                    <input type="hidden" name="rating" value="<?= htmlspecialchars($_SESSION['user_review']['rating']) ?>">
                    <input type="hidden" name="comment" value="<?= htmlspecialchars($_SESSION['user_review']['comment']) ?>">
                    <button type="submit" name="action" value="update">تحديث المراجعة</button>
                </form>
            </div>
        <?php else: ?>
            <p>لا توجد مراجعات حتى الآن.</p>
        <?php endif; ?>
    </div>
</body>
</html>
