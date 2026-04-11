<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CompressProductImages extends Command
{
    protected $signature = 'images:compress-products
                            {--path=products : Folder inside storage/app/public}
                            {--min-kib=150 : Compress only files larger than this size in KiB}
                            {--quality=75 : WebP quality}
                            {--limit=100 : Max number of images to process per run}';

    protected $description = 'Compress product images without changing name or dimensions';

    public function handle(): int
    {
        $manager = new ImageManager(new Driver());

        $folder = trim((string) $this->option('path'), '/');
        $minKib = (int) $this->option('min-kib');
        $quality = (int) $this->option('quality');
        $limit = (int) $this->option('limit');

        if ($minKib < 1) {
            $this->error('min-kib must be at least 1.');
            return self::FAILURE;
        }

        if ($quality < 1 || $quality > 100) {
            $this->error('quality must be between 1 and 100.');
            return self::FAILURE;
        }

        if ($limit < 1) {
            $this->error('limit must be at least 1.');
            return self::FAILURE;
        }

        $allFiles = Storage::disk('public')->allFiles($folder);

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        $files = collect($allFiles)
            ->filter(function (string $file) use ($allowedExtensions) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return in_array($ext, $allowedExtensions, true);
            })
            ->values();

        $this->info('Found ' . $files->count() . ' image files in ' . $folder);

        $processed = 0;
        $skippedSmall = 0;
        $skippedErrors = 0;
        $saved = 0;

        foreach ($files as $file) {
            if ($processed >= $limit) {
                $this->warn("Reached limit of {$limit} files for this run.");
                break;
            }

            $absolutePath = storage_path('app/public/' . $file);

            if (!file_exists($absolutePath)) {
                $this->warn("Missing file: {$file}");
                continue;
            }

            $originalBytes = filesize($absolutePath);

            if ($originalBytes === false) {
                $this->warn("Could not read size: {$file}");
                $skippedErrors++;
                continue;
            }

            $originalKib = $originalBytes / 1024;

            if ($originalKib <= $minKib) {
                $skippedSmall++;
                continue;
            }

           try {
    $image = $manager->read($absolutePath);

$encoded = $image->toWebp($quality);
$encodedBinary = (string) $encoded;
$encodedBytes = strlen($encodedBinary);

// Only overwrite if the new file is actually smaller
if ($encodedBytes >= $originalBytes) {
    $this->line(
        "{$file} | " .
        round($originalKib, 2) . " KiB -> skipped (no improvement)"
    );
    continue;
}

// Save optimized version
file_put_contents($absolutePath, $encodedBinary);

clearstatcache(true, $absolutePath);

$newBytes = filesize($absolutePath);
$newKib = $newBytes !== false ? ($newBytes / 1024) : 0;

$savedBytes = $originalBytes - ($newBytes ?: 0);

$this->line(
    "{$file} | " .
    round($originalKib, 2) . " KiB -> " .
    round($newKib, 2) . " KiB | saved " .
    round($savedBytes / 1024, 2) . " KiB"
);

$processed++;
$saved += $savedBytes;
} catch (\Throwable $e) {
    $this->error("Failed: {$file} | {$e->getMessage()}");
    $skippedErrors++;
}
        }

        $this->newLine();
        $this->info("Processed: {$processed}");
        $this->info("Skipped (<= {$minKib} KiB): {$skippedSmall}");
        $this->info("Errors: {$skippedErrors}");
        $this->info('Total saved: ' . round($saved / 1024 / 1024, 2) . ' MiB');

        return self::SUCCESS;
    }
}