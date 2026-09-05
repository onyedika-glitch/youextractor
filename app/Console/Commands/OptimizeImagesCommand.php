<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager; // Will be optional

class OptimizeImagesCommand extends Command
{
    protected $signature = 'images:optimize 
                            {--path=public/img : Base path to optimize} 
                            {--max-width=1280 : Max width for resizing}
                            {--webp : Also generate WebP versions when possible}';

    protected $description = 'Optimize and resize marketing images (screenshots, generated hero assets) for better SEO & Core Web Vitals.';

    public function handle(): int
    {
        $basePath = base_path($this->option('path'));
        $maxWidth = (int) $this->option('max-width');
        $generateWebp = $this->option('webp');

        if (!File::isDirectory($basePath)) {
            $this->error("Path not found: {$basePath}");
            return 1;
        }

        $this->info("Optimizing images in: {$basePath}");
        $this->info("Max width: {$maxWidth}px" . ($generateWebp ? ' + WebP versions' : ''));

        $files = File::allFiles($basePath);
        $optimized = 0;

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (!in_array($ext, ['png', 'jpg', 'jpeg'])) {
                continue;
            }

            $fullPath = $file->getRealPath();
            $relative = $file->getRelativePathname();

            // Skip already optimized or tiny files
            if (str_contains($relative, 'optimized') || $file->getSize() < 50_000) {
                continue;
            }

            $this->line("Processing: {$relative}");

            try {
                // Simple GD resize (no extra deps)
                $info = getimagesize($fullPath);
                if (!$info) continue;

                [$width, $height] = $info;
                if ($width <= $maxWidth) {
                    $this->comment("  → Already small enough");
                    continue;
                }

                $newHeight = intval($height * ($maxWidth / $width));

                $src = match ($ext) {
                    'png' => imagecreatefrompng($fullPath),
                    default => imagecreatefromjpeg($fullPath),
                };

                $dst = imagecreatetruecolor($maxWidth, $newHeight);

                if ($ext === 'png') {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);

                // Save optimized version next to original
                $optimizedPath = $file->getPath() . '/' . $file->getFilenameWithoutExtension() . '-opt.' . $ext;

                if ($ext === 'png') {
                    imagepng($dst, $optimizedPath, 7);
                } else {
                    imagejpeg($dst, $optimizedPath, 82);
                }

                imagedestroy($src);
                imagedestroy($dst);

                $this->info("  → Saved optimized: " . basename($optimizedPath) . " (" . round(filesize($optimizedPath)/1024) . "KB)");

                // Optional WebP
                if ($generateWebp && function_exists('imagewebp')) {
                    $webpPath = $file->getPath() . '/' . $file->getFilenameWithoutExtension() . '.webp';
                    $webp = imagecreatetruecolor($maxWidth, $newHeight);
                    imagecopyresampled($webp, $dst, 0, 0, 0, 0, $maxWidth, $newHeight, $maxWidth, $newHeight); // reuse last dst? better recreate

                    // Simpler: re-open the opt and convert
                    $webpSuccess = false;
                    if ($ext === 'png') {
                        $srcPng = imagecreatefrompng($optimizedPath);
                        $webpSuccess = imagewebp($srcPng, $webpPath, 80);
                        imagedestroy($srcPng);
                    } else {
                        $srcJpg = imagecreatefromjpeg($optimizedPath);
                        $webpSuccess = imagewebp($srcJpg, $webpPath, 80);
                        imagedestroy($srcJpg);
                    }

                    if ($webpSuccess) {
                        $this->info("  → WebP created: " . basename($webpPath));
                    }
                }

                $optimized++;
            } catch (\Throwable $e) {
                $this->error("  Failed: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Done. Optimized {$optimized} images.");
        $this->comment("Tip: Update your <img> tags or add a helper to prefer -opt / .webp versions.");

        return 0;
    }
}