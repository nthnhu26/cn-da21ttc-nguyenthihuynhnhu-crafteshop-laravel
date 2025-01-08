<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'user_addresses';

    // Khóa chính
    protected $primaryKey = 'address_id';

    // Các cột có thể gán giá trị hàng loạt
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address_detail',
        'id_province',
        'id_district',
        'id_ward',
        'is_default',
    ];

    // Định dạng cột ngày tháng
    protected $casts = [
        'is_default' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Quan hệ với bảng `users`
     * Một địa chỉ thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }


    /**
     * Lấy địa chỉ đầy đủ (định dạng tiện dụng)
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address_detail}, {$this->id_ward}, {$this->id_district}, {$this->id_province}";
    }

    /**
     * Phạm vi lọc địa chỉ mặc định
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
    public function ward() {
        return $this->belongsTo(Ward::class, 'id_ward', 'code');
    }
    
    public function district() {
        return $this->belongsTo(District::class, 'id_district', 'code');
    }
    
    public function province() {
        return $this->belongsTo(Province::class, 'id_province', 'code');
    }
}
