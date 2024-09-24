<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendUserTable extends Migration
{
    public function up()
    {
        Schema::create('friend_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('friend_id');
            $table->enum('status', ['pending', 'confirmed']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint to prevent duplicate friendships
            $table->unique(['user_id', 'friend_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('friend_user');
    }
};