<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Addon extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'quantity',
        'price_inr',
        'price_usd',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_inr' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function userAddons(): HasMany
    {
        return $this->hasMany(UserAddon::class);
    }

    public function getPrice(string $currency = 'INR'): float
    {
        return (float) ($currency === 'INR' ? $this->price_inr : $this->price_usd);
    }

    /**
     * Get formatted price with currency symbol
     */
    public function getFormattedPrice(string $currency = 'INR'): string
    {
        $price = $this->getPrice($currency);
        $symbol = $currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($price, 0);
    }

    public function getGstAmount(string $currency = 'INR'): float
    {
        $price = $this->getPrice($currency);
        $gstPercent = (float) Setting::getValue('gst_percent', 0);
        return ($price * $gstPercent) / 100;
    }

    public function getTotalPriceWithGst(string $currency = 'INR'): float
    {
        return $this->getPrice($currency) + $this->getGstAmount($currency);
    }

    /**
     * Get per-unit cost
     */
    public function getPerUnitCost(string $currency = 'INR'): string
    {
        $price = $this->getPrice($currency);
        $perUnit = $price / $this->quantity;
        $symbol = $currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($perUnit, 2) . ' per ' . rtrim($this->type, 's');
    }

    public function getIcon(): string
    {
        return $this->type ?? 'package';
    }

    /**
     * Scope: Active add-ons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
