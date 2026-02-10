<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('id', 'desc')->paginate(20);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'icerik' => 'required',
            'resim' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['resim']);
        $data['sef'] = Str::slug($request->baslik);
        $data['tarih'] = now();
        $data['durum'] = $request->has('durum') ? 1 : 0;
        $data['anasayfa'] = $request->has('anasayfa') ? 1 : 0;
        $data['okunma'] = 0;

        if ($request->hasFile('resim')) {
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(1200, 800);
            $result = $uploadService->uploadImage($request->file('resim'), 'blog');
            $data['resim'] = $result['filename'];
        }

        Blog::create($data);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Yazı başarıyla eklendi.');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        
        $request->validate([
            'baslik' => 'required|string|max:255',
            'icerik' => 'required',
            'resim' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['resim']);
        $data['sef'] = Str::slug($request->baslik);
        $data['durum'] = $request->has('durum') ? 1 : 0;
        $data['anasayfa'] = $request->has('anasayfa') ? 1 : 0;

        if ($request->hasFile('resim')) {
            if ($blog->resim) {
                app(FileUploadService::class)->deleteImage('blog/' . $blog->resim);
            }
            
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(1200, 800);
            $result = $uploadService->uploadImage($request->file('resim'), 'blog');
            $data['resim'] = $result['filename'];
        }

        $blog->update($data);

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Yazı başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        
        if ($blog->resim) {
            app(FileUploadService::class)->deleteImage('blog/' . $blog->resim);
        }
        
        $blog->delete();

        return redirect()
            ->route('admin.blog.index')
            ->with('success', 'Yazı silindi.');
    }
}
