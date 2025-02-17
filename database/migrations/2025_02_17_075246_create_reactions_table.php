<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who reacted
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Post being reacted to
            $table->string('type'); // Reaction type: like, love, haha, etc.
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('reactions');
    }
};
