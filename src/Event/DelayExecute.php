<?php


namespace Soen\Delay\Alone\Event;


class DelayExecute
{
    public $job;
    public $test = 'this is test';
    public function __construct($job)
    {
        $this->job = $job;
    }
}