<?php
// اتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // اسم المستخدم الافتراضي لـ WAMP
$password = ""; // كلمة المرور الافتراضية لـ WAMP
$dbname = "eventdb";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// استعلام لجلب الأحداث من الجدول
$sql = "SELECT id, name, available FROM events";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأحداث المتاحة</title>
    <style>
        /* إعداد التنسيق العام */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #333;
            color: #fff;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #444;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #ffa500;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 15px;
            border-bottom: 1px solid #555;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .unavailable {
            color: #000000;
            font-weight: bold;
        }
        button, a.btn {
            padding: 10px 15px;
            background-color: #ffa500;
            color: #222;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover, a.btn:hover {
            background-color: #ff8c00;
        }
        button.show-details {
            background-color: #ff8c00;
        }
        button.show-details:hover {
            background-color: #ff8c00;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(50, 50, 50, 0.9);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #333;
            color: #f0f0f0;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover {
            color: white;
            cursor: pointer;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            padding: 10px;
            width: calc(100% - 120px);
            border: none;
            border-radius: 5px;
        }
        .search-bar button {
            width: 100px;
        }
        .no-results {
            color: #ff0000;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>الأحداث المتاحة</h1>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="ابحث عن حدث" onkeyup="searchEvent()">
        <button onclick="searchEvent()">بحث</button>
    </div>
    <ul id="eventList">
        <?php
        if ($result->num_rows > 0) {
            // جلب الأحداث من قاعدة البيانات وعرضها
            while ($row = $result->fetch_assoc()) {
                echo "<li data-event-name='" . htmlspecialchars($row['name']) . "'>";
                echo "<div><strong>" . htmlspecialchars($row['name']) . "</strong>";
                // تحقق من حالة توفر الحدث
                if ($row['available'] == 0) {
                    echo " <span class='unavailable'>(غير متاح)</span>";
                }
                echo "</div>";
                echo "<button class='show-details' onclick='showDetails(" . $row['id'] . ")'>عرض التفاصيل</button>";
                // إخفاء زر الحجز إذا كان الحدث غير متاح
                if ($row['available'] == 1) {
                    echo "<a href='login.php?event_id=" . $row['id'] . "' class='btn'>احجز الآن</a>";
                }
                echo "</li>";
            }
        } else {
            echo "<li>لا توجد أحداث متاحة حاليًا.</li>";
        }
        $conn->close();
        ?>
    </ul>
    <div id="noResults" class="no-results" style="display:none;">آسف، لم يتم العثور على أي حدث بهذا الاسم.</div>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="eventName"></h2>
        <p id="modalContent"></p>
    </div>
</div>

<script>
    function showDetails(eventId) {
        fetch(`get_event_details.php?event_id=${eventId}`)
            .then(response => {
                if (!response.ok) throw new Error("فشل في جلب تفاصيل الحدث.");
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                document.getElementById('eventName').innerText = data.name;
                document.getElementById('modalContent').innerHTML = `
                    <p><strong>التاريخ:</strong> ${data.date || 'غير متوفر'}</p>
                    <p><strong>الوقت:</strong> ${data.time || 'غير متوفر'}</p>
                    <p><strong>المكان:</strong> ${data.location || 'غير متوفر'}</p>
                    <p><strong>عدد التذاكر المتاحة:</strong> ${data.ticket || 'غير متوفر'}</p>
                    <p><strong>عدد المقاعد:</strong> ${data.seat || 'غير متوفر'}</p>
                    <p><strong>الوصف:</strong> ${data.description || 'غير متوفر'}</p>
                `;
                document.getElementById('myModal').style.display = "block";
            })
            .catch(error => {
                console.error("خطأ:", error);
                alert("حدث خطأ أثناء تحميل تفاصيل الحدث. يرجى المحاولة مرة أخرى.");
            });
    }

    function closeModal() {
        document.getElementById('myModal').style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('myModal')) {
            closeModal();
        }
    }

    function showDetails(eventId) {
    console.log(eventId); // للتحقق من قيمة eventId
    fetch(`get_event_details.php?event_id=${eventId}`)
        .then(response => {
            if (!response.ok) throw new Error("فشل في جلب تفاصيل الحدث.");
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            document.getElementById('eventName').innerText = data.name;
            document.getElementById('modalContent').innerHTML = `
                <p><strong>التاريخ:</strong> ${data.date || 'غير متوفر'}</p>
                <p><strong>الوقت:</strong> ${data.time || 'غير متوفر'}</p>
                <p><strong>المكان:</strong> ${data.location || 'غير متوفر'}</p>
                <p><strong>عدد التذاكر المتاحة:</strong> ${data.ticket || 'غير متوفر'}</p>
                <p><strong>عدد المقاعد:</strong> ${data.seat || 'غير متوفر'}</p>
                <p><strong>الوصف:</strong> ${data.description || 'غير متوفر'}</p>
            `;
            document.getElementById('myModal').style.display = "block";
        })
        .catch(error => {
            console.error("خطأ:", error);
            alert("حدث خطأ أثناء تحميل تفاصيل الحدث. يرجى المحاولة مرة أخرى.");
        });
}

</script>
</body>
</html>
