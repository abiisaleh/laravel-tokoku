<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'items' => 'array'
    ];

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->subtotal + $this->ongkir,
        );
    }

    public function user(): 
    {
         return $this->belongsTo(User::class);
    }
}
