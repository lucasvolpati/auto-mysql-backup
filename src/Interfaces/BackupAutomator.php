<?php

namespace ChronoDB\Interfaces;

interface BackupAutomator
{
    public function makeDumps(): void;
    public function createZipFile(string $command): bool;
}