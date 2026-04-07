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
        Schema::create('comment_notification', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUserN');
            $table->unsignedBigInteger('idFromUserN');
            $table->unsignedBigInteger('idCommentN');
            $table->string('typeN', 10);
            $table->string('messageN', 255)->nullable();
            $table->boolean('isReadN')->default(false);
            $table->timestamps();

            $table->index(['idUserN', 'created_at']);
            $table->unique(['idUserN', 'idFromUserN', 'idCommentN']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_notification');
    }
};
