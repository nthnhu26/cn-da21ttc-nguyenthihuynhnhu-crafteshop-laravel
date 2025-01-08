<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['code', 'name', 'full_name'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
