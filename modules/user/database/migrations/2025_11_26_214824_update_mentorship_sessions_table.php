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
        Schema::table('mentorship_sessions', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])
                ->default('scheduled')
                ->change();
            $table->json('change_log')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedInteger('duration') ->change();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
