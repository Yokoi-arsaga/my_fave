<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequests extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'applicant_id',
        'destination_id',
        'message',
    ];
}
