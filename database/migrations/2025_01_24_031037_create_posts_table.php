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
        $table->id(); // Primary key
        $table->string('title',255);
        $table->text('description');
        $table->integer('status')->default(1); // 0 for Inactive, 1 for Active

        // Created and updated user IDs (as foreign keys)
        $table->unsignedBigInteger('created_user_id');
        $table->unsignedBigInteger('updated_user_id');

        // Foreign keys
        $table->foreign('created_user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('updated_user_id')->references('id')->on('users')->onDelete('cascade');

        $table->timestamps(); // This will automatically create `created_at` and `updated_at`
        $table->softDeletes(); // `deleted_at` column for soft deletes
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
