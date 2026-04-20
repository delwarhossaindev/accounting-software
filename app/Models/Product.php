<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'sku', 'name', 'description', 'unit',
        'purchase_price', 'sale_price',
        'current_stock', 'reorder_level',
        'category', 'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) {
            return 'out_of_stock';
        }
        if ($this->reorder_level > 0 && $this->current_stock <= $this->reorder_level) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockValueAttribute(): float
    {
        return $this->current_stock * $this->purchase_price;
    }

    public static function generateSku(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? (int) preg_replace('/\D/', '', $last->sku) + 1 : 1;
        return 'SKU-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
