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
        // Step 1: Add new columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('group', 100)->default('guest');
            $table->string('noTelp')->nullable(); //no telephone
            $table->string('address')->nullable(); //alamat
            $table->char('province_id', 2)->nullable();
            $table->char('regency_id', 4)->nullable();
            $table->char('district_id', 7)->nullable();
            $table->char('village_id', 10)->nullable();
        });

        // Step 3: Remove foreign keys from customers table if it exists
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropForeign(['province_id']);
                $table->dropForeign(['regency_id']);
                $table->dropForeign(['district_id']);
                $table->dropForeign(['village_id']);
            });

            Schema::dropIfExists('customers');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['group', 'noTelp', 'address', 'province_id', 'regency_id', 'district_id', 'village_id']);
        });

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
};
