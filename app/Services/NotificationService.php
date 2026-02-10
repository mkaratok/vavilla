<?php

namespace App\Services;

use App\Mail\ReservationConfirmed;
use App\Mail\ReservationCreated;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    protected SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle new reservation notifications
     */
    public function notifyNewReservation(Reservation $reservation): void
    {
        $settings = Setting::instance();
        
        // Send email notification to admin
        try {
            if ($settings->iletisim_email) {
                Mail::to($settings->iletisim_email)
                    ->send(new ReservationCreated($reservation));
                    
                Log::info('Admin email notification sent for reservation #' . $reservation->id);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send admin email notification', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
        
        // Send SMS notification to admin
        try {
            $this->smsService->sendNewReservationAlert($reservation);
        } catch (\Exception $e) {
            Log::error('Failed to send admin SMS notification', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle reservation confirmation notifications
     */
    public function notifyReservationConfirmed(Reservation $reservation): void
    {
        // Send email to customer
        try {
            if ($reservation->email) {
                Mail::to($reservation->email)
                    ->send(new ReservationConfirmed($reservation));
                    
                Log::info('Customer email confirmation sent for reservation #' . $reservation->id);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send customer email confirmation', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
        
        // Send SMS to customer
        try {
            $this->smsService->sendReservationConfirmation($reservation);
        } catch (\Exception $e) {
            Log::error('Failed to send customer SMS confirmation', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle reservation cancellation notifications
     */
    public function notifyReservationCancelled(Reservation $reservation): void
    {
        $settings = Setting::instance();
        
        // Send cancellation SMS to customer
        if ($reservation->telefon && $this->smsService->isEnabled()) {
            $message = sprintf(
                "%s - Rezervasyonunuz iptal edilmistir. Rez No: #%d. Bilgi: %s",
                $settings->siteadi ?? 'Villa Kiralama',
                $reservation->id,
                $settings->telefon1 ?? ''
            );
            
            $this->smsService->send($reservation->telefon, $message);
        }
    }
}
