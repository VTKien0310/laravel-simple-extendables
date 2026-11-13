<?php

namespace App\Extendables\Core\Ports\File;

use App\Extendables\Core\Ports\File\Exceptions\FileStorageException;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Shared, disk-agnostic implementation of the FileStoragePort backed by a Laravel filesystem disk.
 * Concrete ports only need to provide the disk name and override disk-specific behaviour.
 */
abstract class FlysystemFileStoragePort implements FileStoragePort
{
    public function __construct(
        protected readonly string $diskName,
        protected string $workDir = '',
        protected int $defaultTempUrlDuration = 30
    ) {
    }

    public function setWorkDir(string $workDir): void
    {
        $this->workDir = $workDir;
    }

    /**
     * Get a new disk instance
     */
    protected function disk(): Filesystem|FilesystemAdapter
    {
        return Storage::disk($this->diskName);
    }

    public function getDiskName(): string
    {
        return $this->diskName;
    }

    public function getWorkDir(): string
    {
        return $this->workDir;
    }

    /**
     * Prefix the path with the working directory if requested
     */
    protected function resolvePath(string $path, bool $isWorkDirPath): string
    {
        return $isWorkDirPath ? "$this->workDir/$path" : $path;
    }

    /**
     * {@inheritDoc}
     */
    public function putFileAs(
        File|UploadedFile $file,
        string $name,
        ?string $extension = null,
        bool $isRelativeToWorkDir = true
    ): bool|string {
        $fileStoragePath = $isRelativeToWorkDir ? $this->workDir : '';

        if ($extension) {
            $name = "$name.$extension";
        }

        return $this->disk()->putFileAs($fileStoragePath, $file, $name);
    }

    public function putFile(File|UploadedFile $file, bool $isRelativeToWorkDir = true): bool|string
    {
        $fileStoragePath = $isRelativeToWorkDir ? $this->workDir : '';

        return $this->disk()->putFile($fileStoragePath, $file);
    }

    public function delete(string $path, bool $isWorkDirPath = false): bool
    {
        return $this->disk()->delete($this->resolvePath($path, $isWorkDirPath));
    }

    /**
     * {@inheritDoc}
     */
    public function makeTempUrlForPath(
        string $path,
        int|DateTimeInterface|null $duration = null,
        array $options = [],
        bool $isWorkDirPath = false
    ): string {
        return $this->disk()->temporaryUrl(
            $this->resolvePath($path, $isWorkDirPath),
            is_int($duration)
                ? now()->addMinutes($duration)
                : ($duration ?? now()->addMinutes($this->defaultTempUrlDuration)),
            $options
        );
    }

    public function makeUrlForPath(string $path, bool $isWorkDirPath = false): string
    {
        return $this->disk()->url($this->resolvePath($path, $isWorkDirPath));
    }

    public function get(string $path, bool $isWorkDirPath = true): string
    {
        return $this->disk()->get($this->resolvePath($path, $isWorkDirPath)) ?? '';
    }

    /**
     * {@inheritDoc}
     */
    public function putBinaryContentAs(
        string $file,
        string $name,
        ?string $extension = null,
        bool $isWorkDirPath = true
    ): string|bool {
        $path = $this->resolvePath($name, $isWorkDirPath);

        if ($extension) {
            $path = "$path.$extension";
        }

        if ($this->disk()->put($path, $file)) {
            return $path;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function download(
        string $path,
        ?string $name = null,
        array $headers = [],
        bool $isWorkDirPath = false
    ): StreamedResponse {
        return $this->disk()->download($this->resolvePath($path, $isWorkDirPath), $name, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function getFullPath(string $path, bool $isWorkDirPath = true): string
    {
        return $this->disk()->path($this->resolvePath($path, $isWorkDirPath));
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $filePath, bool $isWorkDirPath = true, ?string $fileExtension = null): bool
    {
        if ($fileExtension) {
            $filePath = "$filePath.$fileExtension";
        }

        return $this->disk()->exists($this->resolvePath($filePath, $isWorkDirPath));
    }

    /**
     * {@inheritDoc}
     */
    public function makeDownloadUrlForPath(
        string $path,
        string $filename,
        ?string $contentType = null,
        int|DateTimeInterface|null $duration = null,
        array $options = [],
        bool $isWorkDirPath = false
    ): string {
        $options = array_merge($options, $this->downloadResponseOptions($filename, $contentType));

        return $this->makeTempUrlForPath(
            path: $path,
            duration: $duration,
            options: $options,
            isWorkDirPath: $isWorkDirPath
        );
    }

    /**
     * Disk-specific options that make a temporary URL behave as a download.
     * Return an empty array when the disk does not support such options.
     *
     * @return array<string, string>
     */
    protected function downloadResponseOptions(string $filename, ?string $contentType = null): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function saveFileToLocal(string $path, string $localName, ?string $localDirectory = null, bool $isWorkDirPath = false): string
    {
        $localDirectory = $localDirectory ?? sys_get_temp_dir();
        $localPath = $localDirectory.'/'.$localName;

        $fileContent = $this->get($path, $isWorkDirPath);
        file_put_contents($localPath, $fileContent);

        return $localPath;
    }

    /**
     * {@inheritDoc}
     */
    public function streamFileToLocal(string $path, string $localName, ?string $localDirectory = null, bool $isWorkDirPath = false): string
    {
        $localDirectory = $localDirectory ?? sys_get_temp_dir();
        $localPath = $localDirectory.'/'.$localName;

        $resolvedPath = $this->resolvePath($path, $isWorkDirPath);

        $remoteStream = $this->disk()->readStream($resolvedPath);

        if ($remoteStream === null) {
            throw FileStorageException::unableToOpenRemoteStream($resolvedPath);
        }

        $localStream = fopen($localPath, 'w');

        if ($localStream === false) {
            fclose($remoteStream);
            throw FileStorageException::unableToOpenLocalFile($localPath);
        }

        try {
            stream_copy_to_stream($remoteStream, $localStream);
        } finally {
            fclose($remoteStream);
            fclose($localStream);
        }

        return $localPath;
    }
}
