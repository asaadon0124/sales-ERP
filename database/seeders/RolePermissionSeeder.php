<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // مثال: ربط كل الصلاحيات بالمدير الرئيسي
        $mainAdmin = Role::where('name', 'like', '%مدير رئيسي%')->get();

        foreach ($mainAdmin as $role) {
            $permissions = Permission::pluck('name')->toArray();
            $role->syncPermissions($permissions); // بيربط كل الصلاحيات المتاحة للشركة دي
        
        }

    }
}
