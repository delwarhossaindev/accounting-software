<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:run {--only-db : Backup only the database} {--keep=30 : Days of backups to retain}';
    protected $description = 'Backup database (and optionally storage/uploads) to storage/app/backups';

    public function handle(): int
    {
        $backupDir = storage_path('app/backups');
        File::ensureDirectoryExists($backupDir);

        $timestamp = now()->format('Y-m-d_His');
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        $archive = $backupDir . DIRECTORY_SEPARATOR . "backup_{$timestamp}.zip";
        $zip = new ZipArchive();
        if ($zip->open($archive, ZipArchive::CREATE) !== true) {
            $this->error('Failed to create zip archive.');
            return 1;
        }

        if ($dbConfig['driver'] === 'sqlite') {
            $dbPath = $dbConfig['database'];
            if (!File::exists($dbPath)) {
                $this->error("SQLite file not found at {$dbPath}");
                $zip->close();
                return 1;
            }
            $zip->addFile($dbPath, 'database.sqlite');
            $this->info("Added SQLite database ({$dbPath})");
        } elseif ($dbConfig['driver'] === 'mysql') {
            $dumpFile = $backupDir . DIRECTORY_SEPARATOR . "dump_{$timestamp}.sql";
            $cmd = sprintf(
                'mysqldump -h%s -u%s %s %s > %s',
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['username']),
                !empty($dbConfig['password']) ? '-p' . escapeshellarg($dbConfig['password']) : '',
                escapeshellarg($dbConfig['database']),
                escapeshellarg($dumpFile)
            );
            exec($cmd, $out, $code);
            if ($code !== 0) {
                $this->error('mysqldump failed. Is it on PATH?');
                $zip->close();
                return 1;
            }
            $zip->addFile($dumpFile, 'database.sql');
            $this->info("Added MySQL dump");
        } else {
            $this->warn("Unsupported database driver: {$dbConfig['driver']}");
        }

        if (!$this->option('only-db')) {
            $storagePath = storage_path('app/public');
            if (File::isDirectory($storagePath)) {
                $this->addDirectoryToZip($zip, $storagePath, 'storage/public');
                $this->info('Added storage/app/public');
            }
        }

        $zip->close();

        if (isset($dumpFile) && File::exists($dumpFile)) {
            File::delete($dumpFile);
        }

        $size = number_format(filesize($archive) / 1024 / 1024, 2);
        $this->info("Backup created: {$archive} ({$size} MB)");

        $this->cleanupOldBackups($backupDir, (int) $this->option('keep'));

        return 0;
    }

    private function addDirectoryToZip(ZipArchive $zip, string $source, string $prefix): void
    {
        $source = rtrim($source, '/\\');
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;
            $relative = $prefix . '/' . substr($file->getPathname(), strlen($source) + 1);
            $zip->addFile($file->getPathname(), $relative);
        }
    }

    private function cleanupOldBackups(string $dir, int $keepDays): void
    {
        $cutoff = now()->subDays($keepDays)->timestamp;
        foreach (glob($dir . DIRECTORY_SEPARATOR . 'backup_*.zip') as $file) {
            if (filemtime($file) < $cutoff) {
                File::delete($file);
                $this->line('Removed old backup: ' . basename($file));
            }
        }
    }
}
