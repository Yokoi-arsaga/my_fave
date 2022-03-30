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

    /**
     * 親フォルダーに紐づくお気に入り動画
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteVideos()
    {
        return $this->belongsToMany(FavoriteVideo::class);
    }
}
