<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('topic_name');
            $table->string('topic');
            $table->longText('body');
            $table->string('logged_at');
            $table->string('has_tag');
            $table->string('tags')->nullable();;
            $table->string('has_file');
            $table->string('files_name')->nullable();;
            $table->string('files_url')->nullable();;
            $table->string('revoke');
            $table->string('seen');
            $table->string('has_unread');
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
        Schema::dropIfExists('posts');
    }
}
