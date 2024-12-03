<?php

namespace AutoMysqlBackup\Core;

use AutoMysqlBackup\Core\Connect;
use React\ChildProcess\Process;

class Automator {

    protected array $ignoreBases = [
        'mysql', 
        'information_schema', 
        'performance_schema'
    ];

    public function __construct(
        private string $dbHost,
        private string $dbUser,
        private string $dbPass,
        private string $zipPass
    )
    {}

    public function clearBackupsIfExists(string $backupsPath)
    {
        exec("cd $backupsPath");
        exec("rm -rf $backupsPath/*");
    }

    public function getDbList(): array
    {
        $dbList = [];
        $sql = "SHOW DATABASES";
        $con = Connect::getConnection($this->dbHost, $this->dbUser, $this->dbPass);

        $stmt = $con->prepare($sql);
        $stmt->execute();
        $databases = $stmt->fetchAll();

        foreach ($databases as $db) {
            if (!in_array($db['Database'], $this->ignoreBases)) {
                $dbList [] = $db['Database'];
            }
        }

        return $dbList;
    }
}