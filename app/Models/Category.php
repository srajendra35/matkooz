<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'image',
    ];

    public function subCtegories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }
    // public function childCategory()
    // {
    //     return $this->hasMany(Childcategory::class, 'childcategory_id');
    // }
}
