<?php
namespace Invoice\Interfaces;

interface ReflectorStruct
{
    public function __construct(string $classPath);
    public function getProperties(): array;
}