<?php

class SystemCall{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler)
    {
        return $this->callback($task, $scheduler);
    }
    public function getTaskId() {
        return new SystemCall(function(Task $task,Scheduler $scheduler){
            $task->setSendValue($task->getTaskId());
            $scheduler->schedule($task);
        });
    }
}