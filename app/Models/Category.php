<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =[
        'name'
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at',
        'pivot'
    ];


    public function product(){
        return $this->hasMany(Product::class);
    }
}
