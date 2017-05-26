<?php

require __DIR__.'/../src/Timer.php';

use Nicklayb\Timer\Timer;
use PHPUnit\Framework\TestCase;

function generateTimer($times)
{
    $timer = new Timer;
    for ($i = 0; $i < ($times - 1); $i++) {
        $timer->pushTime($i);
    }
    return $timer;
}

class TimerTest extends TestCase
{
    public function testPushTime()
    {
        $times = 4;
        $timer = generateTimer($times);
        $this->assertEquals($timer->countTimes(), $times);
    }

    public function testClearTimes()
    {
        $timer = generateTimer(4);
        $timer->clearTimes();
        $this->assertEquals($timer->countTimes(), 1);
    }

    public function testStartWithTime()
    {
        $timer = new Timer;
        $this->assertEquals($timer->countTimes(), 1);
    }
}
