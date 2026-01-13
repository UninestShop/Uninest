<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('university_name')->nullable();
            $table->string('university_email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_email_verified')->default(false);
            $table->boolean('is_mobile_verified')->default(false);
            $table->enum('user_type', ['buyer', 'seller', 'both'])->default('buyer');
            $table->boolean('is_seller_verified')->default(false);
            $table->string('profile_picture')->nullable();
            $table->string('current_location')->nullable();
            $table->json('university_location')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->integer('safety_rating')->default(0);
            $table->integer('successful_transactions')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'university_email',
                'university_name',
                'mobile_number',
                'otp',
                'otp_expires_at',
                'is_email_verified',
                'is_mobile_verified',
                'user_type',
                'is_seller_verified',
                'profile_picture',
                'current_location',
                'university_location',
                'last_active_at',
                'is_blocked',
                'safety_rating',
                'successful_transactions'
            ]);
        });
    }
};
