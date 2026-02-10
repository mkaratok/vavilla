<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormReceived;
use App\Models\Contact;
use App\Models\Newsletter;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'adsoyad' => 'required|string|max:255',
            'telefon' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'konu' => 'required|string|max:255',
            'mesaj' => 'required|string',
        ]);

        $contact = Contact::create([
            'adsoyad' => $request->adsoyad,
            'email' => $request->email,
            'telefon' => $request->telefon,
            'konu' => $request->konu,
            'mesaj' => $request->mesaj,
            'tarih' => time(),
            'ip' => $request->ip(),
            'durum' => 0,
        ]);

        // Send email notification
        try {
            $settings = Setting::instance();
            if ($settings->iletisim_email) {
                Mail::to($settings->iletisim_email)->send(new ContactFormReceived($contact));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send contact form email', ['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapılacaktır.');
    }

    public function newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);

        Newsletter::create([
            'email' => $request->email,
            'tarih' => now()->format('d.m.Y'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'E-bülten kaydınız başarıyla oluşturuldu.',
        ]);
    }
}
