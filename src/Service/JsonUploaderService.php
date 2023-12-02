<?php

namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;

class JsonUploaderService
{
    private const CHMOD = 0700;

    public function __construct(
        private $targetDirectory,
        private readonly Filesystem $filesystem
    )
    {}

    public function uploadJson(string $json, string $identify): void
    {
        $targetDirectory = $this->getTargetDirectory();
        $fileName = md5($identify.uniqid('', true)) . '.json';
        $this->checkDirectoryExistAndCreate($targetDirectory);

        file_put_contents($targetDirectory . '/' . $fileName, $json);
    }

    private function checkDirectoryExistAndCreate(string $targetDirectory): void
    {
        if (!$this->filesystem->exists($targetDirectory)) {
            $this->filesystem->mkdir($targetDirectory, self::CHMOD);
        }
    }

    private function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
