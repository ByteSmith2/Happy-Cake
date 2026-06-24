<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'price', 'sale_price',
        'image', 'description', 'stock', 'featured',
        'size_options', 'min_lead_days',
    ];

    protected $casts = [
        'size_options' => 'array',
        'featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function hasSizeOptions(): bool
    {
        return is_array($this->size_options) && count($this->size_options) > 0;
    }
}
