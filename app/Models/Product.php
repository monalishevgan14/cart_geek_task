<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_price',
        'product_description',
        'user_id'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    private function formatText($value)
    {
        return ucfirst(trim($value));
    }

    // ACCESSOR
    public function getProductNameAttribute($value)
    {
        return $this->formatText($value);
    }

    public function getProductDescriptionAttribute($value)
    {
        return $this->formatText($value);
    }

    // MUTATOR
    public function setProductPriceAttribute($value)
    {
        $this->attributes['product_price'] = number_format($value, 2, '.', '');
    }

}
