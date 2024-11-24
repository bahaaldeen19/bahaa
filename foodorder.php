<?php
// بدء الجلسة
session_start();

// استيراد الكلاس
include 'FoodOrderclass.php';

// إنشاء كائن من الكلاس
$foodOrder = new FoodOrderclass();

// معالجة الطلبات من الفورم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استخراج البيانات من الفورم
    $foodItem = $_POST['foodItem'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // إضافة فحص وجود الكمية وتعيينها لقيمة افتراضية إذا كانت غير موجودة
    $userID = $_POST['userID'];
    $eventID = $_POST['eventID'];
    $totalPrice = $_POST['totalPrice'] * $quantity; // احتساب السعر الإجمالي بناءً على الكمية

    // إذا لم تكن الجلسة تحتوي على طلبات من قبل، نبدأ مصفوفة جديدة
    if (!isset($_SESSION['orders'])) {
        $_SESSION['orders'] = [];
    }

    // التحقق مما إذا كان نفس الصنف موجود بالفعل في الطلبات مع نفس الكمية
    $orderExists = false;
    foreach ($_SESSION['orders'] as $order) {
        if ($order['foodItem'] == $foodItem && $order['quantity'] == $quantity) {
            $orderExists = true;
            break;
        }
    }

    if ($orderExists) {
        echo "<script>alert('الطلب موجود بالفعل بنفس الكمية!');</script>";
    } else {
        // إضافة الطلب إلى الجلسة
        $order = [
            'foodItem' => $foodItem,
            'quantity' => $quantity,
            'totalPrice' => $totalPrice,
            'userID' => $userID,
            'eventID' => $eventID
        ];

        // إضافة الطلب إلى مصفوفة الطلبات في الجلسة
        $_SESSION['orders'][] = $order;

        echo "<script>alert('تم إضافة الطلب بنجاح');</script>";
    }
}

// حذف الطلب
if (isset($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    // حذف الطلب من المصفوفة
    if (isset($_SESSION['orders'][$removeIndex])) {
        unset($_SESSION['orders'][$removeIndex]);
        $_SESSION['orders'] = array_values($_SESSION['orders']); // إعادة ترتيب المصفوفة بعد الحذف
        echo "<script>alert('تم حذف الطلب بنجاح');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب الطعام</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* استايل عام */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* إعدادات الجسم */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2e2e2e, #1a1a1a);
            color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        /* الحاوية الرئيسية */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* ترويسة الصفحة */
        header {
            text-align: center;
            padding: 20px;
            background: #333;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* عنوان الترويسة */
        header h1 {
            font-size: 2.5em;
            color: #f0ad4e;
        }

        /* وصف الترويسة */
        header p {
            font-size: 1.2em;
            color: #ddd;
        }

        /* إعدادات القائمة الخاصة بالأطعمة */
        .food-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* جعل المربعات بجانب بعضها */
            gap: 20px;
            margin: 30px 0;
        }

        /* تصميم المربعات الخاصة بالأطعمة */
        .food-item {
            background-color: #444;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        /* تأثير عند التمرير على المربعات */
        .food-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        /* عرض الملصقات */
        .food-item .emoji {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        /* عنوان الطعام */
        .food-item h2 {
            color: #f0ad4e;
        }

        /* السعر */
        .food-item .price {
            color: #f0ad4e;
            font-weight: bold;
            margin: 10px 0;
        }

        /* حقل الكمية */
        .food-item input[type="number"] {
            width: 50px;
            padding: 5px;
            margin: 10px 0;
            text-align: center;
            background-color: #333;
            color: #f0ad4e;
            border: none;
            border-radius: 5px;
        }

        /* زر الطلب */
        .order-btn {
            background-color: #f0ad4e;
            color: #333;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        /* تأثير الزر عند التمرير */
        .order-btn:hover {
            background-color: #e69e44;
        }

        /* ملخص الطلب */
        .order-summary {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-top: 30px;
            width: 95%;
            position: absolute;
            top:125%;
            left:3%;
        }

        /* عنوان ملخص الطلب */
        .order-summary h2 {
            color: #f0ad4e;
            text-align: center;
        }

        /* قائمة ملخص الطلب */
        .order-summary ul {
            list-style-type: none;
            padding: 0;
            color: #ddd;
        }

        .order-summary ul li {
            padding: 10px;
            border-bottom: 1px solid #555;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🍔 طلب الطعام لحدثك 🍕</h1>
            <p>اختر الأطعمة المفضلة لديك واستمتع بألذ النكهات!</p>
        </header>

        <div class="food-menu">
            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍔</div>
                    <h2>برجر</h2>
                    <p>برجر لذيذ مع جبن وخس.</p>
                    <span class="price">15 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="برجر">
                    <input type="hidden" name="totalPrice" value="15">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍕</div>
                    <h2>بيتزا</h2>
                    <p>بيتزا مارغريتا مع صوص الطماطم الطازج.</p>
                    <span class="price">12 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="بيتزا">
                    <input type="hidden" name="totalPrice" value="12">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <!-- إضافة أصناف جديدة -->
            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍗</div>
                    <h2>دجاج</h2>
                    <p>دجاج مشوي مع توابل خاصة.</p>
                    <span class="price">18 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="دجاج">
                    <input type="hidden" name="totalPrice" value="18">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍣</div>
                    <h2>سوشي</h2>
                    <p>سوشي مع سمك طازج وأرز.</p>
                    <span class="price">25 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="سوشي">
                    <input type="hidden" name="totalPrice" value="25">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🥗</div>
                    <h2>سلطة</h2>
                    <p>سلطة طازجة مع صوص الزبادي.</p>
                    <span class="price">10 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="سلطة">
                    <input type="hidden" name="totalPrice" value="10">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🥪</div>
                    <h2>ساندويتش</h2>
                    <p>ساندويتش لحم مشوي مع خبز طازج.</p>
                    <span class="price">14 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="ساندويتش">
                    <input type="hidden" name="totalPrice" value="14">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍩</div>
                    <h2>دونات</h2>
                    <p>دونات لذيذة محشوة بالشوكولاتة.</p>
                    <span class="price">8 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="دونات">
                    <input type="hidden" name="totalPrice" value="8">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍫</div>
                    <h2>شوكولاتة</h2>
                    <p>شوكولاتة غنية ولذيذة.</p>
                    <span class="price">5 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="شوكولاتة">
                    <input type="hidden" name="totalPrice" value="5">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>

            <form action="foodorder.php" method="POST">
                <div class="food-item">
                    <div class="emoji">🍦</div>
                    <h2>آيس كريم</h2>
                    <p>آيس كريم بالفانيليا والشوكولاتة.</p>
                    <span class="price">7 دولار</span>
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="hidden" name="foodItem" value="آيس كريم">
                    <input type="hidden" name="totalPrice" value="7">
                    <input type="hidden" name="userID" value="1">
                    <input type="hidden" name="eventID" value="1">
                    <button type="submit" class="order-btn">اطلب الآن</button>
                </div>
            </form>
        </div>

        <!-- ملخص الطلبات -->
        <div class="order-summary">
            <h2>ملخص الطلبات</h2>
            <ul>
                <?php
                // عرض الطلبات
                if (isset($_SESSION['orders'])) {
                    foreach ($_SESSION['orders'] as $index => $order) {
                        echo "<li>" . $order['foodItem'] . " (العدد: " . $order['quantity'] . ") - السعر: " . $order['totalPrice'] . " دولار <a href='?remove=" . $index . "'>حذف</a></li>";
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
