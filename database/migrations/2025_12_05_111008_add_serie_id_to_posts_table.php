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
        Schema::table('posts', function (Blueprint $table) {
            // On ajoute la colonne, nullable (car optionnelle)
            // et on fait le lien avec la table series
            $table->foreignId('serie_id')->nullable()->constrained('series')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['serie_id']); // Supprime la clé étrangère
            $table->dropColumn('serie_id');    // Supprime la colonne
        });
    }
};
