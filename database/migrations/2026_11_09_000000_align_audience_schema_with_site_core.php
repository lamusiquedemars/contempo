<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('audience_contacts')) {
            Schema::table('audience_contacts', function (Blueprint $table): void {
                if (! Schema::hasColumn('audience_contacts', 'external_identity')) {
                    $table->string('external_identity')->nullable()->index();
                }

                if (! Schema::hasColumn('audience_contacts', 'consent_source')) {
                    $table->string('consent_source')->nullable();
                }

                if (! Schema::hasColumn('audience_contacts', 'consented_at')) {
                    $table->timestamp('consented_at')->nullable();
                }

                if (! Schema::hasColumn('audience_contacts', 'provider_synced_at')) {
                    $table->timestamp('provider_synced_at')->nullable();
                }

                if (! Schema::hasColumn('audience_contacts', 'provider_sync_status')) {
                    $table->string('provider_sync_status')->nullable()->index();
                }

                if (! Schema::hasColumn('audience_contacts', 'provider_sync_error')) {
                    $table->text('provider_sync_error')->nullable();
                }
            });
        }

        if (Schema::hasTable('audience_segments')) {
            Schema::table('audience_segments', function (Blueprint $table): void {
                if (! Schema::hasColumn('audience_segments', 'provider_list_id')) {
                    $table->unsignedBigInteger('provider_list_id')->nullable()->index();
                }

                if (! Schema::hasColumn('audience_segments', 'provider_synced_at')) {
                    $table->timestamp('provider_synced_at')->nullable();
                }

                if (! Schema::hasColumn('audience_segments', 'provider_sync_status')) {
                    $table->string('provider_sync_status')->nullable()->index();
                }

                if (! Schema::hasColumn('audience_segments', 'provider_sync_error')) {
                    $table->text('provider_sync_error')->nullable();
                }
            });
        }

        if (Schema::hasTable('segment_messages')) {
            Schema::table('segment_messages', function (Blueprint $table): void {
                if (! Schema::hasColumn('segment_messages', 'provider_campaign_id')) {
                    $table->unsignedBigInteger('provider_campaign_id')->nullable()->index();
                }

                if (! Schema::hasColumn('segment_messages', 'provider_status')) {
                    $table->string('provider_status')->nullable()->index();
                }

                if (! Schema::hasColumn('segment_messages', 'provider_created_at')) {
                    $table->timestamp('provider_created_at')->nullable();
                }

                if (! Schema::hasColumn('segment_messages', 'provider_sent_at')) {
                    $table->timestamp('provider_sent_at')->nullable();
                }

                if (! Schema::hasColumn('segment_messages', 'provider_last_sync_at')) {
                    $table->timestamp('provider_last_sync_at')->nullable();
                }

                if (! Schema::hasColumn('segment_messages', 'provider_error')) {
                    $table->text('provider_error')->nullable();
                }
            });
        }

        if (Schema::hasTable('segment_message_deliveries')) {
            Schema::table('segment_message_deliveries', function (Blueprint $table): void {
                if (! Schema::hasColumn('segment_message_deliveries', 'provider_raw_event_id')) {
                    $table->string('provider_raw_event_id')->nullable()->index();
                }
            });
        }

        if (Schema::hasTable('audience_brevo_events') && ! Schema::hasColumn('audience_brevo_events', 'provider_campaign_id')) {
            Schema::table('audience_brevo_events', function (Blueprint $table): void {
                $table->unsignedBigInteger('provider_campaign_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        // Migration de compatibilité : les données et traces d'envoi sont conservées.
    }
};
