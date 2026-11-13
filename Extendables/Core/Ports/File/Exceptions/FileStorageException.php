<?php

namespace App\Extendables\Core\Ports\File\Exceptions;

use App\Extendables\Core\Http\Exception\ExtendableException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Thrown by file storage ports when a storage operation fails.
 *
 * The exception message carries operation details (e.g. paths) for logging, while the HTTP error message
 * stays generic so storage internals are not exposed to API clients.
 */
class FileStorageException extends Exception implements ExtendableException
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function unableToOpenRemoteStream(string $path): self
    {
        return new self("Unable to open remote stream for file: {$path}");
    }

    public static function unableToOpenLocalFile(string $path): self
    {
        return new self("Unable to open local file for writing: {$path}");
    }

    public function httpStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function httpErrorCode(): string
    {
        return 'file_storage_error';
    }

    public function httpErrorMessage(): string
    {
        return 'A file storage operation failed.';
    }
}
