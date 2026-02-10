<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first();
        return view('admin.about.index', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
        ]);

        $about = About::first();
        if (!$about) {
            $about = new About();
        }

        $data = $request->except(['image_1', 'image_2']);

        $uploadService = app(FileUploadService::class);
        $uploadService->setMaxDimensions(1200, 800);

        if ($request->hasFile('image_1')) {
            if ($about->image_1) {
                $uploadService->deleteImage('about/' . $about->image_1);
            }
            $result = $uploadService->uploadImage($request->file('image_1'), 'about');
            $data['image_1'] = $result['filename'];
        }

        if ($request->hasFile('image_2')) {
            if ($about->image_2) {
                $uploadService->deleteImage('about/' . $about->image_2);
            }
            $result = $uploadService->uploadImage($request->file('image_2'), 'about');
            $data['image_2'] = $result['filename'];
        }

        $about->fill($data);
        $about->save();

        return redirect()
            ->route('admin.about.index')
            ->with('success', 'Hakkımızda bilgileri güncellendi.');
    }
}
