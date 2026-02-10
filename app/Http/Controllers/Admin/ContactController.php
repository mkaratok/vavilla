<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::orderBy('id', 'desc')->paginate(20);
        return view('admin.contact.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        // Mark as read
        if ($message->durum == 0) {
            $message->markAsRead();
        }
        
        return view('admin.contact.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()
            ->route('admin.contact.index')
            ->with('success', 'Mesaj başarıyla silindi.');
    }
}
