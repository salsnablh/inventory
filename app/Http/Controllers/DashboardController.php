<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\NotificationMessage;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $items = Item::query()->orderBy('name')->get();

        return view('dashboard.index', [
            'totalItems' => $items->count(),
            'totalStock' => $items->sum('quantity'),
            'lowStockItems' => $items->filter->is_low_stock->values(),
            'recentMovements' => StockMovement::with('item')->latest()->limit(6)->get(),
            'draftMessages' => NotificationMessage::query()->where('status', 'draft')->count(),
        ]);
    }
}
