<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new
class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->string('comment_id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('post_id');
            $table->foreign('post_id')->references('post_id')->on('posts')->onDelete('cascade');
            $table->text('comment_content');
            $table->unsignedInteger('count_like')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
