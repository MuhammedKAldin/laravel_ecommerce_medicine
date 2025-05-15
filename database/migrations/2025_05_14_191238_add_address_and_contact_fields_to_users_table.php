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
        Schema::table('users', function (Blueprint $table) {
            // Address Information
            $table->string('country')->nullable();
            $table->string('street_address')->nullable();
            $table->string('apartment')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            
            // Contact Information
            $table->string('phone')->nullable();
            
            // Split name into first_name and last_name
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'street_address',
                'apartment',
                'city',
                'postcode',
                'phone',
                'first_name',
                'last_name'
            ]);
        });
    }
};
