<?php
// تضمين الاتصال بقاعدة البيانات
include_once("db.php");

// تضمين كلاس User
include_once("User.php");

// بدء الجلسة
session_start();

// إنشاء كائن من كلاس User
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// تحقق مما إذا كانت هناك بيانات تم إرسالها من النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // الحصول على كلمة المرور

    // محاولة تسجيل الدخول
    if ($user->login($username, $password)) {
        $_SESSION['username'] = $username;
        header("Location: ticket.php");  // إعادة التوجيه إلى صفحة التذكرة
        exit();
    } else {
        $error_message = "اسم المستخدم أو كلمة المرور غير صحيحة."; // رسالة خطأ
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <style>
        /* إعداد التنسيق العام */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #181818; /* لون خلفية داكن */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #f0f0f0;
        }

        .container {
            background: linear-gradient(135deg, #2b2b2b, #404040);
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s;
        }

        .container:hover {
            transform: scale(1.02);
        }

        h1 {
            text-align: center;
            color: #ffa500; /* لون العنوان */
            margin-bottom: 20px;
            font-size: 2em;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #555;
            border-radius: 10px;
            background-color: #333; /* لون خلفية المدخلات */
            color: #f0f0f0;
            font-size: 1em;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border: 1px solid #ffa500; /* تغيير لون الحدود عند التركيز */
            outline: none;
        }

        input[type="submit"] {
            width: 100%;
            padding: 15px;
            background-color: #ffa500; /* لون زر الإنشاء */
            border: none;
            border-radius: 10px;
            color: #222;
            font-weight: bold;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #ff8c00; /* تغيير لون الزر عند التمرير */
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: #bbb; /* لون الفقرات */
        }

        a {
            color: #ffa500; /* لون الروابط */
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #ff8c00; /* تغيير لون الروابط عند التمرير */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>تسجيل الدخول</h1>
    <?php if (isset($error_message)): ?>
        <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <input type="text" placeholder="اسم المستخدم" name="username" required>
        <input type="password" placeholder="كلمة المرور" name="password" required>
        <input type="submit" name="s" value="تسجيل الدخول">
    </form>
    <p>ليس لديك حساب؟ <a href="signup.php">إنشاء حساب</a></p>
</div>




</body>
</html>
