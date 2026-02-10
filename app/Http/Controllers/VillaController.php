<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Setting;
use App\Models\Villa;
use App\Models\VillaType;
use App\Models\CustomerReview;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::instance();
        
        $query = Villa::active()->with('region', 'images');
        
        // Filter by region
        if ($request->filled('bolge')) {
            $query->where('region_id', $request->bolge);
        }
        
        // Filter by villa type
        if ($request->filled('tip')) {
            $query->whereJsonContains('villa_tipi', $request->tip);
        }
        
        // Filter by capacity (Input: kisi_sayisi or kapasite)
        $capacity = $request->input('kisi_sayisi', $request->input('kapasite'));
        if ($capacity) {
            $query->where('yetiskin', '>=', $capacity);
        }
        
        // Filter by availability (Dates)
        if ($request->filled('giris_tarihi') && $request->filled('cikis_tarihi')) {
            try {
                $startDate = \Carbon\Carbon::createFromFormat('d.m.Y', $request->giris_tarihi)->format('Y-m-d');
                $endDate = \Carbon\Carbon::createFromFormat('d.m.Y', $request->cikis_tarihi)->format('Y-m-d');

                $query->whereDoesntHave('reservations', function ($q) use ($startDate, $endDate) {
                    $q->whereIn('durum', [1, 2]) // Confirmed or Completed
                      ->where(function ($subQ) use ($startDate, $endDate) {
                          $subQ->whereBetween('gelis_tarihi', [$startDate, $endDate])
                               ->orWhereBetween('cikis_tarihi', [$startDate, $endDate])
                               ->orWhere(function ($overlapQ) use ($startDate, $endDate) {
                                   $overlapQ->where('gelis_tarihi', '<=', $startDate)
                                            ->where('cikis_tarihi', '>=', $endDate);
                               });
                      });
                });
            } catch (\Exception $e) {
                // Invalid date format, ignore filter
            }
        }
        
        // Filter by price range
        if ($request->filled('min_fiyat')) {
            $query->where('fiyat', '>=', $request->min_fiyat);
        }
        if ($request->filled('max_fiyat')) {
            $query->where('fiyat', '<=', $request->max_fiyat);
        }
        
        // Search by name
        if ($request->filled('ara')) {
            $query->where('baslik', 'like', '%' . $request->ara . '%');
        }
        
        // Sort
        $sort = $request->get('sirala', 'anasayfa_sira');
        switch ($sort) {
            case 'fiyat_artan':
                $query->orderBy('fiyat', 'asc');
                break;
            case 'fiyat_azalan':
                $query->orderBy('fiyat', 'desc');
                break;
            case 'yeni':
                $query->orderBy('id', 'desc');
                break;
            default:
                $query->orderBy('anasayfa_sira', 'asc');
        }
        
        $villas = $query->paginate(12)->withQueryString();
        
        return view('public.villas.index', [
            'settings' => $settings,
            'villas' => $villas,
            'regions' => Region::withCount('villas')->get(),
            'villaTypes' => VillaType::all(),
            'filters' => $request->all(), // Pass all inputs back to view
        ]);
    }

    public function show($slug)
    {
        $settings = Setting::instance();
        
        $villa = Villa::where('sef', $slug)
            ->where('durum', 1)
            ->with(['region', 'images', 'seasonalPrices', 'reviews' => function($q) {
                $q->approved()->orderBy('id', 'desc');
            }])
            ->firstOrFail();
        
        // Similar villas
        $similarVillas = Villa::active()
            ->where('id', '!=', $villa->id)
            ->where('region_id', $villa->region_id)
            ->with('images')
            ->limit(4)
            ->get();
        
        // Calculate average rating
        $avgRating = $villa->reviews->avg('ortalama') ?? 0;
        
        return view('public.villas.show', [
            'settings' => $settings,
            'villa' => $villa,
            'similarVillas' => $similarVillas,
            'avgRating' => round($avgRating, 1),
        ]);
    }

    public function byRegion($slug)
    {
        $settings = Setting::instance();
        $region = Region::where('sef', $slug)->firstOrFail();
        
        $villas = Villa::active()
            ->where('region_id', $region->id)
            ->with('images')
            ->paginate(12);
        
        return view('public.villas.region', [
            'settings' => $settings,
            'region' => $region,
            'villas' => $villas,
        ]);
    }

    public function byType($slug)
    {
        $settings = Setting::instance();
        $type = VillaType::where('sef', $slug)->firstOrFail();
        
        $villas = Villa::active()
            ->whereJsonContains('villa_tipi', (string) $type->id)
            ->with('images')
            ->paginate(12);
        
        return view('public.villas.type', [
            'settings' => $settings,
            'type' => $type,
            'villas' => $villas,
        ]);
    }
}
