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
        Schema::create('data_smartmeters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('smartmetergas_id')-> constrained()->cascadeOnDelete();
            $table->integer('volume');
            $table->tinyInteger('statusBaterai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_smartmeters');
    }
};
