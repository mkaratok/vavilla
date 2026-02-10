<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class HomePageServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sira')->paginate(20);
        return view('admin.services_home.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services_home.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'resim' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['resim']);

        if ($request->hasFile('resim')) {
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(800, 600);
            $result = $uploadService->uploadImage($request->file('resim'), 'services');
            $data['resim'] = $result['filename'];
        }

        Service::create($data);

        return redirect()
            ->route('admin.services-home.index')
            ->with('success', 'Hizmet başarıyla eklendi.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services_home.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $request->validate([
            'baslik' => 'required|string|max:255',
            'resim' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['resim']);

        if ($request->hasFile('resim')) {
            if ($service->resim) {
                app(FileUploadService::class)->deleteImage('services/' . $service->resim);
            }
            
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(800, 600);
            $result = $uploadService->uploadImage($request->file('resim'), 'services');
            $data['resim'] = $result['filename'];
        }

        $service->update($data);

        return redirect()
            ->route('admin.services-home.index')
            ->with('success', 'Hizmet başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        if ($service->resim) {
            app(FileUploadService::class)->deleteImage('services/' . $service->resim);
        }
        
        $service->delete();

        return redirect()
            ->route('admin.services-home.index')
            ->with('success', 'Hizmet başarıyla silindi.');
    }
}
