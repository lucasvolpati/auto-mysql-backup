<?php

namespace AutoMysqlBackup\Base;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Log
{
    public static $log;

    private static function config()
    {
        $dateFormat = "Y-m-d, H:i:s";

        $output = "[%datetime%] > %level_name% > %message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat);

        $stream = new StreamHandler(__DIR__.'/../../logs/logfile.log', Level::Debug);
        $stream->setFormatter($formatter);

        self::$log = new Logger('Database-Backup');
        self::$log->pushHandler($stream);

        return self::$log;
    }

    public static function info(string $message, array $context = []): void
    {
        $log = self::config();
        $log->info($message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        $log = self::config();
        $log->debug($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        $log = self::config();
        $log->warning($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        $log = self::config();
        $log->error($message, $context);
    }
}
