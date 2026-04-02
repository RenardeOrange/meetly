<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('genre', ['homme', 'femme', 'non-binaire', 'autre'])->nullable()->after('numero_programme');
            $table->enum('orientation', ['heterosexuel', 'homosexuel', 'bisexuel', 'pansexuel', 'autre'])->nullable()->after('genre');
            $table->json('type_relation')->nullable()->after('orientation');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['genre', 'orientation', 'type_relation']);
        });
    }
};
