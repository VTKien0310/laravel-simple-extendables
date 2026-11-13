<?php

namespace App\Extendables\Core\Ports\File;

use DateTimeInterface;

/**
 * File storage port backed by Laravel's `local` disk.
 *
 * Temporary URLs require the `local` disk to be configured with `'serve' => true`
 * (the default since Laravel 11), otherwise Laravel throws a RuntimeException.
 *
 * @see https://laravel.com/docs/filesystem#the-local-driver
 */
class LocalFileStoragePort extends FlysystemFileStoragePort
{
    public function __construct(
        string $workDir = '',
        int $defaultTempUrlDuration = 30
    ) {
        parent::__construct('local', $workDir, $defaultTempUrlDuration);
    }

    /**
     * {@inheritDoc}
     *
     * Note: the local disk ignores temporary URL options, so the download filename and
     * content type cannot be enforced through the URL. Use download() when a forced
     * attachment name is required.
     */
    public function makeDownloadUrlForPath(
        string $path,
        string $filename,
        ?string $contentType = null,
        int|DateTimeInterface|null $duration = null,
        array $options = [],
        bool $isWorkDirPath = false
    ): string {
        return parent::makeDownloadUrlForPath($path, $filename, $contentType, $duration, $options, $isWorkDirPath);
    }
}
