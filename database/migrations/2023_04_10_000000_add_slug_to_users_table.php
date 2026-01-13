<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name')->unique();
        });
        
        // Generate slugs for existing users
        $this->generateSlugsForExistingUsers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
    
    /**
     * Generate slugs for existing users
     */
    private function generateSlugsForExistingUsers()
    {
        $users = DB::table('users')->get();
        
        foreach ($users as $user) {
            $slug = \Illuminate\Support\Str::slug($user->name);
            $originalSlug = $slug;
            $count = 1;
            
            while (DB::table('users')->where('slug', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            
            DB::table('users')->where('id', $user->id)->update(['slug' => $slug]);
        }
    }
}
