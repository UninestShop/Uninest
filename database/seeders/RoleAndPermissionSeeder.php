<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Create permissions table if it doesn't exist
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        // Create roles table if it doesn't exist
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        // Create pivot tables if they don't exist
        if (!Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function ($table) {
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->primary(['permission_id', 'role_id']);
            });
        }

        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function ($table) {
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->primary(['role_id', 'user_id']);
            });
        }

        // Seed permissions
        $permissions = [
            'manage_users',
            'view_users',
            // ...existing permissions...
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => ucwords(str_replace('_', ' ', $permission)),
                'slug' => $permission
            ]);
        }

        // Seed roles
        $roles = [
            'Administrator' => 'admin',
            'Moderator' => 'moderator',
            'Verified Student' => 'verified_student',
            'Unverified User' => 'unverified_user'
        ];

        foreach ($roles as $name => $slug) {
            Role::firstOrCreate([
                'name' => $name,
                'slug' => $slug
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
