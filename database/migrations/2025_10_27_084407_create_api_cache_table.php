<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('caches', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // bv. sentry, monday, flare, digitalocean, travpro
            $table->string('endpoint')->nullable(); // optioneel: sub-endpoint bv. /errors
            $table->json('data'); // gecachte API-response
            $table->timestamp('cached_at')->useCurrent(); // when it cached the data
            $table->integer('ttl')->default(900); // time in seconds  900s = 15 min
            $table->timestamps();

            $table->index('source');
            $table->index('cached_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caches');
    }
};

