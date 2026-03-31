<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interets', function (Blueprint $table) {
            $table->string('categorie')->after('nom')->default('autre');
        });
    }

    public function down(): void
    {
        Schema::table('interets', function (Blueprint $table) {
            $table->dropColumn('categorie');
        });
    }
};
