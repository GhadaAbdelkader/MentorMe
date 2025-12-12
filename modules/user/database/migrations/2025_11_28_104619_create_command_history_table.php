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
        Schema::create('command_history', function (Blueprint $table) {
            $table->id();

            $table->string('command_type');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('user_type');
            $table->json('payload');
            $table->dateTime('executed_at');
            $table->dateTime('undone_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('executed_at');
            $table->index('command_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('command_history');
    }
};
