<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes,HasUlids;

    protected $fillable=[
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'is_active',
        'category_id'
    ];

    public function uniqueIds()
    {
        return [
            'ulid',
        ];
    }

    public function productImage(){
        return $this->hasMany(ProductImage::class);
    }

    public function category(){
        return $this->belongsToMany(Category::class);
    }

}
