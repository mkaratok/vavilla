<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Villa;
use App\Models\VillaType;
use App\Models\Faq;
use App\Models\Video;
use App\Models\CustomerReview;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::instance();
        
        return view('public.home', [
            'settings' => $settings,
            'villas' => ($villas = Villa::homepage()->with('region', 'images')->limit(6)->get())->isEmpty() 
                ? Villa::active()->with('region', 'images')->latest()->limit(6)->get() 
                : $villas,
            'allVillas' => Villa::active()->get(),
            'regions' => Region::withCount('villas')->get(),
            'villaTypes' => VillaType::all(),
            'reviews' => CustomerReview::where('durum', 1)->latest()->take(5)->get(),
            'blogs' => Blog::where('durum', 1)->latest()->take(3)->get(),
        ]);
    }

    public function about()
    {
        $settings = Setting::instance();
        return view('public.about', compact('settings'));
    }

    public function contact()
    {
        $settings = Setting::instance();
        return view('public.contact', compact('settings'));
    }

    public function faq()
    {
        $settings = Setting::instance();
        $faqs = Faq::all();
        return view('public.faq', compact('settings', 'faqs'));
    }

    public function gallery()
    {
        $settings = Setting::instance();
        $galleries = \App\Models\PhotoGallery::with('images')->orderBy('id', 'desc')->get();
        return view('public.gallery', compact('settings', 'galleries'));
    }

    public function galleryShow($slug)
    {
        $settings = Setting::instance();
        $gallery = \App\Models\PhotoGallery::where('sef', $slug)->with('images')->firstOrFail();
        return view('public.gallery-show', compact('settings', 'gallery'));
    }
}
