<?php

namespace AutoMysqlBackup\Core;

use \PDO;
use \PDOException;

class Connect 
{
    public static PDO $instance;

    public function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    public static function getConnection(string $dbHost, string $dbUser, string $dbPass): PDO
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                throw new PDOException("Erro na conexão: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
