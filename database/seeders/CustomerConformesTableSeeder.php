<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomerConformesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customer_conformes')->delete();
        
        \DB::table('customer_conformes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'krad',
                'contact_no' => '09171531652',
                'customer_id' => 2,
                'created_at' => '2024-04-01 08:45:16',
                'updated_at' => '2024-04-01 08:45:16',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'John Niño S. Potenciano',
                'contact_no' => '09679440330',
                'customer_id' => 3,
                'created_at' => '2024-04-16 16:12:37',
                'updated_at' => '2024-04-16 16:12:37',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'John Doe',
                'contact_no' => '09123654789',
                'customer_id' => 6,
                'created_at' => '2024-04-18 10:36:47',
                'updated_at' => '2024-04-18 10:36:47',
            ),
        ));

        
    }
}