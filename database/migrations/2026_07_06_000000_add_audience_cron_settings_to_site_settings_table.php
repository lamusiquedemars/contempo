<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('site_settings', 'audience_cron_enabled')) {
                $table->boolean('audience_cron_enabled')->default(true);
            }

            if (! Schema::hasColumn('site_settings', 'audience_cron_token')) {
                $table->string('audience_cron_token', 80)->nullable()->unique();
            }

            if (! Schema::hasColumn('site_settings', 'audience_send_limit')) {
                $table->unsignedSmallInteger('audience_send_limit')->default(25);
            }

            if (! Schema::hasColumn('site_settings', 'audience_send_domain_limit')) {
                $table->unsignedSmallInteger('audience_send_domain_limit')->default(3);
            }

            if (! Schema::hasColumn('site_settings', 'audience_send_max_seconds')) {
                $table->unsignedSmallInteger('audience_send_max_seconds')->default(180);
            }

            if (! Schema::hasColumn('site_settings', 'audience_send_max_attempts')) {
                $table->unsignedTinyInteger('audience_send_max_attempts')->default(3);
            }

            if (! Schema::hasColumn('site_settings', 'audience_excluded_domains')) {
                $table->text('audience_excluded_domains')->nullable();
            }

            if (! Schema::hasColumn('site_settings', 'audience_cron_last_ran_at')) {
                $table->timestamp('audience_cron_last_ran_at')->nullable();
            }

            if (! Schema::hasColumn('site_settings', 'audience_cron_last_result')) {
                $table->json('audience_cron_last_result')->nullable();
            }
        });

        DB::table('site_settings')
            ->whereNull('audience_cron_token')
            ->orderBy('id')
            ->get(['id'])
            ->each(fn (object $setting): int => DB::table('site_settings')
                ->where('id', $setting->id)
                ->update(['audience_cron_token' => Str::random(48)]));
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table): void {
            foreach ([
                'audience_cron_last_result',
                'audience_cron_last_ran_at',
                'audience_excluded_domains',
                'audience_send_max_attempts',
                'audience_send_max_seconds',
                'audience_send_domain_limit',
                'audience_send_limit',
                'audience_cron_token',
                'audience_cron_enabled',
            ] as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
