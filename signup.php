<?php
// الاتصال بقاعدة البيانات
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eventdb';

$conn = new mysqli($host, $username, $password, $database);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("الاتصال فشل: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // التحقق من وجود المستخدم مسبقاً
    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<p>اسم المستخدم موجود بالفعل! من فضلك اختر اسم مستخدم آخر.</p>";
    } else {
        // إدخال البيانات في قاعدة البيانات
        $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>تم إنشاء الحساب بنجاح. يمكنك الآن <a href='login.php'>تسجيل الدخول</a>.</p>";
        } else {
            echo "<p>حدث خطأ أثناء إنشاء الحساب: " . $conn->error . "</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب</title>
    
    
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
    <h1>إنشاء حساب جديد</h1>
    <form method="post" action="">
        <input type="text" name="username" placeholder="اسم المستخدم" required><br>
        <input type="password" name="password" placeholder="كلمة المرور" required><br>
        <input type="submit" value="إنشاء الحساب">
    </form>
</div>

</body>
</html>
