<?php
use PHPUnit\Framework\TestCase;

// تضمين الكلاس
require_once 'FoodOrderclass.php';

class FoodOrderTest extends TestCase {

    // اختبار إضافة طلب
    public function testAddOrder() {
        $foodOrder = new FoodOrderclass();
        $result = $foodOrder->addOrder("برجر", 1, 1, 15);
        $this->assertEquals("تم إضافة الطلب بنجاح", $result); // تحقق من رسالة النجاح
    }

    // اختبار إضافة طلب مكرر بنفس التفاصيل (نفس الطعام ونفس المستخدم ونفس الحدث)
    public function testAddDuplicateOrder() {
        $foodOrder = new FoodOrderclass();
        $foodOrder->addOrder("برجر", 1, 1, 15);
        $result = $foodOrder->addOrder("برجر", 1, 1, 15); // نفس الطعام والمستخدم والحدث
        $this->assertEquals("الطلب موجود بالفعل", $result); // تحقق من أن الطلب المكرر غير مقبول
    }

    // اختبار استرجاع جميع الطلبات
    public function testGetAllOrders() {
        $foodOrder = new FoodOrderclass();
        $foodOrder->addOrder("بيتزا", 1, 2, 12);
        $orders = $foodOrder->getAllOrders();
        $this->assertGreaterThan(0, count($orders), "يجب أن تكون هناك طلبات"); // تحقق من وجود طلبات
    }

    // اختبار حذف طلب
    public function testRemoveOrder() {
        $foodOrder = new FoodOrderclass();
        $foodOrder->addOrder("بيتزا", 1, 2, 12);
        $result = $foodOrder->removeOrder(1); // حذف الطلب الأول
        $this->assertEquals("تم حذف الطلب بنجاح", $result); // تحقق من رسالة الحذف
        $orders = $foodOrder->getAllOrders();
        $this->assertCount(0, $orders, "يجب أن تكون قائمة الطلبات فارغة"); // تحقق من أن القائمة فارغة
    }

    // اختبار محاولة حذف طلب غير موجود
    public function testRemoveNonExistentOrder() {
        $foodOrder = new FoodOrderclass();
        $foodOrder->addOrder("برجر", 1, 1, 15);
        $result = $foodOrder->removeOrder(5); // محاولة حذف طلب غير موجود
        $this->assertEquals("الطلب غير موجود", $result); // تحقق من الرسالة المناسبة
    }

    // اختبار إضافة طلبات متعددة بنفس المستخدم
    public function testAddMultipleOrders() {
        $foodOrder = new FoodOrderclass();
        $foodOrder->addOrder("برجر", 1, 1, 15);
        $foodOrder->addOrder("بيتزا", 1, 2, 12);
        $orders = $foodOrder->getAllOrders();
        $this->assertCount(2, $orders, "يجب أن تكون هناك طلبين في القائمة");
    }
}
?>
