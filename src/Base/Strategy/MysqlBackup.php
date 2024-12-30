<?php

namespace ChronoDB\Base\Strategy;

use ChronoDB\Interfaces\BackupAutomator;
use ChronoDB\Core\Connect;
use React\ChildProcess\Process;
use ChronoDB\Base\Log;
use \DateTime;
use \ZipArchive;

class MysqlBackup implements BackupAutomator
{
    public array $ignoreDatabases = [
        'mysql', 
        'information_schema', 
        'performance_schema'
    ];

    public string $backupDir = __DIR__ . '/../../../storage/mysql-mariadb';

    public function makeDumps(): void
    {
        
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function createZipFile(string $fileName): bool
    {       
        $currentFolderName = (new DateTime('now'))->format('Y-m-d');

        $dir = $this->getOrCreatePath("$this->backupDir/$currentFolderName");

        $filePath = "$dir/$fileName";

        $zip = new ZipArchive();

        if (!$zip->open($filePath . '.zip', ZipArchive::CREATE)) {
            Log::error("Não foi possível criar arquivo zip final!", [$filePath . '.zip']);
            return false;
        }

        if (!$zip->addFile($filePath . '.sql', basename($filePath . '.sql'))) {
            Log::error("Não foi possível adicionar o arquivo ao ZIP!", ['backupFile' => $filePath . '.sql']);
            $zip->close();
            return false;
        }

        $zip->setPassword('12345');
        $zip->setEncryptionName(basename($filePath . '.zip'), ZipArchive::EM_AES_256);
        $zip->close();
        
        Log::info("Arquivo zip final foi criado com sucesso!", [$filePath . '.zip']);
        return true;
    }

    /**
     * @param string $path
     * @return string
     */
    private function getOrCreatePath(string $path): string
    {
        if(!file_exists($path)) {
            mkdir($path);
        }

        return $path;
    }

    /**
     * @return void
     */
    public function setIgnoreDatabases(array $databases): void
    {
        $this->ignoreDatabases[] = $databases;
    }
}
