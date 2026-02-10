<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillaType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VillaTypeController extends Controller
{
    public function index()
    {
        $types = VillaType::orderBy('baslik')->paginate(20);
        return view('admin.villa-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.villa-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['sef'] = Str::slug($request->baslik);

        VillaType::create($data);

        return redirect()
            ->route('admin.villa-types.index')
            ->with('success', 'Villa tipi başarıyla eklendi.');
    }

    public function edit(VillaType $villaType)
    {
        return view('admin.villa-types.edit', ['type' => $villaType]);
    }

    public function update(Request $request, VillaType $villaType)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['sef'] = Str::slug($request->baslik);

        $villaType->update($data);

        return redirect()
            ->route('admin.villa-types.index')
            ->with('success', 'Villa tipi başarıyla güncellendi.');
    }

    public function destroy(VillaType $villaType)
    {
        $villaType->delete();

        return redirect()
            ->route('admin.villa-types.index')
            ->with('success', 'Villa tipi başarıyla silindi.');
    }
}
