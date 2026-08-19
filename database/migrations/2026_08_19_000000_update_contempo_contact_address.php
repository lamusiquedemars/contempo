<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_settings')) {
            DB::table('site_settings')
                ->update(['address' => '9 quai Arloing, 69009 Lyon']);
        }

        if (Schema::hasTable('pages')) {
            DB::table('pages')
                ->where('slug', 'contact')
                ->update(['seo_description' => 'Contacter Contempo luthiers, 9 quai Arloing, 69009 Lyon.']);

            DB::table('pages')
                ->where('slug', 'atelier')
                ->update([
                    'hero_subtitle' => 'Formé à Crémone, Giovanni Corazzol reprend l atelier en 2024 pour y faire vivre une lutherie contemporaine ancrée dans l histoire lyonnaise.',
                ]);
        }
    }

    public function down(): void
    {
        // The current contact details must not be reverted to obsolete information.
    }
};
