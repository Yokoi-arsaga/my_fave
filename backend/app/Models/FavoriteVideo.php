<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteVideo extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'video_url',
        'video_name',
    ];

    /**
     * お気に入り動画に紐づく親フォルダー
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parentFolders()
    {
        return $this->belongsToMany(ParentFolder::class);
    }
}
