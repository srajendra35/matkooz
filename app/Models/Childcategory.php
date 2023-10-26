<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childcategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'subcategory_id'
    ];

    public function subcategories()
    {
        return $this->belongsTo(Subcategory::class);
    }

    // public  function Category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}
