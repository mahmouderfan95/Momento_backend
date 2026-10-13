<?php
namespace App\Services\Admin;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
class ImageService
{
    private const MAX_WIDTH = 1200;
    private const MAX_HEIGHT = 1200;
    private const QUALITY = 80;
    public function __construct()
    {
        $this->manager = new ImageManager(
            new Driver()
        );
    }
    public function store(
        UploadedFile $file,
        string $directory = 'uploads/products'
    ): string {
        $image = $this->manager->read($file->getRealPath());

        /*
         * Resize only if image is larger than our maximum dimensions.
         * This keeps smaller images at their original dimensions.
         */
        $image->scaleDown(
            width: self::MAX_WIDTH,
            height: self::MAX_HEIGHT
        );

        /*
         * Generate WebP.
         */
        $encoded = $image->toWebp(
            quality: self::QUALITY
        );

        /*
         * Generate unique filename.
         */
        $filename = sprintf(
            '%s_%s.webp',
            now()->format('Ymd_His'),
            Str::random(20)
        );

        $path = trim($directory, '/') . '/' . $filename;

        /*
         * Store only the optimized WebP.
         */
        Storage::disk('public')->put(
            $path,
            (string) $encoded
        );

        return $filename;
    }
    public function delete(
        ?string $filename,
        string $directory
    ): void {
        if (empty($filename)) {
            return;
        }

        $path = trim($directory, '/') . '/' . $filename;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
    public function storeMany(
        array $files,
        string $directory = 'uploads/products/media'
    ): array {
        $uploads = [];

        foreach ($files as $file) {

            if (!$file instanceof UploadedFile || !$file->isValid()) {
                continue;
            }

            $filename = $this->store(
                $file,
                $directory
            );

            $path = trim($directory, '/') . '/' . $filename;

            $uploads[] = [
                'original_path' => $path,
                'media_path' => Storage::disk('public')->url($path),
                'file_name' => $filename,
                'file_extension' => 'webp',
                'is_main' => false,
                'status' => 'active',
            ];
        }

        return $uploads;
    }
}
