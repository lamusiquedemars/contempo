<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('gallery_images')) {
            DB::table('gallery_images')->delete();
        }

        if (Schema::hasTable('galleries')) {
            DB::table('galleries')->delete();
        }

        if (Schema::hasTable('content_slots')) {
            DB::table('content_slots')
                ->whereIn('key', ['gallery.title', 'gallery.intro'])
                ->delete();
        }
    }

    public function down(): void
    {
        //
    }
};
