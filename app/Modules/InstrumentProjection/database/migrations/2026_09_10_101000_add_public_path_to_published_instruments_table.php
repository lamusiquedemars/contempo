<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('published_instruments', function (Blueprint $table): void {
            $table->string('slug')->nullable()->unique()->after('reference');
            $table->string('price_label')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('published_instruments', function (Blueprint $table): void {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'price_label']);
        });
    }
};
