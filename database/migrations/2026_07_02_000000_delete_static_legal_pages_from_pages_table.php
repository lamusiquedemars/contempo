<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pages')
            ->whereIn('slug', ['mentions-legales', 'confidentialite'])
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
