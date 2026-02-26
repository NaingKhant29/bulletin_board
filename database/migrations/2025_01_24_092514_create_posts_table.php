<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('title', 255);
            $table->string('description');
            $table->integer('status')->default(1); // 0 for Inactive, 1 for Active
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->unsignedBigInteger('updated_user_id')->nullable();
            $table->unsignedBigInteger('deleted_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();


            $table->foreign('created_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('deleted_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
