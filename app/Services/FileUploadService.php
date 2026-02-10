<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    protected int $maxWidth = 1920;
    protected int $maxHeight = 1080;
    protected int $thumbnailWidth = 400;
    protected int $thumbnailHeight = 300;
    protected int $quality = 85;

    /**
     * Upload and optimize an image
     */
    public function uploadImage(UploadedFile $file, string $directory = 'uploads'): array
    {
        $filename = $this->generateFilename($file);
        $path = $directory . '/' . $filename;
        
        // Get image info
        $imageInfo = getimagesize($file->getPathname());
        $mimeType = $imageInfo['mime'] ?? $file->getMimeType();
        
        // Create image resource based on mime type
        $sourceImage = $this->createImageFromFile($file->getPathname(), $mimeType);
        
        if (!$sourceImage) {
            \Log::error('FileUploadService: Failed to create image resource', ['mime' => $mimeType, 'path' => $file->getPathname()]);
            // If image processing fails, just store the original
            $storedPath = $file->storeAs($directory, $filename, 'public');
            return [
                'path' => $storedPath,
                'url' => Storage::url($storedPath),
                'filename' => $filename,
                'thumbnail' => null,
            ];
        }
        
        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);
        
        // Resize if necessary
        $resizedImage = $this->resizeImage($sourceImage, $originalWidth, $originalHeight, $this->maxWidth, $this->maxHeight);
        
        // Save optimized image
        $storedPath = 'public/' . $path;
        $fullPath = storage_path('app/' . $storedPath);
        
        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                \Log::error('FileUploadService: Failed to create directory', ['dir' => $dir]);
                throw new \Exception("Failed to create directory: $dir");
            }
        }
        
        $this->saveImage($resizedImage, $fullPath, $mimeType);
        
        // Create thumbnail
        $thumbnailFilename = 'thumb_' . $filename;
        $thumbnailPath = $directory . '/' . $thumbnailFilename;
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
        
        $thumbnailImage = $this->resizeImage($sourceImage, $originalWidth, $originalHeight, $this->thumbnailWidth, $this->thumbnailHeight, true);
        $this->saveImage($thumbnailImage, $thumbnailFullPath, $mimeType);
        
        // Clean up
        imagedestroy($sourceImage);
        if ($resizedImage !== $sourceImage) {
            imagedestroy($resizedImage);
        }
        if ($thumbnailImage !== $sourceImage) {
            imagedestroy($thumbnailImage);
        }
        
        return [
            'path' => $path,
            'url' => Storage::url($path),
            'filename' => $filename,
            'thumbnail' => $thumbnailPath,
            'thumbnail_url' => Storage::url($thumbnailPath),
        ];
    }

    /**
     * Upload multiple images
     */
    public function uploadMultipleImages(array $files, string $directory = 'uploads'): array
    {
        $results = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $results[] = $this->uploadImage($file, $directory);
            }
        }
        
        return $results;
    }

    /**
     * Delete an image and its thumbnail
     */
    public function deleteImage(string $path): bool
    {
        $deleted = Storage::disk('public')->delete($path);
        
        // Try to delete thumbnail
        $thumbnailPath = dirname($path) . '/thumb_' . basename($path);
        Storage::disk('public')->delete($thumbnailPath);
        
        return $deleted;
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        return Str::uuid() . '.' . strtolower($extension);
    }

    /**
     * Create image resource from file
     */
    protected function createImageFromFile(string $path, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($path);
                }
                return false;
            default:
                return false;
        }
    }

    /**
     * Resize image maintaining aspect ratio
     */
    protected function resizeImage($sourceImage, int $originalWidth, int $originalHeight, int $maxWidth, int $maxHeight, bool $crop = false)
    {
        // Calculate new dimensions
        if ($crop) {
            // Crop to fit (for thumbnails)
            $ratio = max($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int) ($originalWidth * $ratio);
            $newHeight = (int) ($originalHeight * $ratio);
            $srcX = (int) (($newWidth - $maxWidth) / 2 / $ratio);
            $srcY = (int) (($newHeight - $maxHeight) / 2 / $ratio);
            $srcWidth = (int) ($maxWidth / $ratio);
            $srcHeight = (int) ($maxHeight / $ratio);
            
            $newImage = imagecreatetruecolor($maxWidth, $maxHeight);
            $this->preserveTransparency($newImage, $sourceImage);
            
            imagecopyresampled($newImage, $sourceImage, 0, 0, $srcX, $srcY, $maxWidth, $maxHeight, $srcWidth, $srcHeight);
        } else {
            // Resize to fit within bounds
            if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
                return $sourceImage;
            }
            
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int) ($originalWidth * $ratio);
            $newHeight = (int) ($originalHeight * $ratio);
            
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            $this->preserveTransparency($newImage, $sourceImage);
            
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        }
        
        return $newImage;
    }

    /**
     * Preserve transparency for PNG/GIF
     */
    protected function preserveTransparency($newImage, $sourceImage): void
    {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, imagesx($newImage), imagesy($newImage), $transparent);
    }

    /**
     * Save image to file
     */
    protected function saveImage($image, string $path, string $mimeType): bool
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagejpeg($image, $path, $this->quality);
            case 'image/png':
                return imagepng($image, $path, 9 - round($this->quality / 10));
            case 'image/gif':
                return imagegif($image, $path);
            case 'image/webp':
                if (function_exists('imagewebp')) {
                    return imagewebp($image, $path, $this->quality);
                }
                return imagejpeg($image, $path, $this->quality);
            default:
                return imagejpeg($image, $path, $this->quality);
        }
    }

    /**
     * Set max dimensions for images
     */
    public function setMaxDimensions(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }

    /**
     * Set thumbnail dimensions
     */
    public function setThumbnailDimensions(int $width, int $height): self
    {
        $this->thumbnailWidth = $width;
        $this->thumbnailHeight = $height;
        return $this;
    }

    /**
     * Set image quality (1-100)
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(1, min(100, $quality));
        return $this;
    }
}
