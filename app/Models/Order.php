<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'order_date',
        'total_value',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_order')->withPivot('qty','name','price');
    }
}
