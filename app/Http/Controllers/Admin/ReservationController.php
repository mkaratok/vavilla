<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\Reservation;
use App\Models\Villa;
use App\Models\AdditionalService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with('villa')->orderBy('id', 'desc');
        
        // Filter by status
        if ($request->has('status')) {
            $statusMap = [
                'pending' => Reservation::STATUS_PENDING,
                'confirmed' => Reservation::STATUS_CONFIRMED,
                'completed' => Reservation::STATUS_COMPLETED,
                'cancelled' => Reservation::STATUS_CANCELLED,
            ];
            
            if (isset($statusMap[$request->status])) {
                $query->where('durum', $statusMap[$request->status]);
            }
        }
        
        // Exclude external reservations
        $query->where('harici', 0);
        
        $reservations = $query->paginate(20);
        
        return view('admin.reservations.index', compact('reservations'));
    }

    public function external()
    {
        $reservations = Reservation::with('villa')
            ->where('harici', 1)
            ->orderBy('id', 'desc')
            ->paginate(20);
            
        return view('admin.reservations.external', compact('reservations'));
    }

    public function create(Request $request)
    {
        $villas = Villa::active()->orderBy('baslik')->get();
        $paymentMethods = PaymentMethod::all();
        $services = AdditionalService::all();
        
        $selectedVilla = null;
        if ($request->has('villa_id')) {
            $selectedVilla = Villa::find($request->villa_id);
        }
        
        return view('admin.reservations.create', compact('villas', 'paymentMethods', 'services', 'selectedVilla'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'adsoyad' => 'required|string|max:255',
            'telefon' => 'required|string|max:255',
            'gelis_tarihi' => 'required|date',
            'cikis_tarihi' => 'required|date|after:gelis_tarihi',
            'toplam_tutar' => 'required|numeric|min:0',
        ]);

        $overlapping = Reservation::where('villa_id', $request->villa_id)
            ->whereIn('durum', [
                Reservation::STATUS_PENDING,
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_COMPLETED,
            ])
            ->where(function ($query) use ($request) {
                $query->whereBetween('gelis_tarihi', [$request->gelis_tarihi, $request->cikis_tarihi])
                    ->orWhereBetween('cikis_tarihi', [$request->gelis_tarihi, $request->cikis_tarihi])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('gelis_tarihi', '<=', $request->gelis_tarihi)
                          ->where('cikis_tarihi', '>=', $request->cikis_tarihi);
                    });
            })
            ->exists();

        if ($overlapping) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['gelis_tarihi' => 'Bu villada seçilen tarihler arasında başka bir rezervasyon bulunmaktadır.']);
        }

        $data = $request->all();
        $data['rezervasyon_tarihi'] = now();
        $data['ip'] = $request->ip();
        $data['harici'] = $request->has('harici') ? 1 : 0;
        
        // Handle additional services as JSON
        if ($request->has('ek_hizmetler')) {
            $data['ek_hizmetler'] = $request->ek_hizmetler;
        }

        $reservation = Reservation::create($data);

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Rezervasyon başarıyla eklendi.');
    }

    public function edit(Reservation $reservation)
    {
        $villas = Villa::active()->orderBy('baslik')->get();
        $paymentMethods = PaymentMethod::all();
        $services = AdditionalService::all();
        
        return view('admin.reservations.edit', compact('reservation', 'villas', 'paymentMethods', 'services'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'villa_id' => 'required|exists:villas,id',
            'adsoyad' => 'required|string|max:255',
            'telefon' => 'required|string|max:255',
            'gelis_tarihi' => 'required|date',
            'cikis_tarihi' => 'required|date|after:gelis_tarihi',
            'toplam_tutar' => 'required|numeric|min:0',
        ]);

        // Only check for overlapping if villa or dates have changed
        $villaChanged = $request->villa_id != $reservation->villa_id;
        $datesChanged = $request->gelis_tarihi != $reservation->gelis_tarihi->format('Y-m-d') 
                     || $request->cikis_tarihi != $reservation->cikis_tarihi->format('Y-m-d');

        if ($villaChanged || $datesChanged) {
            $overlapping = Reservation::where('villa_id', $request->villa_id)
                ->where('id', '!=', $reservation->id)
                ->whereIn('durum', [
                    Reservation::STATUS_PENDING,
                    Reservation::STATUS_CONFIRMED,
                    Reservation::STATUS_COMPLETED,
                ])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('gelis_tarihi', [$request->gelis_tarihi, $request->cikis_tarihi])
                        ->orWhereBetween('cikis_tarihi', [$request->gelis_tarihi, $request->cikis_tarihi])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('gelis_tarihi', '<=', $request->gelis_tarihi)
                              ->where('cikis_tarihi', '>=', $request->cikis_tarihi);
                        });
                })
                ->exists();

            if ($overlapping) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['gelis_tarihi' => 'Bu villada seçilen tarihler arasında başka bir rezervasyon bulunmaktadır.']);
            }
        }

        $data = $request->all();
        
        // Handle additional services as JSON
        if ($request->has('ek_hizmetler')) {
            $data['ek_hizmetler'] = $request->ek_hizmetler;
        }

        $reservation->update($data);

        return redirect()
            ->route('admin.reservations.edit', $reservation)
            ->with('success', 'Rezervasyon başarıyla güncellendi.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Rezervasyon başarıyla silindi.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'durum' => 'required|in:0,1,2,3',
        ]);

        $oldStatus = $reservation->durum;
        $newStatus = (int) $request->durum;
        
        $reservation->update(['durum' => $newStatus]);

        // Send confirmation notification if status changed to confirmed
        if ($oldStatus !== Reservation::STATUS_CONFIRMED && $newStatus === Reservation::STATUS_CONFIRMED) {
            app(NotificationService::class)->notifyReservationConfirmed($reservation);
        }
        
        // Send cancellation notification if cancelled
        if ($newStatus === Reservation::STATUS_CANCELLED) {
            app(NotificationService::class)->notifyReservationCancelled($reservation);
        }

        return response()->json(['success' => true]);
    }

    public function calendar(Request $request)
    {
        $selectedVilla = null;
        $showAll = true;

        $monthParam = $request->get('month');
        try {
            $currentMonth = $monthParam
                ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
                : now()->startOfMonth();
        } catch (\Throwable $e) {
            $currentMonth = now()->startOfMonth();
        }

        $monthStart = $currentMonth->copy()->startOfMonth();
        $monthEnd = $currentMonth->copy()->endOfMonth();
        $daysInMonth = $currentMonth->daysInMonth;

        $activeVillaIds = Villa::active()->pluck('id');
        $villas = Villa::active()->orderBy('baslik')->get();

        $reservations = Reservation::with('villa')
            ->whereIn('villa_id', $activeVillaIds)
            ->whereIn('durum', [
                Reservation::STATUS_PENDING,
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_COMPLETED,
            ])
            ->whereDate('gelis_tarihi', '<=', $monthEnd->toDateString())
            ->whereDate('cikis_tarihi', '>=', $monthStart->toDateString())
            ->orderBy('gelis_tarihi')
            ->get();

        $reservationsByVilla = $reservations->groupBy('villa_id');
        $calendarRows = [];

        foreach ($villas as $villa) {
            $villaReservations = ($reservationsByVilla[$villa->id] ?? collect())
                ->sortBy(function ($r) {
                    return $r->gelis_tarihi;
                })
                ->values();

            $events = [];

            foreach ($villaReservations as $reservation) {
                $displayStart = $reservation->gelis_tarihi->copy();
                $displayEnd = $reservation->cikis_tarihi->copy();

                if ($displayStart->lt($monthStart)) {
                    $displayStart = $monthStart->copy();
                }
                if ($displayEnd->gt($monthEnd)) {
                    $displayEnd = $monthEnd->copy();
                }

                $startDay = (int) $displayStart->day;
                $endDay = (int) $displayEnd->day;

                $events[] = [
                    'id' => $reservation->id,
                    'title' => $reservation->adsoyad,
                    'url' => route('admin.reservations.edit', $reservation),
                    'status' => $reservation->durum,
                    'start_day' => $startDay,
                    'end_day' => $endDay,
                ];
            }

            $calendarRows[] = [
                'villa' => $villa,
                'events' => $events,
            ];
        }

        return view('admin.reservations.calendar', compact(
            'villas',
            'selectedVilla',
            'showAll',
            'currentMonth',
            'monthStart',
            'monthEnd',
            'daysInMonth',
            'calendarRows'
        ));
    }

    // AJAX: Calculate price for reservation
    public function calculatePrice(Request $request)
    {
        $villa = Villa::find($request->villa_id);
        
        if (!$villa) {
            return response()->json(['error' => 'Villa bulunamadı'], 404);
        }
        
        $startDate = Carbon::parse($request->gelis_tarihi);
        $endDate = Carbon::parse($request->cikis_tarihi);
        $totalPrice = 0;
        
        // Calculate price for each night
        for ($date = $startDate->copy(); $date->lt($endDate); $date->addDay()) {
            $totalPrice += $villa->getPriceForDate($date->toDateString());
        }
        
        return response()->json([
            'total' => $totalPrice,
            'nights' => $startDate->diffInDays($endDate),
            'formatted' => number_format($totalPrice, 0, ',', '.') . ' ₺',
        ]);
    }

    // AJAX: Get occupied dates for villa
    public function getOccupiedDates(Request $request)
    {
        $villa = Villa::find($request->villa_id);
        
        if (!$villa) {
            return response()->json(['error' => 'Villa bulunamadı'], 404);
        }
        
        $query = $villa->reservations()
            ->whereIn('durum', [
                Reservation::STATUS_PENDING,
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_COMPLETED,
            ]);
        
        // Exclude current reservation if editing
        if ($request->has('exclude_reservation_id')) {
            $query->where('id', '!=', $request->exclude_reservation_id);
        }
        
        $reservations = $query->get();
        
        $occupiedDates = [];
        $entryDates = [];
        $exitDates = [];
        
        foreach ($reservations as $reservation) {
            $entryDates[] = $reservation->gelis_tarihi->format('Y-n-j');
            $exitDates[] = $reservation->cikis_tarihi->format('Y-n-j');
            
            $current = $reservation->gelis_tarihi->copy();
            while ($current->lte($reservation->cikis_tarihi)) {
                $occupiedDates[] = $current->format('Y-n-j');
                $current->addDay();
            }
        }
        
        return response()->json([
            'occupied' => array_unique($occupiedDates),
            'entries' => $entryDates,
            'exits' => $exitDates,
        ]);
    }
}
