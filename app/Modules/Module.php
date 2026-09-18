<?php

namespace App\Modules;

abstract class Module
{
    abstract public function slug(): string;

    abstract public function name(): string;

    public function description(): string
    {
        return '';
    }

    public function icon(): string
    {
        return '';
    }

    public function price(): int
    {
        return 0;
    }

    public function dependencies(): array
    {
        return [];
    }
}