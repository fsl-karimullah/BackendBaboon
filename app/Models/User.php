<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'instance',
        'phone_number',
        'password',
        'subscription_exp_date',
        'avatar',
    ];

    protected $appends = [
        'is_subscribed',
    ];

    public function getIsSubscribedAttribute(): bool
    {
        return $this->subscription_exp_date >= now();
    }

    protected $hidden = [
        'password',
    ];

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public static function boot()
    {
        parent::boot();

        static::updated(function (User $model) {
            if ($model->avatar != $model->getOriginal('avatar')) {
                Storage::delete('public/'.$model->getOriginal('avatar'));
            }
        });

        static::deleted(function (User $model) {
            Storage::delete('public/'.$model->avatar);
        });
    }
}
