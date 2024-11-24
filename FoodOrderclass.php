<?php
require_once 'FoodOrderclass.php';

class FoodOrderclass {
    private $orders = []; // تخزين الطلبات بشكل مؤقت

    // دالة لإضافة طلب جديد
    public function addOrder($foodItem, $userID, $eventID, $totalPrice) {
        // تحقق من وجود طلب بنفس التفاصيل (نفس الطعام، نفس المستخدم، نفس الحدث)
        foreach ($this->orders as $order) {
            if ($order['foodItem'] == $foodItem && $order['userID'] == $userID && $order['eventID'] == $eventID) {
                return "الطلب موجود بالفعل"; // إذا كان الطلب موجودًا بالفعل
            }
        }
        
        // توليد orderID جديد بناءً على عدد الطلبات الحالية
        $orderID = count($this->orders) + 1;
        
        // إضافة الطلب إلى القائمة
        $this->orders[] = [
            'orderID' => $orderID,
            'foodItem' => $foodItem,
            'userID' => $userID,
            'eventID' => $eventID,
            'totalPrice' => $totalPrice
        ];
        
        return "تم إضافة الطلب بنجاح"; // رسالة نجاح
    }

    // دالة لجلب جميع الطلبات
    public function getAllOrders() {
        return $this->orders; // إرجاع جميع الطلبات
    }

    // دالة لحذف طلب بناءً على orderID
    public function removeOrder($orderID) {
        foreach ($this->orders as $index => $order) {
            if ($order['orderID'] == $orderID) {
                unset($this->orders[$index]); // حذف الطلب
                $this->orders = array_values($this->orders); // إعادة ترتيب الفهارس بعد الحذف
                return "تم حذف الطلب بنجاح"; // رسالة النجاح
            }
        }
        return "الطلب غير موجود"; // في حال لم يتم العثور على الطلب
    }
}
?>
