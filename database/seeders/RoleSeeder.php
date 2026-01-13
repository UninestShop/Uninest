<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Admin Role
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin'
        ]);

        // Moderator Role
        $moderatorRole = Role::create([
            'name' => 'Moderator',
            'slug' => 'moderator'
        ]);

        // Verified Student Role
        $studentRole = Role::create([
            'name' => 'Verified Student',
            'slug' => 'verified_student'
        ]);

        // Unverified User Role
        $unverifiedRole = Role::create([
            'name' => 'Unverified User',
            'slug' => 'unverified_user'
        ]);

        // Assign permissions to roles
        $adminRole->permissions()->sync(Permission::all());
        
        $moderatorRole->permissions()->sync(Permission::whereIn('slug', [
            'view_users', 'verify_student_status', 'view_products',
            'approve_products', 'view_reports', 'manage_safe_zones'
        ])->get());

        $studentRole->permissions()->sync(Permission::whereIn('slug', [
            'create_product_listing', 'edit_own_product', 'delete_own_product',
            'view_products', 'report_product', 'initiate_transaction',
            'complete_transaction', 'cancel_transaction', 'set_meeting_location',
            'view_safe_zones', 'send_message', 'block_user', 'report_message'
        ])->get());

        $unverifiedRole->permissions()->sync(Permission::whereIn('slug', [
            'view_products'
        ])->get());
    }
}
