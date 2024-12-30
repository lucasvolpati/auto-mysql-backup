<?php

namespace ChronoDB\Base\Strategy;

use ChronoDB\Interfaces\BackupAutomator;
use ChronoDB\Core\Connect;
use React\ChildProcess\Process;
use ChronoDB\Base\Log;
use \DateTime;
use \ZipArchive;

class MysqlAutomator implements BackupAutomator
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
     * @param string $filePath | $this->backupDir/$currentFolderName/$fileName
     */
    public function createZipFile(string $filePath): bool
    {
        // ini_set('upload_tmp_dir', $this->backupDir . '/tmp');
        // ini_set('sys_temp_dir', $this->backupDir . '/tmp');
        Log::info('Diretório temporário usado pelo PHP:', ['tmp_dir' => sys_get_temp_dir()]);

        $currentFolderName = (new DateTime('now'))->format('Y-m-d');

        // $command = "zip -jP $zipPass $this->backupDir/$fileName.zip {$dataBaseItem['backupFile']}";

        $zip = new ZipArchive();

        // $file = "$this->backupDir/$currentFolderName/$fileName";
        

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
        
        Log::error("Arquivo zip final foi criado com sucesso!", [$filePath . '.zip']);
        return true;
    }

    public function setIgnoreDatabases(array $databases): void
    {
        $this->ignoreDatabases[] = $databases;
    }
}