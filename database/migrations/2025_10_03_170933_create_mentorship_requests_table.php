<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('mentorship_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentee_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('mentor_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'canceled'])
                ->default('pending');

            $table->text('message')->nullable();
            $table->unique(['mentee_id', 'mentor_id']);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('mentorship_requests');
    }
};
