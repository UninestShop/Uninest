<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the old name column if it exists
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Add a generated column for name that concatenates first_name and last_name
            $table->string('name')->storedAs("CONCAT(first_name, ' ', last_name)")->after('last_name');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('name')->after('id');
        });
    }
};
