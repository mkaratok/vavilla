<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first() ?? new Setting();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = Setting::first();
        
        if (!$settings) {
            $settings = new Setting();
        }

        $data = $request->except('_token', '_method', 'logo', 'favicon', 'footer_logo', 'anasayfa_video');
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(400, 120);
            $result = $uploadService->uploadImage($request->file('logo'), 'settings');
            $data['logo'] = $result['path'];
        }
        
        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $path = $request->file('favicon')->store('settings', 'public');
            $data['favicon'] = $path;
        }
        
        // Handle footer logo upload
        if ($request->hasFile('footer_logo')) {
            if ($settings->footer_logo) {
                Storage::disk('public')->delete($settings->footer_logo);
            }
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(400, 120);
            $result = $uploadService->uploadImage($request->file('footer_logo'), 'settings');
            $data['footer_logo'] = $result['path'];
        }

        // Handle Homepage Video upload
        // Handle Homepage Video upload
        if ($request->hasFile('anasayfa_video')) {
            try {
                if ($settings->anasayfa_video) {
                    Storage::disk('public')->delete($settings->anasayfa_video);
                }
                // Store directly without image processing service since it's a video
                $path = $request->file('anasayfa_video')->store('videos', 'public');
                $data['anasayfa_video'] = $path;
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['anasayfa_video' => 'Video yüklenirken hata oluştu: ' . $e->getMessage()]);
            }
        }
        
        $settings->fill($data);
        $settings->save();

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
