<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotels extends Model
{
    use HasFactory;

    protected $table = 'hotels';

    protected $fillable = [
        'hotel_name',
        'description',
        'type',
        'contact_no',
        'address',
        'email',
        'website',
        'qr_code_path',
        'image',
        'is_active',
        'is_deleted'
    ];
}
