<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Booking</title>
    <style>
        /* إعدادات عامة */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', Arial, sans-serif;
    background: linear-gradient(145deg, #1a1a1a, #121212);
    color: #ffffff;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* الحاوية الرئيسية */
.container {
    width: 90%;
    max-width: 1000px;
    padding: 20px;
    background: linear-gradient(145deg, #222222, #2a2a2a);
    border-radius: 20px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
    animation: fadeIn 1.2s ease-in-out;
    position: relative;
    overflow: hidden;
}

/* عناوين */
h1 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 2.5rem;
    color: #00e676;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* الحاوية الخاصة بالمقاعد */
.seat-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(60px, 1fr));
    gap: 15px;
    justify-content: center;
    margin-bottom: 20px;
}

/* تصميم المقعد */
.seat {
    width: 60px;
    height: 60px;
    background: #1e1e1e;
    border: 2px solid #00e676;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    font-size: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
}

.seat:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 230, 118, 0.6);
}

.seat.reserved {
    background: #dc3545;
    border-color: #ff616f;
    cursor: not-allowed;
}

.seat.reserved:hover {
    transform: none;
    box-shadow: none;
}

/* أزرار الحجز والتحرير */
.button {
    display: inline-block;
    padding: 15px 25px;
    margin: 10px auto;
    background: #ffff80;
    color: #1e1e1e;
    font-size: 1rem;
    font-weight: bold;
    text-transform: uppercase;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
}

.button:hover {
    background: #0000ff;
    color: white;
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(0, 230, 118, 0.6);
}

/* الرسائل */
.success {
    color: #00e676;
    text-align: center;
    font-size: 1.2rem;
    margin-top: 20px;
}

.error {
    color: #ff616f;
    text-align: center;
    font-size: 1.2rem;
    margin-top: 20px;
}

/* تأثيرات الحركة */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* زخرفة خلفية داخل الحاوية */
.container::before,
.container::after {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    background: rgba(0, 230, 118, 0.2);
    border-radius: 50%;
    z-index: 0;
    animation: moveShapes 10s infinite linear;
}

.container::before {
    top: -50px;
    left: -50px;
}

.container::after {
    bottom: -50px;
    right: -50px;
}

/* حركة الزخرفة */
@keyframes moveShapes {
    0% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(20px, 20px);
    }
    100% {
        transform: translate(0, 0);
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Seat Booking</h1>
        <form method="POST" action="">
            <div class="seat-container">
                <?php
                // تعريف المقاعد
                $seats = [
                    ["id" => "A1", "status" => "available"],
                    ["id" => "A2", "status" => "available"],
                    ["id" => "A3", "status" => "reserved"],
                    ["id" => "A4", "status" => "available"]
                ];

                foreach ($seats as $seat) {
                    $status = $seat['status'];
                    $seatID = $seat['id'];
                    $class = $status === "available" ? "seat" : "seat reserved";
                    $disabled = $status === "reserved" ? "disabled" : "";

                    echo "
                    <label>
                        <input type='checkbox' name='seats[]' value='$seatID' $disabled>
                        <div class='$class' data-status='$status'>$seatID</div>
                    </label>";
                }
                ?>
            </div>
            <button type="submit" name="reserve" class="button">Reserve Selected</button>
            <button type="submit" name="release" class="button">Release Selected</button>
        </form>

        <?php
        // تعريف الدوال
        class SeatManager {
            public static function reserveSeats($seats) {
                $results = [];
                foreach ($seats as $seat) {
                    $results[] = "Seat $seat reserved successfully!";
                }
                return $results;
            }

            public static function releaseSeats($seats) {
                $results = [];
                foreach ($seats as $seat) {
                    $results[] = "Seat $seat released successfully!";
                }
                return $results;
            }
        }

        // معالجة الطلب
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedSeats = $_POST['seats'] ?? [];
            if (!empty($selectedSeats)) {
                if (isset($_POST['reserve'])) {
                    $results = SeatManager::reserveSeats($selectedSeats);
                    foreach ($results as $message) {
                        echo "<p class='success'>$message</p>";
                    }
                } elseif (isset($_POST['release'])) {
                    $results = SeatManager::releaseSeats($selectedSeats);
                    foreach ($results as $message) {
                        echo "<p class='success'>$message</p>";
                    }
                }
            } else {
                echo "<p class='error'>No seats selected!</p>";
            }
        }
        ?>
    </div>
</body>
</html>
