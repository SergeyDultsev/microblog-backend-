<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->string('post_id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('text_content');
            $table->text('media_content')->nullable();
            $table->unsignedInteger('count_like')->default(0);
            $table->unsignedInteger('count_comment')->default(0);
            $table->timestamp('created_at')->nullable();
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
