<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                roleSeeder::class,
                permissionSeeder::class,
                RolePermissionSeeder::class,
                AccountTypeSeeder::class,
                AccountsSeeder::class,
                AdminsSeeder::class,
                AdminSittingsSeeder::class,
                TreasuriesSeeder::class,
                TreasuriesDetailesSeeder::class,
                MaterialTypesSeeder::class,
                moveTypeSeeder::class,
                StoresSeeder::class,
                ItemUnitsSeeder::class,
                ItemCategorySeeder::class,
                ItemSeeder::class,
                CustomersSeeder::class,
                SuppliersCategorySeeder::class,
                SuppliersSeeder::class,
                ServantSeeder::class,
                ItemCardMoveMentTypeSeeder::class,
                ItemCardMoveMentCategorySeeder::class,
            ]);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
