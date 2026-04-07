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
        Schema::create('favorite_list_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idListLI');
            $table->unsignedBigInteger('idMovieLI');
            $table->timestamps();

            $table->unique(['idListLI', 'idMovieLI']);
            $table->index(['idListLI', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_list_item');
    }
};
