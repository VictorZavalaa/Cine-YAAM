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
        Schema::create('comment_reaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCommentR');
            $table->unsignedBigInteger('idUserR');
            $table->string('typeR', 10);
            $table->timestamps();

            $table->unique(['idCommentR', 'idUserR']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_reaction');
    }
};
