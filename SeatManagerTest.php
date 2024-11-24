<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../SeatBooking.php'; // المسار إلى الملف الرئيسي

class SeatManagerTest extends TestCase {
    public function testReserveSeats() {
        $seats = ['A1', 'A2'];
        $expected = [
            "Seat A1 reserved successfully!",
            "Seat A2 reserved successfully!"
        ];

        $result = SeatManager::reserveSeats($seats);
        $this->assertEquals($expected, $result);
    }

    public function testReleaseSeats() {
        $seats = ['A1', 'A2'];
        $expected = [
            "Seat A1 released successfully!",
            "Seat A2 released successfully!"
        ];

        $result = SeatManager::releaseSeats($seats);
        $this->assertEquals($expected, $result);
    }
}
