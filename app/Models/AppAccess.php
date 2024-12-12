<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Blade;

class AppAccess extends Model
{
    use HasFactory;

    protected $table = 'app_access';

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'token',
        'whitlisted',
    ];

    protected $casts = [
        'whitlisted' => 'array',
    ];
}
