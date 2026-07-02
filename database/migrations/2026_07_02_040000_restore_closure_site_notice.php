<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_notices')) {
            return;
        }

        DB::table('site_notices')->updateOrInsert([
            'title' => 'Fermeture estivale et déménagement',
        ], [
            'message' => "L’atelier sera fermé du 20 juillet au 24 août en raison de la pause estivale et du déménagement.\n\nÀ partir du 24 août, l’atelier vous accueillera à sa nouvelle adresse :\n9 quai Arloing, 69009 Lyon.\n\nPendant cette période de transition, l’atelier sera ouvert uniquement sur rendez-vous.",
            'link_label' => null,
            'link_url' => null,
            'placement' => 'home',
            'tone' => 'info',
            'is_published' => true,
            'starts_at' => null,
            'ends_at' => '2026-08-24 09:17:23',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (Schema::hasTable('site_notices')) {
            DB::table('site_notices')
                ->where('title', 'Fermeture estivale et déménagement')
                ->delete();
        }
    }
};
