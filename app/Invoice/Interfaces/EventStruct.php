<?php
namespace Invoice\Interfaces;

interface EventStruct
{
    public function track(string $classPath): void;
    public function flush(): void;
}