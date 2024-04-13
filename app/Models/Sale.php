<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'user_id',
        'total',
        'client_name',
        'client_email',
        'client_phone',
        'client_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'price']);
    }
}
