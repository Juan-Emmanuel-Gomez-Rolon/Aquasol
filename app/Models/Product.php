<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'barcode',
        'price',
        'stock',
        'image',
        'last_sale',
        'last_entry'
    ];

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot(['quantity', 'price']);
    }

    public function entries()
    {
        return $this->belongsToMany(Entry::class)->withPivot('quantity');
    }

    public function lastSales()
    {
        return $this->sales()->latest()->limit(15);
    }
}
