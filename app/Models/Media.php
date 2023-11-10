<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getUrl()
    {
        // Replace 'your_base_url' with the actual base URL where your images are stored
        return asset('storage/' . $this->directory . '/' . $this->filename . '.' . $this->extension);
    }
}
