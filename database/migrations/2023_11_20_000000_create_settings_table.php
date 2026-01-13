<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // Check if the max_products_per_user setting already exists
        $settingExists = DB::table('settings')
            ->where('key', 'max_products_per_user')
            ->exists();
            
        if (!$settingExists) {
            // Insert default product limit setting
            DB::table('settings')->insert([
                'key' => 'max_products_per_user',
                'value' => '10', // Default limit
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
