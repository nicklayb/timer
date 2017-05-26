<?php

namespace Nicklayb\Timer;

class Timer
{
    protected $times = [];
    protected $names = [];

    private static $instance = null;

    public function __construct()
    {
        $this->pushTime($this->getStartTimeName());
    }

    public function getStartTimeName()
    {
        return 'start';
    }

    public function pushTime($name)
    {
        $this->times[] = microtime(true);
        $this->names[$name] = (count($this->times) - 1);
        return $this;
    }

    public function getTimes()
    {
        return $this->times;
    }

    public function getFirstTime()
    {
        return $this->times[0];
    }

    public function getLastTime()
    {
        return $this->times[$this->getLastTimeIndex()];
    }

    public function countTimes()
    {
        return count($this->times);
    }

    public function getLastTimeIndex()
    {
        return $this->countTimes() - 1;
    }

    public function elapsed()
    {
        return $this->getLastTime() - $this->getFirstTime();
    }

    public function times()
    {
        return static::getInstance()->getTimes();
    }

    public function clearTimes()
    {
        $this->times = [];
        $this->names = [];
        return $this->pushTime($this->getStartTimeName());
    }

    public function hasTime($index)
    {
        return isset($this->times[$index]);
    }

    public function getTime($index)
    {
        if ($this->hasTime($index)) {
            return (is_int($index)) ? $this->times[$index] : $this->getNamedTime();
        }
        return 0;
    }

    public function getNamedIndex($name)
    {
        return $this->names[$name];
    }

    public function getIndexName($index)
    {
        return array_flip($this->names)[$index];
    }

    public function getNamedTime($name)
    {
        return $this->times[$this->getNamedIndex($name)];
    }

    public function getDiff($firstIndex, $secondIndex = 0)
    {
        return $this->getTime($firstIndex) - $this->getTime($secondIndex);
    }

    public function asDiffTable()
    {
        $results = [];
        foreach ($this->times as $index => $time) {
            $lastDiff = (count($results) > 0) ? $this->getDiff($index, ($index - 1)) : 0;
            $startDiff = $this->getDiff($index);
            $results[$this->getIndexName($index)] = [
                'time' => $time,
                'diff' => $lastDiff,
                'start_diff' => $startDiff
            ];
        }
        return $results;
    }

    public function __toString()
    {
        return $this->asDiffTable();
    }

    public static function clear()
    {
        return static::getInstance()->clearTimes();
    }

    public static function tick($name)
    {
        return static::getInstance()->pushTime($name);
    }

    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}
