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
        Schema::create('favorite_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUserL');
            $table->string('nameL', 100);
            $table->timestamps();

            $table->index(['idUserL', 'created_at']);
            $table->unique(['idUserL', 'nameL']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_list');
    }
};
