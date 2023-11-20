<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory,HasUlids;

    protected $fillable = [
        'disk',
        'directory',
        'filename',
        'extension',
        'mime_type',
        'aggregate_type',
        'size',
    ];


    protected $hidden = [
        'id'
    ];

    public function uniqueIds()
    {
        return [
            'ulid',
        ];
    }

    // public function getUrlAttribute()
    // {
    //     return url(Storage::url("{$this->directory}/{$this->filename}.{$this->extension}"));
    // }

    public function getUrl()
    {
        return asset('storage/' . $this->directory . '/' . $this->filename . '.' . $this->extension);
    }

   

}
