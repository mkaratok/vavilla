<?php

namespace App\Http\Controllers;

use App\Models\AdditionalService;
use App\Models\PaymentMethod;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Villa;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function create(Request $request, $slug)
    {
        $settings = Setting::instance();
        
        $villa = Villa::where('sef', $slug)
            ->where('durum', 1)
            ->with('seasonalPrices')
            ->firstOrFail();
        
        $paymentMethods = PaymentMethod::all();
        $services = AdditionalService::all();
        
        return view('public.reservation.create', [
            'settings' => $settings,
            'villa' => $villa,
            'paymentMethods' => $paymentMethods,
            'services' => $services,
            'gelis' => $request->get('gelis'),
            'cikis' => $request->get('cikis'),
        ]);
    }

    public function store(Request $request, $slug)
    {
        $settings = Setting::instance();
        
        $villa = Villa::where('sef', $slug)
            ->where('durum', 1)
            ->firstOrFail();
        
        $request->validate([
            'adsoyad' => 'required|string|max:255',
            'telefon' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'gelis_tarihi' => 'required|date|after_or_equal:today',
            'cikis_tarihi' => 'required|date|after:gelis_tarihi',
            'yetiskin' => 'required|integer|min:1',
        ]);
        
        // Check minimum rental period
        $gelis = Carbon::parse($request->gelis_tarihi);
        $cikis = Carbon::parse($request->cikis_tarihi);
        $nights = $gelis->diffInDays($cikis);
        
        if ($nights < $villa->minimum_kiralama_suresi) {
            return back()->with('error', "Bu villa minimum {$villa->minimum_kiralama_suresi} gece kiralanabilir.");
        }
        
        // Müsaitlik Kontrol Et
        if (!$villa->isAvailable($request->gelis_tarihi, $request->cikis_tarihi)) {
            return back()->with('error', 'Seçilen tarihler için villa müsait değil.');
        }
        
        // Calculate total price
        $totalPrice = 0;
        for ($date = $gelis->copy(); $date->lt($cikis); $date->addDay()) {
            $totalPrice += $villa->getPriceForDate($date->toDateString());
        }
        
        // Create reservation
        $reservation = Reservation::create([
            'villa_id' => $villa->id,
            'adsoyad' => $request->adsoyad,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'yetiskin' => $request->yetiskin,
            'cocuk' => $request->cocuk ?? 0,
            'adres' => $request->adres,
            'notu' => $request->notu,
            'tc' => $request->tc,
            'toplam_tutar' => $totalPrice,
            'gelis_tarihi' => $request->gelis_tarihi,
            'cikis_tarihi' => $request->cikis_tarihi,
            'rezervasyon_tarihi' => now(),
            'ip' => $request->ip(),
            'durum' => Reservation::STATUS_PENDING,
            'odeme_tipi' => $request->odeme_tipi,
            'ek_hizmetler' => $request->ek_hizmetler ?? [],
        ]);
        
        // Send notifications
        app(NotificationService::class)->notifyNewReservation($reservation);
        
        return redirect()
            ->route('reservation.success', ['id' => $reservation->id])
            ->with('success', 'Rezervasyon talebiniz alındı!');
    }

    public function success(Request $request)
    {
        $settings = Setting::instance();
        $reservation = Reservation::with('villa')->findOrFail($request->id);
        
        return view('public.reservation.success', [
            'settings' => $settings,
            'reservation' => $reservation,
        ]);
    }

    // AJAX: Müsaitlik Kontrol Et
    public function checkAvailability(Request $request)
    {
        $villa = Villa::find($request->villa_id);
        
        if (!$villa) {
            return response()->json(['error' => 'Villa bulunamadı'], 404);
        }
        
        $available = $villa->isAvailable($request->gelis, $request->cikis);
        
        return response()->json([
            'available' => $available,
            'message' => $available ? 'Villa müsait' : 'Villa dolu',
        ]);
    }

    // AJAX: Calculate price
    public function calculatePrice(Request $request)
    {
        $villa = Villa::find($request->villa_id);
        
        if (!$villa) {
            return response()->json(['error' => 'Villa bulunamadı'], 404);
        }
        
        $gelis = Carbon::parse($request->gelis);
        $cikis = Carbon::parse($request->cikis);
        $totalPrice = 0;
        
        for ($date = $gelis->copy(); $date->lt($cikis); $date->addDay()) {
            $totalPrice += $villa->getPriceForDate($date->toDateString());
        }
        
        $nights = $gelis->diffInDays($cikis);
        $prepayment = ($totalPrice * Setting::instance()->on_kiralama_bedeli) / 100;
        
        return response()->json([
            'total' => $totalPrice,
            'nights' => $nights,
            'prepayment' => $prepayment,
            'formatted' => number_format($totalPrice, 0, ',', '.') . ' ₺',
            'prepayment_formatted' => number_format($prepayment, 0, ',', '.') . ' ₺',
        ]);
    }

    // AJAX: Get occupied dates for villa calendar
    public function getOccupiedDates(Request $request)
    {
        $villa = Villa::find($request->villa_id);
        
        if (!$villa) {
            return response()->json(['error' => 'Villa bulunamadı'], 404);
        }
        
        // Get pending and confirmed reservations
        $reservations = $villa->reservations()
            ->whereIn('durum', [
                Reservation::STATUS_PENDING,
                Reservation::STATUS_CONFIRMED,
            ])
            ->get();
        
        $occupiedDates = [];
        
        foreach ($reservations as $reservation) {
            $current = Carbon::parse($reservation->gelis_tarihi);
            $endDate = Carbon::parse($reservation->cikis_tarihi);
            
            while ($current->lt($endDate)) {
                $occupiedDates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        }
        
        return response()->json([
            'occupied' => array_unique($occupiedDates),
        ]);
    }
}
