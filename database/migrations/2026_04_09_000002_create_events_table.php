<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete();
            $table->string('titre', 100);
            $table->text('description')->nullable();
            $table->date('date_evenement');
            $table->time('heure_debut');
            $table->string('lieu', 200)->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->decimal('prix', 8, 2)->default(0);
            $table->enum('type_acces', ['public', 'sur_demande', 'prive'])->default('public');
            $table->enum('statut', ['actif', 'annule', 'complet'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
