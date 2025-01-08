<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Contact extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];
}