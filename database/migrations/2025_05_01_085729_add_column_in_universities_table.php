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
        Schema::table('universities', function (Blueprint $table) {
            $table->string('contact_email')->nullable()->after('location');
            $table->string('country_code')->nullable()->after('contact_email');
            $table->string('mobile_number')->nullable()->after('country_code');
            $table->string('status')->default('active')->after('mobile_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            //
        });
    }
};
