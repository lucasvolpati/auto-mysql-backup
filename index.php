<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

use ChronoDB\Core\Automator;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$auto = new Automator(env('DB_HOST'), env('DB_USER'), env('DB_PASS'), env('ZIP_PASS'));

$auto->makeBackups();
