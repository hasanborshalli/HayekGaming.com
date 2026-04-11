<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TestImageOptimize extends Command
{
    protected $signature = 'image:test';
    protected $description = 'Test optimizing one product image';

    public function handle()
    {
        $manager = new ImageManager(new Driver());

        // 👇 CHANGE THIS to one real image from your storage
        $inputPath = storage_path('app/public/products/Product-113361cd-0a61-4555-939f-55b396f5041a.webp');

        // 👇 output file (safe, new file)
        $outputPath = storage_path('app/public/products/test-optimized.webp');

        if (!file_exists($inputPath)) {
            $this->error("Image not found!");
            return;
        }

        $this->info("Original size: " . round(filesize($inputPath) / 1024, 2) . " KB");

        $image = $manager->read($inputPath);

        // Resize to max 800px
        $image->scaleDown(width: 800, height: 800);

        // Convert/compress to webp
        $image->toWebp(quality: 75)->save($outputPath);

        $this->info("Optimized saved!");

        $this->info("New size: " . round(filesize($outputPath) / 1024, 2) . " KB");
    }
}