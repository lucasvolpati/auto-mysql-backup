<?php
declare(strict_types=1);

use ChronoDB\Base\Strategy\MysqlAutomator;
use PHPUnit\Framework\TestCase;

class MysqlAutomatorTest extends TestCase
{
    public string $backupDir = __DIR__ . '/../storage/test';

    public function testZipCreating()
    {
        $zipp = new MysqlAutomator();
        $result = $zipp->createZipFile($this->backupDir . '/test');
        $this->assertIsBool($result, 'yeah');
    }
}