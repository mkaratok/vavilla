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
        ini_set('memory_limit', '512M');
        ini_set('post_max_size', '512M');
        ini_set('upload_max_filesize', '512M');

        $request->validate([
            'images.*' => 'required|image|max:102400', // 100MB per file
        ]);

        $uploadService = app(FileUploadService::class);
        $uploadService->setMaxDimensions(1920, 1280);
        $uploadService->setThumbnailDimensions(400, 300);
        
        $files = $request->file('images');
        $uploadedCount = 0;

        if ($request->hasFile('images')) {
            foreach($files as $file) {
                try {
                    $result = $uploadService->uploadImage($file, 'gallery');

                    PhotoGalleryImage::create([
                        'gallery_id' => $gallery->id,
                        'bresim' => $result['filename'],
                        'kresim' => $result['thumbnail'] ? basename($result['thumbnail']) : $result['filename'],
                    ]);
                    $uploadedCount++;
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                    continue;
                }
            }
        }

        return response()->json([
            'success' => true, 
            'message' => $uploadedCount . ' resim başarıyla yüklendi.'
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
