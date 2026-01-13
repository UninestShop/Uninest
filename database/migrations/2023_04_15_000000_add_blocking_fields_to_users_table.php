<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlockingFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'blocked_at')) {
                $table->timestamp('blocked_at')->nullable()->after('remember_token');
            }
            
            if (!Schema::hasColumn('users', 'blocked_reason')) {
                $table->string('blocked_reason')->nullable()->after('blocked_at');
            }
            
            // In case the is_blocked column doesn't exist either
            if (!Schema::hasColumn('users', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('remember_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['blocked_at', 'blocked_reason']);
            // Only drop is_blocked if we added it in this migration
            if (!Schema::hasColumn('users', 'is_blocked_added_before')) {
                $table->dropColumn('is_blocked');
            }
        });
    }
}
