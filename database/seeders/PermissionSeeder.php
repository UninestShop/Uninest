<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            'manage_users',
            'view_users',
            'verify_student_status',
            
            // Product Management
            'create_product_listing',
            'edit_own_product',
            'delete_own_product',
            'view_products',
            'report_product',
            
            // Transaction Management
            'initiate_transaction',
            'complete_transaction',
            'cancel_transaction',
            'report_transaction',
            
            // Location Management
            'set_meeting_location',
            'view_safe_zones',
            
            // Messaging
            'send_message',
            'block_user',
            'report_message',
            
            // Admin Specific
            'approve_products',
            'manage_categories',
            'manage_safe_zones',
            'view_reports',
            'ban_users',
            'manage_university_settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => ucwords(str_replace('_', ' ', $permission)),
                'slug' => $permission
            ]);
        }
    }
}
