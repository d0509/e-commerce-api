<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUlids, HasSlug;

    protected $fillable = [
        'name',
        'ulid',
        'slug',
        'description',
        'price',
        'quantity',
        'is_active',
    ];

    public function media()
    {
        return $this->hasManyThrough(Media::class, Mediable::class, 'mediable_id', 'id', 'id', 'media_id')->where('mediables.mediable_type', Product::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name') // The field to generate the slug from
            ->saveSlugsTo('slug');       // The field to store the generated slug
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name', // The field to generate the slug from
                'onUpdate' => true, // Regenerate the slug when the source field is updated
            ],
        ];
    }


    public function uniqueIds()
    {
        return [
            'ulid',
        ];
    }

    public function productImage()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
}
