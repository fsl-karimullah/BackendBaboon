<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function boot(): void
    {
        parent::boot();
        static::updated(function (Subscription $model) {
            if ($model->status == 'SUCCESS') {
                $user = $model->user;
                $user->update([
                    'subscription_exp_date' => now()->addMonth($model->period)
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
