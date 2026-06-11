<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory.index', [
            'items' => Item::query()->orderBy('name')->get(),
            'movements' => StockMovement::with('item')->latest()->limit(10)->get(),
            'cloudinary' => [
                'cloudName' => config('services.cloudinary.cloud_name'),
                'uploadPreset' => config('services.cloudinary.upload_preset'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:items,sku'],
            'name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:25'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
            'photo_public_id' => ['nullable', 'string', 'max:255'],
        ]);

        Item::create($data);

        return back()->with('status', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('items', 'sku')->ignore($item)],
            'name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:25'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'location' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo_url' => ['nullable', 'url', 'max:2048'],
            'photo_public_id' => ['nullable', 'string', 'max:255'],
        ]);

        $item->update($data);

        return back()->with('status', 'Data barang diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return back()->with('status', 'Barang dihapus.');
    }

    public function recordMovement(Request $request, Item $item)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'actor' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($item, $data) {
            $delta = $data['type'] === 'in' ? $data['quantity'] : -$data['quantity'];
            $newQuantity = $item->quantity + $delta;

            if ($newQuantity < 0) {
                abort(422, 'Stok tidak cukup untuk transaksi keluar.');
            }

            $item->update(['quantity' => $newQuantity]);
            $item->movements()->create($data);
        });

        return back()->with('status', 'Pergerakan stok dicatat.');
    }
}
