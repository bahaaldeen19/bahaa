<?php

use PHPUnit\Framework\TestCase;

require_once 'C:\wamp64\www\my-project\Review.php';

// افتراضًا أن المراجعات مخزنة في الجلسة
session_start();

// تعريف دوال اختبار المراجعة
function submitReview($username, $eventID, $ticketNumber, $rating, $comment)
{
    // تحقق من صحة المدخلات (كما تم في الكود السابق)
    if (empty($username) || empty($eventID) || empty($ticketNumber) || empty($rating) || empty($comment)) {
        return "جميع الحقول مطلوبة!";
    }

    // إضافة مراجعة جديدة
    $_SESSION['user_review'] = new Review($eventID, $ticketNumber, $rating, $comment, $username);

    return "تمت إضافة المراجعة بنجاح!";
}

function getReview()
{
    if (isset($_SESSION['user_review']) && $_SESSION['user_review'] instanceof Review) {
        return [
            'username' => $_SESSION['user_review']->getUsername(),
            'event_id' => $_SESSION['user_review']->getEventID(),
            'ticket_number' => $_SESSION['user_review']->getTicketNumber(),
            'rating' => $_SESSION['user_review']->getRating(),
            'comment' => $_SESSION['user_review']->getComment()
        ];
    }

    return null;
}

function updateReview($username, $eventID, $ticketNumber, $rating, $comment)
{
    // تحديث المراجعة إذا كانت موجودة
    if (isset($_SESSION['user_review']) && $_SESSION['user_review'] instanceof Review) {
        $_SESSION['user_review'] = new Review($eventID, $ticketNumber, $rating, $comment, $username);
        return "تمت إضافة المراجعة بنجاح!";
    }

    return "لا توجد مراجعة لتحديثها!";
}

class ReviewTest extends TestCase
{
    public function testSubmitReview()
    {
        $result = submitReview("Ahmed", 1, 101, 5, "مراجعة ممتازة!");
        $this->assertEquals("تمت إضافة المراجعة بنجاح!", $result);
    }

    public function testGetReview()
    {
        submitReview("Ahmed", 1, 101, 5, "مراجعة ممتازة!");
        $review = getReview();
        $this->assertNotEmpty($review);
        $this->assertEquals("Ahmed", $review['username']);
    }

    public function testUpdateReview()
    {
        submitReview("Ahmed", 1, 101, 5, "مراجعة ممتازة!");
        $result = updateReview("Ali", 2, 202, 4, "مراجعة محدثة!");
        $this->assertEquals("تمت إضافة المراجعة بنجاح!", $result);

        $updatedReview = getReview();
        $this->assertEquals("Ali", $updatedReview['username']);
    }
}
