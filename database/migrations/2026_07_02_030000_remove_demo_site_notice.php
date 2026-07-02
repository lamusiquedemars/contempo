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

        DB::table('site_notices')
            ->where('title', 'Annonce démo')
            ->where('message', 'Ce bloc est une annonce courte indépendante des pages. Si aucune annonce active n’existe, rien ne s’affiche.')
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
