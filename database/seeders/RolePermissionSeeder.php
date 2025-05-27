<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create-user',
            'read-user',
            'update-user',
            'delete-user',
            'complete-challenges',
            'update-profile',
            'read-profile',
            'track-student-progress',
            'create-challenges',
            'read-challenges',
            'update-challenges',
            'delete-challenges',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $studentRole = Role::create(['name' => 'student']);
        $lecturerRole = Role::create(['name' => 'lecturer']);

        // Assign specific permissions to editor
        $adminRole->givePermissionTo(['create-user', 'read-user', 'update-user', 'delete-user']);
        $studentRole->givePermissionTo(['complete-challenges', 'update-profile', 'read-profile']);
        $lecturerRole->givePermissionTo(['track-student-progress', 'create-challenges', 'read-challenges', 'update-challenges', 'delete-challenges']);
    }
}
