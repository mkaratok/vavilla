<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = AdditionalService::orderBy('baslik')->paginate(20);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        AdditionalService::create($request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Hizmet başarıyla eklendi.');
    }

    public function edit(AdditionalService $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, AdditionalService $service)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $service->update($request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Hizmet başarıyla güncellendi.');
    }

    public function destroy(AdditionalService $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Hizmet başarıyla silindi.');
    }
}
