<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::withCount('villas')->orderBy('baslik')->paginate(20);
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['sef'] = Str::slug($request->baslik);

        Region::create($data);

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Bölge başarıyla eklendi.');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['sef'] = Str::slug($request->baslik);

        $region->update($data);

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Bölge başarıyla güncellendi.');
    }

    public function destroy(Region $region)
    {
        if ($region->villas()->count() > 0) {
            return redirect()
                ->route('admin.regions.index')
                ->with('error', 'Bu bölgeye ait villalar bulunmaktadır. Önce villaları silin veya başka bölgeye taşıyın.');
        }

        $region->delete();

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Bölge başarıyla silindi.');
    }
}
