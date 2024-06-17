<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListServicesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_services')->delete();
        
        \DB::table('list_services')->insert(array (
            0 => 
            array (
                'id' => 1,
                'fee' => '3000.00',
                'name' => 'On-site Calibration',
                'description' => 'More than 50 km radius from the laboratory',
                'laboratory_type' => 11,
                'laboratory_id' => 14,
                'is_active' => 1,
                'created_at' => '2024-06-10 11:46:42',
                'updated_at' => '2024-06-10 11:46:42',
            ),
            1 => 
            array (
                'id' => 2,
                'fee' => '2000.00',
                'name' => 'On-site Calibration',
                'description' => 'Within 50 km radius from the laboratory',
                'laboratory_type' => 11,
                'laboratory_id' => 14,
                'is_active' => 1,
                'created_at' => '2024-06-10 11:46:42',
                'updated_at' => '2024-06-10 11:46:42',
            ),
        ));

        
    }
}