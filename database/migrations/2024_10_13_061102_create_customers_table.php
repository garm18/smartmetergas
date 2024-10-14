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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('name'); //nama
            $table->string('noTelp'); //no telephone
            $table->string('address'); //alamat
            // provinces
            $table->char('province_id', 2);
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('restrict');

            // regencies
            $table->char('regency_id', 4);
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('restrict');

            // districts
            $table->char('district_id', 7);
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('restrict');

            // villages
            $table->char('village_id', 10);
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
        });

        Schema::dropIfExists('customers');
    }
};
