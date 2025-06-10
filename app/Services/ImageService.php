<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageService
{
    private array $allowedMimes = ['jpeg', 'jpg', 'png', 'webp'];
    private int $maxFileSize = 2048; // KB
    private array $thumbnailSizes = [
        'small' => [150, 150],
        'medium' => [300, 300],
        'large' => [600, 600],
    ];

    public function uploadImage(UploadedFile $file, string $directory = 'images'): ?string
    {
        if (!$this->validateImage($file)) {
            return null;
        }

        $filename = $this->generateFilename($file);
        $path = $directory . '/' . $filename;

        try {
            // Store original image
            $stored = Storage::disk('public')->put($path, file_get_contents($file));
            
            if ($stored) {
                // Generate thumbnails
                $this->generateThumbnails($file, $directory, $filename);
                return $path;
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Image upload failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function deleteImage(string $path): bool
    {
        try {
            // Delete original image
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Delete thumbnails
            $this->deleteThumbnails($path);

            return true;
        } catch (\Exception $e) {
            \Log::error('Image deletion failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getImageUrl(string $path, string $size = 'original'): string
    {
        if ($size === 'original') {
            return Storage::disk('public')->url($path);
        }

        $thumbnailPath = $this->getThumbnailPath($path, $size);
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }

        // Fallback to original if thumbnail doesn't exist
        return Storage::disk('public')->url($path);
    }

    private function validateImage(UploadedFile $file): bool
    {
        if (!$file->isValid()) {
            return false;
        }

        if ($file->getSize() > $this->maxFileSize * 1024) {
            return false;
        }

        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, $this->allowedMimes);
    }

    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    private function generateThumbnails(UploadedFile $file, string $directory, string $filename): void
    {
        try {
            $image = Image::make($file);

            foreach ($this->thumbnailSizes as $size => [$width, $height]) {
                $thumbnail = clone $image;
                $thumbnail->fit($width, $height);
                
                $thumbnailPath = $directory . '/thumbnails/' . $size . '/' . $filename;
                Storage::disk('public')->put($thumbnailPath, (string) $thumbnail->encode());
            }
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed', ['error' => $e->getMessage()]);
        }
    }

    private function deleteThumbnails(string $originalPath): void
    {
        $filename = basename($originalPath);
        $directory = dirname($originalPath);

        foreach (array_keys($this->thumbnailSizes) as $size) {
            $thumbnailPath = $directory . '/thumbnails/' . $size . '/' . $filename;
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
    }

    private function getThumbnailPath(string $originalPath, string $size): string
    {
        $filename = basename($originalPath);
        $directory = dirname($originalPath);
        return $directory . '/thumbnails/' . $size . '/' . $filename;
    }

    public function optimizeImage(string $path): bool
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            $image = Image::make($fullPath);
            
            // Optimize quality
            $image->save($fullPath, 85);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Image optimization failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}