<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use ChronoDB\Base\Strategy\MysqlBackup;

Dotenv\Dotenv::createImmutable(__DIR__)->load();

$test = new MysqlBackup();

$test->createZipFile('test');