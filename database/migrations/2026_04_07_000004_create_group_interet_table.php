<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_interet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('interet_id')->constrained('interets')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['group_id', 'interet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_interet');
    }
};
