<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GrandchildFolder extends Model
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
        'is_nest',
        'child_folder_id'
    ];

    /**
     * 孫フォルダーに紐づくお気に入り動画
     *
     * @return BelongsToMany
     */
    public function favoriteVideos(): BelongsToMany
    {
        return $this->belongsToMany(FavoriteVideo::class);
    }
}
