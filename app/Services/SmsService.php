<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $username;
    protected $password;
    protected $sender;
    protected $enabled = false;

    public function __construct()
    {
        $settings = Setting::instance();
        
        $this->username = $settings->ileti_merkezi_username ?? '';
        $this->password = $settings->ileti_merkezi_password ?? '';
        $this->sender = $settings->ileti_merkezi_sender ?? '';
        
        $this->enabled = !empty($this->username) && !empty($this->password);
    }

    /**
     * Send SMS via İleti Merkezi API
     */
    public function send(string $phone, string $message): bool
    {
        if (!$this->enabled) {
            Log::info('SMS Service disabled, skipping message to: ' . $phone);
            return false;
        }

        // Format phone number
        $phone = $this->formatPhone($phone);
        
        try {
            // İleti Merkezi API endpoint
            $response = Http::asForm()->post('https://api.iletimerkezi.com/v1/send-sms', [
                'username' => $this->username,
                'password' => $this->password,
                'sender' => $this->sender,
                'recipients' => $phone,
                'text' => $message,
            ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully to: ' . $phone);
                return true;
            }

            Log::error('SMS send failed', [
                'phone' => $phone,
                'response' => $response->body(),
            ]);
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('SMS send exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Send reservation confirmation SMS to customer
     */
    public function sendReservationConfirmation($reservation): bool
    {
        $villa = $reservation->villa;
        $settings = Setting::instance();
        
        $message = sprintf(
            "%s - Rezervasyonunuz onaylandi. Villa: %s, Giris: %s, Cikis: %s. Bilgi: %s",
            $settings->siteadi ?? 'Villa Kiralama',
            $villa->baslik,
            $reservation->gelis_tarihi->format('d.m.Y'),
            $reservation->cikis_tarihi->format('d.m.Y'),
            $settings->telefon1 ?? ''
        );
        
        return $this->send($reservation->telefon, $message);
    }

    /**
     * Send new reservation alert SMS to admin
     */
    public function sendNewReservationAlert($reservation): bool
    {
        $settings = Setting::instance();
        $adminPhone = $settings->telefon1 ?? '';
        
        if (empty($adminPhone)) {
            return false;
        }
        
        $message = sprintf(
            "Yeni rezervasyon talebi! #%d - %s - %s (%s - %s) - Tutar: %s TL",
            $reservation->id,
            $reservation->villa->baslik,
            $reservation->adsoyad,
            $reservation->gelis_tarihi->format('d.m.Y'),
            $reservation->cikis_tarihi->format('d.m.Y'),
            number_format($reservation->toplam_tutar, 0, ',', '.')
        );
        
        return $this->send($adminPhone, $message);
    }

    /**
     * Format phone number (Turkish format)
     */
    protected function formatPhone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present
        if (strlen($phone) == 10 && str_starts_with($phone, '5')) {
            $phone = '90' . $phone;
        } elseif (strlen($phone) == 11 && str_starts_with($phone, '05')) {
            $phone = '9' . $phone;
        }
        
        return $phone;
    }

    /**
     * Check if SMS service is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
