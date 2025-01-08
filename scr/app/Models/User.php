<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'email',
        'password',
        'name',
        'role_id',
        'reset_password_token',
        'reset_token_expires',
        'account_status',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_password_token',
    ];

    protected $casts = [
        'reset_token_expires' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'user_id');
    }

    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'user_id')->default();
    }
    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'user_id');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
    public function getAvatarPathAttribute()
    {
        if (Str::startsWith($this->avatar_url, ['http://', 'https://'])) {
            return $this->avatar_url; // URL từ Google hoặc nguồn khác
        }

        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url); // URL từ storage
        }

        return 'https://via.placeholder.com/150'; // Ảnh mặc định
    }

    public function hasPassword()
    {
        return !is_null($this->password);
    }
}
