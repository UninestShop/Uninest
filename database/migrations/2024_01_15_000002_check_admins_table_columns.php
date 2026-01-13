<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'remember_token')) {
                $table->rememberToken();
            }
            if (!Schema::hasColumn('admins', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        // No need for rollback as these are essential columns
    }
};
