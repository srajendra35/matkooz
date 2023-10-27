<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
        'offer',
    ];
    public function product()
    {
        return $this->hasMany(Product::class, 'offer_id');
    }
}
