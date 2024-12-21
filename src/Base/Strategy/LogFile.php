<?php

namespace ChronoDB\Base\Strategy;

use ChronoDB\Interfaces\Log;

class LogFile implements Log
{
    public function info(string $message, array $context): void
    {

    }

    public function debug(string $message, array $context): void
    {

    }

    public function warning(string $message, array $context): void
    {
        
    }

    public function error(string $message, array $context): void
    {
        
    }
}