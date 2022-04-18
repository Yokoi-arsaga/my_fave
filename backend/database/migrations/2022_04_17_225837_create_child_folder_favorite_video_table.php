<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildFolderFavoriteVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_folder_favorite_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('favorite_video_id')->constrained('favorite_videos');
            $table->foreignId('child_folder_id')->constrained('child_folders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('child_folder_favorite_video');
    }
}
