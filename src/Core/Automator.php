<?php

namespace ChronoDB\Core;

use ChronoDB\Core\Connect;
use React\ChildProcess\Process;
use ChronoDB\Base\Log;

class Automator {

    protected array $ignoreBases = [
        'mysql', 
        'information_schema', 
        'performance_schema'
    ];

    public string $backupDir = __DIR__ . '/../../backups';

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

    private function getDbList(): array
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

    private function getCommands()
    {
        $dbList = $this->getDbList();

        $group = [];
        foreach ($dbList as $base) {
            $backupFile = "$this->backupDir/" . $base . '.sql';
            $dbUser = env('MYSQL_USER');
            $dbPass = env('MYSQL_PASS');
            $dbHost = env('MYSQL_HOST');

            $command = sprintf(
                "mysqldump -h %s -u %s -p'%s' --single-transaction --skip-triggers %s > %s",
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($base),
                escapeshellarg($backupFile),
            );

            $group[$base] = ["command" => $command, "backupFile" => $backupFile];
        }

        return $group;
    }
    public function makeBackups()
    {
        $commands = $this->getCommands();
        $backupDir = $this->backupDir;
        $zipPass = env('ZIP_PASS');

        Log::info('<<< INICIANDO PROCESSO >>>');
        foreach ($commands as $baseName => $dataBaseItem) {
            $process = new Process($dataBaseItem['command']);
            $process->start();
            
            $process->on('exit', function ($code, $term) use ($backupDir, $baseName, $zipPass, $dataBaseItem) {
                if ($code !== 0) {
                    Log::error("Processo falhou com código $code e sinal $term");
                    return;
                }

                $fileZipped = date('d-m-Y_H-i-s')."-$baseName.zip";
        
                $this->generateZip("zip -jP $zipPass $backupDir/$fileZipped {$dataBaseItem['backupFile']}");
    
            });
        }

        Log::info('Backup finalizado');
    }

    private function generateZip($command)
    {
        if (!exec($command)) {
            Log::error("Não foi possível criar arquivo zip final!", [$command]);
            return false;
        }
        
        Log::error("Arquivo zip final foi criado com sucesso!", [$command]);
        return true;
    
    }
}