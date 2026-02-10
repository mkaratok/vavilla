<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TechnicalFeature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        $features = TechnicalFeature::orderBy('baslik')->paginate(20);
        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        TechnicalFeature::create($request->all());

        return redirect()
            ->route('admin.features.index')
            ->with('success', 'Özellik başarıyla eklendi.');
    }

    public function edit(TechnicalFeature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, TechnicalFeature $feature)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $feature->update($request->all());

        return redirect()
            ->route('admin.features.index')
            ->with('success', 'Özellik başarıyla güncellendi.');
    }

    public function destroy(TechnicalFeature $feature)
    {
        $feature->delete();

        return redirect()
            ->route('admin.features.index')
            ->with('success', 'Özellik başarıyla silindi.');
    }
}
