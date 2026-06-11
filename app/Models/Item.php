<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'quantity',
        'unit',
        'minimum_stock',
        'location',
        'notes',
        'photo_url',
        'photo_public_id',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }

    public function getPhotoThumbnailUrlAttribute(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        return str_replace('/upload/', '/upload/c_fill,w_96,h_96,q_auto,f_auto/', $this->photo_url);
    }
}
