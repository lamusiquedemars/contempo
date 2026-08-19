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
                ->where('address', '32 rue de la République, 69002 Lyon')
                ->update(['address' => '9 quai Arloing, 69009 Lyon']);
        }

        if (Schema::hasTable('pages')) {
            DB::table('pages')
                ->where('slug', 'contact')
                ->where('seo_description', 'Contacter Contempo luthiers, 32 rue de la République, 69002 Lyon.')
                ->update(['seo_description' => 'Contacter Contempo luthiers, 9 quai Arloing, 69009 Lyon.']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('site_settings')) {
            DB::table('site_settings')
                ->where('address', '9 quai Arloing, 69009 Lyon')
                ->update(['address' => '32 rue de la République, 69002 Lyon']);
        }

        if (Schema::hasTable('pages')) {
            DB::table('pages')
                ->where('slug', 'contact')
                ->where('seo_description', 'Contacter Contempo luthiers, 9 quai Arloing, 69009 Lyon.')
                ->update(['seo_description' => 'Contacter Contempo luthiers, 32 rue de la République, 69002 Lyon.']);
        }
    }
};
