<?php
// This migration is intentionally empty — group_members.role already supports 'admin' from initial migration.
// Adding a route-level invite/kick feature requires no schema change.
use Illuminate\Database\Migrations\Migration;
return new class extends Migration {
    public function up(): void {}
    public function down(): void {}
};
