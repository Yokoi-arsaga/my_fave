<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteVideoParentFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_video_parent_folder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('favorite_video_id')->constrained('favorite_videos');
            $table->foreignId('parent_folder_id')->constrained('parent_folders');
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
        Schema::dropIfExists('favorite_video_parent_folder');
    }
}
