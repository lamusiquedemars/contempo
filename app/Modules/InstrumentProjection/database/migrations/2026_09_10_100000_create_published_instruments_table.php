<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('published_instruments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('cremona_id')->unique();
            $table->string('reference', 80)->nullable();
            $table->string('name');
            $table->string('family', 80)->nullable();
            $table->string('maker')->nullable();
            $table->text('description')->nullable();
            $table->decimal('sale_amount', 12, 2)->nullable();
            $table->decimal('rental_amount', 12, 2)->nullable();
            $table->string('availability', 32);
            $table->timestamp('published_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('published_instruments');
    }
};
