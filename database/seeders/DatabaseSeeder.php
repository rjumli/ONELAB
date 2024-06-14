<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \DB::table('users')->insert([
        //     'username' => 'administrator',
        //     'email' => 'kradjumli@gmail.com',
        //     'password' => bcrypt('123456789'),
        //     'role' => 'Administrator',
        //     'is_active' => 0,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        // \DB::table('user_profiles')->insert([
        //     'firstname' => 'Ra-ouf',
        //     'lastname' => 'Jumli',
        //     'middlename' => 'Indanan',
        //     'avatar' => 'avatar.jpg',
        //     'gender' => 'Male',
        //     'user_id' => 1,
        //     'mobile' => '09171531652',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
       
        // $this->call(LocationRegionsTableSeeder::class);
        // $this->call(LocationProvincesTableSeeder::class);
        // $this->call(LocationMunicipalitiesTableSeeder::class);
        // $this->call(LocationBarangaysTableSeeder::class);

        // $this->call(ListMenusTableSeeder::class);
        // $this->call(ListStatusesTableSeeder::class);
        // $this->call(ListDropdownsTableSeeder::class);
        // $this->call(ListRolesTableSeeder::class);
        // $this->call(ListDiscountsTableSeeder::class);
        
        // $this->call(MembersTableSeeder::class);
        // $this->call(LaboratoriesTableSeeder::class);
        // $this->call(AddressesTableSeeder::class);
        // $this->call(CsfQuestionsTableSeeder::class);
        
        // $this->call(UsersTableSeeder::class);
        // $this->call(UserProfilesTableSeeder::class);
        // $this->call(UserRolesTableSeeder::class);

        $this->call(InventorySuppliersTableSeeder::class);
        // $this->call(ListServicesTableSeeder::class);
    }
}
