<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function index()
    {
        $items = Item::query()->orderBy('name')->get();

        return view('reports.index', [
            'items' => $items,
            'totalItems' => $items->count(),
            'totalStock' => $items->sum('quantity'),
            'lowStockItems' => $items->filter->is_low_stock,
            'movements' => StockMovement::with('item')->latest()->limit(25)->get(),
        ]);
    }

    public function exportCsv(): Response
    {
        $rows = Item::query()->orderBy('name')->get();
        $csv = "SKU,Nama,Jumlah,Satuan,Stok Minimum,Lokasi,Status\n";

        foreach ($rows as $item) {
            $csv .= sprintf(
                "%s,%s,%d,%s,%d,%s,%s\n",
                $this->csv($item->sku),
                $this->csv($item->name),
                $item->quantity,
                $this->csv($item->unit),
                $item->minimum_stock,
                $this->csv($item->location ?? '-'),
                $item->is_low_stock ? 'Menipis' : 'Aman'
            );
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-inventory.csv"',
        ]);
    }

    private function csv(string $value): string
    {
        return '"' . str_replace('"', '""', $value) . '"';
    }
}
