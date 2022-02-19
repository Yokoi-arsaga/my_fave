<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FriendRequest extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'applicant_id',
        'destination_id',
        'message',
    ];
}
