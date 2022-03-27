<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_folders', function (Blueprint $table) {
            $table->id();
            $table->string('folder_name');
            $table->string('description');
            $table->unsignedInteger('disclosure_range_id');
            $table->boolean('is_nest');
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
        Schema::dropIfExists('parent_folders');
    }
}
