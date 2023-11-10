<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,SoftDeletes;

    protected $hidden=[
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'name'
    ];

    public function user(){
        return $this->hasMany(User::class);
    }
}
