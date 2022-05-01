<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @return BelongsToMany
     */
    public function parentFolders(): BelongsToMany
    {
        return $this->belongsToMany(ParentFolder::class);
    }

    /**
     * お気に入り動画に紐づく子フォルダー
     *
     * @return BelongsToMany
     */
    public function childFolders(): BelongsToMany
    {
        return $this->belongsToMany(ChildFolder::class);
    }

    /**
     * お気に入り動画に紐づく親フォルダー
     *
     * @return BelongsToMany
     */
    public function grandchildFolders(): BelongsToMany
    {
        return $this->belongsToMany(GrandchildFolder::class);
    }
}
