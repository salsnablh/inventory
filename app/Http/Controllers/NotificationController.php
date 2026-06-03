<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\NotificationMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index', [
            'messages' => NotificationMessage::latest()->limit(25)->get(),
            'lowStockItems' => Item::query()
                ->whereColumn('quantity', '<=', 'minimum_stock')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'channel' => ['required', Rule::in(['email', 'whatsapp', 'internal'])],
            'recipient' => ['required', 'string', 'max:150'],
            'subject' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:1000'],
        ]);

        NotificationMessage::create($data + ['status' => 'draft']);

        return back()->with('status', 'Draft notifikasi dibuat.');
    }

    public function markSent(NotificationMessage $message)
    {
        $message->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('status', 'Notifikasi ditandai terkirim.');
    }
}
