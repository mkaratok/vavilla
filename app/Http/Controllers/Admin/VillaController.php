<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\Villa;
use App\Models\VillaImage;
use App\Models\VillaSeasonalPrice;
use App\Models\TechnicalFeature;
use App\Models\AdditionalService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::with('region')->orderBy('id', 'desc')->paginate(20);
        return view('admin.villas.index', compact('villas'));
    }

    public function create()
    {
        $regions = Region::all();
        $features = TechnicalFeature::all();
        $services = AdditionalService::all();
        
        return view('admin.villas.create', compact('regions', 'features', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'fiyat' => 'required|numeric|min:0',
            'region_id' => 'nullable|exists:regions,id',
            'gorsel' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('gorsel', 'teknik_ozellikler', 'mesafe_bilgileri', 'genel_bilgiler', 'ek_hizmetler');
        
        // Generate slug
        $data['sef'] = Str::slug($request->baslik) . '-' . time();
        
        // Handle JSON fields
        if ($request->has('teknik_ozellikler')) {
            $data['teknik_ozellikler'] = $request->teknik_ozellikler;
        }
        if ($request->has('mesafe_bilgileri')) {
            $data['mesafe_bilgileri'] = $request->mesafe_bilgileri;
        }
        if ($request->has('genel_bilgiler')) {
            $data['genel_bilgiler'] = $request->genel_bilgiler;
        }
        if ($request->has('ek_hizmetler')) {
            $data['ek_hizmetler'] = $request->ek_hizmetler;
        }

        // Handle image upload
        if ($request->hasFile('gorsel')) {
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(1200, 800);
            $result = $uploadService->uploadImage($request->file('gorsel'), 'villas');
            $data['gorsel'] = $result['filename'];
        }

        $villa = Villa::create($data);

        return redirect()
            ->route('admin.villas.edit', $villa)
            ->with('success', 'Villa başarıyla eklendi.');
    }

    public function edit(Villa $villa)
    {
        $regions = Region::all();
        $features = TechnicalFeature::all();
        $services = AdditionalService::all();
        $images = $villa->images;
        $prices = $villa->seasonalPrices()->orderBy('tarih1')->get();
        
        return view('admin.villas.edit', compact('villa', 'regions', 'features', 'services', 'images', 'prices'));
    }

    public function update(Request $request, Villa $villa)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'fiyat' => 'required|numeric|min:0',
            'region_id' => 'nullable|exists:regions,id',
            'gorsel' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('gorsel', 'teknik_ozellikler', 'mesafe_bilgileri', 'genel_bilgiler', 'ek_hizmetler');
        
        // Handle JSON fields
        $data['teknik_ozellikler'] = $request->teknik_ozellikler ?? [];
        $data['mesafe_bilgileri'] = $request->mesafe_bilgileri ?? [];
        $data['genel_bilgiler'] = $request->genel_bilgiler ?? [];
        $data['ek_hizmetler'] = $request->ek_hizmetler ?? [];

        // Handle image upload
        if ($request->hasFile('gorsel')) {
            // Delete old image
            if ($villa->gorsel) {
                $uploadService = app(FileUploadService::class);
                $uploadService->deleteImage('villas/' . $villa->gorsel);
            }
            
            $uploadService = app(FileUploadService::class);
            $uploadService->setMaxDimensions(1200, 800);
            $result = $uploadService->uploadImage($request->file('gorsel'), 'villas');
            $data['gorsel'] = $result['filename'];
        }

        $villa->update($data);

        return redirect()
            ->route('admin.villas.edit', $villa)
            ->with('success', 'Villa başarıyla güncellendi.');
    }

    public function destroy(Villa $villa)
    {
        // Delete images
        foreach ($villa->images as $image) {
            Storage::delete('public/villas/' . $image->bresim);
            Storage::delete('public/villas/' . $image->kresim);
        }
        
        if ($villa->gorsel) {
            Storage::delete('public/villas/' . $villa->gorsel);
        }
        
        $villa->delete();

        return redirect()
            ->route('admin.villas.index')
            ->with('success', 'Villa başarıyla silindi.');
    }

    public function duplicate(Villa $villa)
    {
        $newVilla = $villa->replicate();
        $newVilla->baslik = $villa->baslik . ' (Kopya)';
        $newVilla->sef = $villa->sef . '-kopya-' . time();
        $newVilla->durum = 0; // Set as inactive by default
        $newVilla->anasayfa = 0;
        $newVilla->save();

        return redirect()
            ->route('admin.villas.edit', $newVilla)
            ->with('success', 'Villa başarıyla kopyalandı. Düzenleyebilirsiniz.');
    }

    public function ordering()
    {
        $villas = Villa::where('anasayfa', 1)
            ->orderBy('anasayfa_sira')
            ->get();
            
        return view('admin.villas.ordering', compact('villas'));
    }

    public function updateOrdering(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Villa::where('id', $id)->update(['anasayfa_sira' => $index]);
        }

        return response()->json(['success' => true]);
    }

    // Gallery image upload
    public function uploadImage(Request $request, Villa $villa)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $uploadService = app(FileUploadService::class);
        $uploadService->setMaxDimensions(1920, 1280);
        $uploadService->setThumbnailDimensions(400, 300);
        
        $result = $uploadService->uploadImage($request->file('image'), 'villas');

        VillaImage::create([
            'villa_id' => $villa->id,
            'bresim' => $result['filename'],
            'kresim' => $result['thumbnail'] ? basename($result['thumbnail']) : $result['filename'],
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Resim yüklendi',
            'image' => $result,
        ]);
    }

    public function deleteImage(VillaImage $image)
    {
        Storage::delete('public/villas/' . $image->bresim);
        Storage::delete('public/villas/' . $image->kresim);
        $image->delete();

        return response()->json(['success' => true]);
    }

    // Seasonal prices
    public function storePrice(Request $request, Villa $villa)
    {
        $request->validate([
            'tarih1' => 'required|date',
            'tarih2' => 'required|date|after_or_equal:tarih1',
            'fiyat' => 'required|numeric|min:0',
        ]);

        $villa->seasonalPrices()->create($request->only('tarih1', 'tarih2', 'fiyat'));

        return redirect()
            ->route('admin.villas.edit', $villa)
            ->with('success', 'Fiyat dönemi eklendi.');
    }

    public function deletePrice(VillaSeasonalPrice $price)
    {
        $villaId = $price->villa_id;
        $price->delete();

        return redirect()
            ->route('admin.villas.edit', $villaId)
            ->with('success', 'Fiyat dönemi silindi.');
    }
}
