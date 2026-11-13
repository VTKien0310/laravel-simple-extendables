<?php

namespace App\Extendables\Core\Ports\File;

class S3FileStoragePort extends FlysystemFileStoragePort
{
    public function __construct(
        string $workDir = '',
        int $defaultTempUrlDuration = 30
    ) {
        parent::__construct('s3', $workDir, $defaultTempUrlDuration);
    }

    /**
     * {@inheritDoc}
     */
    protected function downloadResponseOptions(string $filename, ?string $contentType = null): array
    {
        $responseOptions = [
            // Encoding the filename with rawurlencode ensures the filename is always valid with ISO-8859-1.
            'ResponseContentDisposition' => 'filename='.rawurlencode($filename),
        ];

        if ($contentType) {
            $responseOptions['ResponseContentType'] = $contentType;
        }

        return $responseOptions;
    }
}
