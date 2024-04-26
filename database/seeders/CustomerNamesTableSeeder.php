<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomerNamesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customer_names')->delete();
        
        \DB::table('customer_names')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'Coca-Cola Beverages Philippines, Inc.',
                'has_branches' => 1,
                'is_active' => 1,
                'is_synced' => 0,
                'created_at' => '2024-04-01 08:43:12',
                'updated_at' => '2024-04-01 08:43:12',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'John Niño S. Potenciano',
                'has_branches' => 0,
                'is_active' => 1,
                'is_synced' => 0,
                'created_at' => '2024-04-16 16:11:54',
                'updated_at' => '2024-04-16 16:11:54',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'Jenelyn L. Ibabao',
                'has_branches' => 0,
                'is_active' => 1,
                'is_synced' => 0,
                'created_at' => '2024-04-17 09:28:22',
                'updated_at' => '2024-04-17 09:28:22',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'Shembery Anne M. Alanano',
                'has_branches' => 0,
                'is_active' => 1,
                'is_synced' => 0,
                'created_at' => '2024-04-17 09:31:10',
                'updated_at' => '2024-04-17 09:31:10',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'Aquatic Food Manufacturing Corporation',
                'has_branches' => 0,
                'is_active' => 1,
                'is_synced' => 0,
                'created_at' => '2024-04-18 10:30:57',
                'updated_at' => '2024-04-18 10:30:57',
            ),
        ));

        
    }
}