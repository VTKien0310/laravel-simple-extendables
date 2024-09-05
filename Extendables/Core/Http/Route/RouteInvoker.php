<?php

namespace App\Extendables\Core\Http\Route;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteInvoker
{
    public static function invokeResourceRoute(
        string $resource,
        string $filePath,
        string $fileNamePrefix,
        array $options = []
    ): void {
        $dirName = Str::studly($resource);

        $routeFileName = Str::snake($resource).'-routes.php';
        if ($fileNamePrefix) {
            $routeFileName = $fileNamePrefix.'-'.$routeFileName;
        }

        self::invokeRoute("$filePath/$dirName/$routeFileName", $options);
    }

    public static function invokeApiResourceRoute(string $resource, array $options = []): void
    {
        self::invokeResourceRoute($resource, 'Http/Api', 'api', $options);
    }

    public static function invokeWebRoute(string $resource, array $options = []): void
    {
        self::invokeResourceRoute($resource, 'Http/Web', 'web', $options);
    }

    public static function invokeRoute(string $filePath, array $options = []): void
    {
        Route::group($options, app_path($filePath));
    }
}
