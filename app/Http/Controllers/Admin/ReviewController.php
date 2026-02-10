<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerReview;
use App\Models\Villa;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = CustomerReview::with('villa')->orderBy('id', 'desc')->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $villas = Villa::orderBy('baslik')->get();
        return view('admin.reviews.create', compact('villas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'adsoyad' => 'required|string|max:255',
            'villa_id' => 'required|exists:villas,id',
            'yorum' => 'required|string',
            'temizlik' => 'required|integer|min:1|max:10',
            'ucret' => 'required|integer|min:1|max:10',
            'ulasim' => 'required|integer|min:1|max:10',
            'servis' => 'required|integer|min:1|max:10',
        ]);

        $data = $request->all();
        $data['tarih'] = now();
        $data['durum'] = 1; // Auto-approve if added by admin
        $data['ip'] = $request->ip();

        CustomerReview::create($data);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Yorum başarıyla eklendi.');
    }

    public function edit($id)
    {
        $review = CustomerReview::findOrFail($id);
        $villas = Villa::orderBy('baslik')->get();
        return view('admin.reviews.edit', compact('review', 'villas'));
    }

    public function update(Request $request, $id)
    {
        $review = CustomerReview::findOrFail($id);
        
        $request->validate([
            'adsoyad' => 'required|string|max:255',
            'villa_id' => 'required|exists:villas,id',
            'yorum' => 'required|string',
            'temizlik' => 'required|integer|min:1|max:10',
            'ucret' => 'required|integer|min:1|max:10',
            'ulasim' => 'required|integer|min:1|max:10',
            'servis' => 'required|integer|min:1|max:10',
        ]);

        $data = $request->all();
        $data['durum'] = $request->has('durum') ? 1 : 0;

        $review->update($data);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Yorum başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        CustomerReview::findOrFail($id)->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Yorum silindi.');
    }
}
