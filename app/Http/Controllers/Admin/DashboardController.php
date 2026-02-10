<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CustomerReview;
use App\Models\Newsletter;
use App\Models\Region;
use App\Models\Reservation;
use App\Models\TechnicalFeature;
use App\Models\Villa;
use App\Models\VillaType;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalVillas' => Villa::count(),
            'pendingReservations' => Reservation::pending()->count(),
            'confirmedReservations' => Reservation::confirmed()->count(),
            'unreadMessages' => ContactMessage::unread()->count(),
            'latestReservations' => Reservation::with('villa')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'totalRegions' => Region::count(),
            'totalVillaTypes' => VillaType::count(),
            'totalFeatures' => TechnicalFeature::count(),
            'totalReviews' => CustomerReview::approved()->count(),
            'totalNewsletters' => Newsletter::count(),
        ]);
    }
}
