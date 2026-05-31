<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->boolean('contact_form_show_name')->default(true);
            $table->boolean('contact_form_show_phone')->default(true);
            $table->boolean('contact_form_show_subject')->default(true);
            $table->boolean('contact_form_send_admin_email')->default(true);
            $table->boolean('contact_form_send_confirmation_email')->default(false);
        });

        if (Schema::hasTable('site_settings')) {
            DB::table('site_settings')->update([
                'contact_form_show_name' => true,
                'contact_form_show_phone' => true,
                'contact_form_show_subject' => true,
                'contact_form_send_admin_email' => true,
                'contact_form_send_confirmation_email' => false,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_form_show_name',
                'contact_form_show_phone',
                'contact_form_show_subject',
                'contact_form_send_admin_email',
                'contact_form_send_confirmation_email',
            ]);
        });
    }
};
