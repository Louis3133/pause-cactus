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
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('licence_id')->nullable()->constrained('licences')->onDelete('cascade');
            $table->timestamps();
            $table->string('title');
            $table->longText('description');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->foreignId('serie_id')->nullable()->constrained('series')->onDelete('cascade');
            $table->integer('episode_number')->nullable();
        });

        Schema::create('post_user', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Post::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'user_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_user');
        Schema::dropIfExists('posts');
    }
};
