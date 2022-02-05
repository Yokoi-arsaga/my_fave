<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'file_string',
        'full_file_name',
        'user_id',
    ];
}
