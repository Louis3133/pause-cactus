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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Post::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\category::class)->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'post_id']);
        });

        Schema::create('category_serie', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Serie::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\category::class)->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'serie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_serie');
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('categories');
    }
};
