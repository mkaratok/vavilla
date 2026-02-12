<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery;
use App\Models\PhotoGalleryImage;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PhotoGalleryController extends Controller
{
    public function index()
    {
        $galleries = PhotoGallery::withCount('images')->orderBy('id', 'desc')->paginate(20);
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['sef'] = Str::slug($request->baslik);

        $gallery = PhotoGallery::create($data);

        return redirect()
            ->route('admin.gallery.edit', $gallery)
            ->with('success', 'Galeri oluşturuldu. Şimdi resim ekleyebilirsiniz.');
    }

    public function edit(PhotoGallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, PhotoGallery $gallery)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['sef'] = Str::slug($request->baslik);

        $gallery->update($data);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Galeri güncellendi.');
    }

    public function destroy(PhotoGallery $gallery)
    {
        foreach ($gallery->images as $image) {
            $this->deleteImage($image);
        }
        
        $gallery->delete();

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Galeri silindi.');
    }

    public function uploadImage(Request $request, PhotoGallery $gallery)
    {
        // Increase memory limit for large image processing
        ini_set('memory_limit', '1024M');

        $request->validate([
            'images.*' => 'required|image|max:20480', // 20MB per file - reduced for memory safety
        ]);

        $uploadService = app(FileUploadService::class);
        $uploadService->setMaxDimensions(1920, 1280);
        $uploadService->setThumbnailDimensions(400, 300);
        
        $files = $request->file('images');
        $uploadedCount = 0;
        $errors = [];

        if ($request->hasFile('images')) {
            foreach($files as $file) {
                try {
                    // Check file size and dimensions before processing
                    $imageInfo = @getimagesize($file->getPathname());
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                        // If image is very large, estimate memory needed (4 bytes per pixel * 2 for processing)
                        $estimatedMemory = $width * $height * 4 * 2;
                        if ($estimatedMemory > 500 * 1024 * 1024) { // 500MB
                            \Log::warning('Image too large for memory processing', [
                                'width' => $width,
                                'height' => $height,
                                'estimated_memory' => $estimatedMemory
                            ]);
                            $errors[] = $file->getClientOriginalName() . ' çok büyük boyutlu (önerilen max: 4000x4000 piksel)';
                            continue;
                        }
                    }

                    $result = $uploadService->uploadImage($file, 'gallery');

                    // If thumbnail wasn't created, use the original filename for kresim too
                    $kresim = $result['thumbnail'] ? basename($result['thumbnail']) : $result['filename'];

                    PhotoGalleryImage::create([
                        'gallery_id' => $gallery->id,
                        'bresim' => $result['filename'],
                        'kresim' => $kresim,
                    ]);
                    $uploadedCount++;
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                    $errors[] = $file->getClientOriginalName() . ' yüklenemedi: ' . $e->getMessage();
                    continue;
                }
            }
        }

        $message = $uploadedCount . ' resim başarıyla yüklendi.';
        if (count($errors) > 0) {
            $message .= ' Hatalar: ' . implode(', ', $errors);
        }

        return response()->json([
            'success' => $uploadedCount > 0,
            'message' => $message
        ]);
    }

    public function deleteImage(PhotoGalleryImage $image)
    {
        $uploadService = app(FileUploadService::class);
        $uploadService->deleteImage('gallery/' . $image->bresim);
        $uploadService->deleteImage('gallery/' . $image->kresim);
        
        $image->delete();

        return response()->json(['success' => true]);
    }
}
