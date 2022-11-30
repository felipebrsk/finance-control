<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{App, Storage};
use App\Interfaces\Services\S3ServiceInterface;

class S3Service implements S3ServiceInterface
{
    /**
     * Create a file in s3 server.
     *
     * @param object $file
     * @param string $folder
     * @param string $visibility
     * @return string
     */
    public function create(object $file, string $folder, string $visibility = 'public'): string
    {
        return App::runningUnitTests() || App::runningInConsole() ?
            Storage::fake('amazonAws')->put($folder, $file, $visibility) :
            Storage::put($folder, $file, $visibility);
    }

    /**
     * Delete file from s3 server.
     *
     * @param string $path
     * @param ?string $folder
     * @param string $visibility
     * @return void
     */
    public function delete(string $path, ?string $folder = ''): void
    {
        $path = $folder ? $folder . '/' . $path : $path;

        App::runningInConsole() || App::runningUnitTests() ?
            Storage::fake('amazonAws')->delete($path) :
            Storage::delete($path);
    }

    /**
     * Get a file s3 path.
     * 
     * @param ?string $path
     * @return ?string
     */
    public function getPath(?string $path): ?string
    {
        if ($path) {
            return App::runningUnitTests() || App::runningInConsole() ?
                $path :
                Storage::temporaryUrl($path, Carbon::now()->addHours(2));
        }
        return null;
    }
}
