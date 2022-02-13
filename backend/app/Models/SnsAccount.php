<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SnsAccount extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'media_id',
        'account_url',
    ];
}
