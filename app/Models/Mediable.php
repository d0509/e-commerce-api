<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mediable extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'media_id',
        'mediable_type',
        'mediable_id',
        'tag',
        'order'
    ];

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
