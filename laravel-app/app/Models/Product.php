<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'price', 'stock_on_hand',
        'reorder_threshold', 'status', 'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
