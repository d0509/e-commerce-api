<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mediable extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'media_id',
        'mediable_type',
        'mediable_id',
        'tag',
    ];

    protected $hidden = [
        'id'
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

}
