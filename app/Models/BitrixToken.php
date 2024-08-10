<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitrixToken extends Model
{
    use HasFactory;

    protected $table = 'bitrix_tokens';

    protected $fillable = [
        'domain',
        'access_token',
        'refresh_token',
        'application_token',
        'expires_at',
    ];


}
