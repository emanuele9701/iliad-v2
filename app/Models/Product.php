<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_value' => 'decimal:2',
    ];


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'products_order')->withPivot('qty');
    }

}

