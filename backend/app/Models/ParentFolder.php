<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentFolder extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'folder_name',
        'description',
        'user_id',
        'disclosure_range_id',
        'is_nest'
    ];
}
