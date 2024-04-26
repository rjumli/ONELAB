<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('customers')->delete();
        
        \DB::table('customers')->insert(array (
            0 => 
            array (
                'id' => 2,
                'code' => 'R900001',
                'email' => 'coca@yahoo.com',
                'name' => 'Main',
                'contact_no' => '09172122748',
                'is_main' => 1,
                'is_active' => 1,
                'is_synced' => 1,
                'bussiness_id' => 47,
                'industry_id' => 72,
                'classification_id' => 74,
                'name_id' => 2,
                'user_id' => NULL,
                'created_at' => '2024-04-01 08:43:12',
                'updated_at' => '2024-04-01 08:43:16',
            ),
            1 => 
            array (
                'id' => 3,
                'code' => 'R900002',
                'email' => 'johnninopotenciano@gmail.com',
                'name' => 'Main',
                'contact_no' => '09614981163',
                'is_main' => 1,
                'is_active' => 1,
                'is_synced' => 1,
                'bussiness_id' => 51,
                'industry_id' => 72,
                'classification_id' => 73,
                'name_id' => 3,
                'user_id' => NULL,
                'created_at' => '2024-04-16 16:11:54',
                'updated_at' => '2024-04-16 16:12:03',
            ),
            2 => 
            array (
                'id' => 4,
                'code' => 'R900003',
                'email' => 'jenelynIbabao@gmail.com',
                'name' => 'Main',
                'contact_no' => '09569849677',
                'is_main' => 1,
                'is_active' => 1,
                'is_synced' => 0,
                'bussiness_id' => 51,
                'industry_id' => 72,
                'classification_id' => 73,
                'name_id' => 4,
                'user_id' => NULL,
                'created_at' => '2024-04-17 09:28:22',
                'updated_at' => '2024-04-17 09:28:22',
            ),
            3 => 
            array (
                'id' => 5,
                'code' => 'R900004',
                'email' => 'shemberyalanano@gmail.com',
                'name' => 'Main',
                'contact_no' => '09936929370',
                'is_main' => 1,
                'is_active' => 1,
                'is_synced' => 0,
                'bussiness_id' => 51,
                'industry_id' => 72,
                'classification_id' => 73,
                'name_id' => 5,
                'user_id' => NULL,
                'created_at' => '2024-04-17 09:31:10',
                'updated_at' => '2024-04-17 09:31:10',
            ),
            4 => 
            array (
                'id' => 6,
                'code' => 'R900005',
                'email' => 'aquaticfood@gmail.com',
                'name' => 'Main',
                'contact_no' => '982-0266',
                'is_main' => 1,
                'is_active' => 1,
                'is_synced' => 0,
                'bussiness_id' => 53,
                'industry_id' => 54,
                'classification_id' => 74,
                'name_id' => 6,
                'user_id' => NULL,
                'created_at' => '2024-04-18 10:30:57',
                'updated_at' => '2024-04-18 10:30:57',
            ),
        ));

        
    }
}