<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'introduction',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isAuthorOf($model): bool
    {
        return $this->id === $model->user_id;
    }

    public function postNotify($instance): void
    {
        // 如果要通知的人是當前用戶，就不必通知了！
        if ($this->id === auth()->id()) {
            return;
        }

        $this->increment('notification_count');
        $this->notify($instance);
    }

    public function markAsRead(): void
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function gravatarUrl(): Attribute
    {
        $hash = md5(strtolower(trim($this->email)));

        return new Attribute(
            get: fn ($value) => 'https://www.gravatar.com/avatar/' . $hash . '?s=400&d=mp'
        );
    }
}
