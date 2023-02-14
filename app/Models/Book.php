<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'authors' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function (Book $model) {
            if ($model->thumbnail != $model->getOriginal('thumbnail')) {
                Storage::delete('public/'.$model->getOriginal('thumbnail'));
            }
        });

        static::forceDeleted(fn (Book $model) => Storage::delete('public/'.$model->thumbnail));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
