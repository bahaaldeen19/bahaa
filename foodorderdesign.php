<?php
// Factory Method لإنشاء أنواع مختلفة من الطعام
class FoodFactory {
    public static function createFood($foodType) {
        switch($foodType) {
            case 'برجر':
                return new Burger();
            case 'بيتزا':
                return new Pizza();
            case 'دجاج':
                return new Chicken();
            default:
                throw new Exception("نوع الطعام غير معروف");
        }
    }
}

// تعريف الكلاسات الخاصة بأنواع الطعام
class Burger {
    public $name = "برجر";
    public $price = 15;

    public function getDescription() {
        return "هذا هو برجر لذيذ بسعر {$this->price} دولار.";
    }
}

class Pizza {
    public $name = "بيتزا";
    public $price = 12;

    public function getDescription() {
        return "هذه هي بيتزا شهية بسعر {$this->price} دولار.";
    }
}

class Chicken {
    public $name = "دجاج";
    public $price = 18;

    public function getDescription() {
        return "هذا هو دجاج مشوي رائع بسعر {$this->price} دولار.";
    }
}

// استخدام Factory Method
try {
    // إنشاء كائن من نوع بيتزا
    $foodItem = FoodFactory::createFood('بيتزا');
    echo "تم إنشاء الطعام: " . $foodItem->name . "\n";
    echo $foodItem->getDescription() . "\n";

    // إنشاء كائن من نوع برجر
    $foodItem = FoodFactory::createFood('برجر');
    echo "تم إنشاء الطعام: " . $foodItem->name . "\n";
    echo $foodItem->getDescription() . "\n";

    // إنشاء كائن من نوع دجاج
    $foodItem = FoodFactory::createFood('دجاج');
    echo "تم إنشاء الطعام: " . $foodItem->name . "\n";
    echo $foodItem->getDescription() . "\n";

} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage();
}
?>
