<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_roles')->delete();
        
        \DB::table('user_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 2,
                'role_id' => 1,
                'created_at' => '2024-05-15 09:20:30',
                'updated_at' => '2024-05-15 09:20:30',
            ),
            1 => 
            array (
                'id' => 2,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 3,
                'role_id' => 1,
                'created_at' => '2024-05-15 09:23:23',
                'updated_at' => '2024-05-15 09:23:23',
            ),
            2 => 
            array (
                'id' => 3,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 4,
                'role_id' => 3,
                'created_at' => '2024-05-15 09:25:38',
                'updated_at' => '2024-05-15 09:25:38',
            ),
            3 => 
            array (
                'id' => 4,
                'laboratory_id' => 14,
                'laboratory_type' => 9,
                'user_id' => 5,
                'role_id' => 2,
                'created_at' => '2024-05-15 09:27:16',
                'updated_at' => '2024-05-15 09:27:16',
            ),
            4 => 
            array (
                'id' => 5,
                'laboratory_id' => 14,
                'laboratory_type' => 9,
                'user_id' => 6,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:28:39',
                'updated_at' => '2024-05-15 09:28:39',
            ),
            5 => 
            array (
                'id' => 6,
                'laboratory_id' => 14,
                'laboratory_type' => 9,
                'user_id' => 7,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:29:51',
                'updated_at' => '2024-05-15 09:29:51',
            ),
            6 => 
            array (
                'id' => 7,
                'laboratory_id' => 14,
                'laboratory_type' => 9,
                'user_id' => 8,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:35:43',
                'updated_at' => '2024-05-15 09:35:43',
            ),
            7 => 
            array (
                'id' => 8,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 9,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:37:01',
                'updated_at' => '2024-05-15 09:37:01',
            ),
            8 => 
            array (
                'id' => 9,
                'laboratory_id' => 14,
                'laboratory_type' => 9,
                'user_id' => 10,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:37:46',
                'updated_at' => '2024-05-15 09:37:46',
            ),
            9 => 
            array (
                'id' => 10,
                'laboratory_id' => 14,
                'laboratory_type' => 11,
                'user_id' => 11,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:40:51',
                'updated_at' => '2024-05-15 09:40:51',
            ),
            10 => 
            array (
                'id' => 11,
                'laboratory_id' => 14,
                'laboratory_type' => 11,
                'user_id' => 12,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:41:48',
                'updated_at' => '2024-05-15 09:41:48',
            ),
            11 => 
            array (
                'id' => 12,
                'laboratory_id' => 14,
                'laboratory_type' => 10,
                'user_id' => 13,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:43:24',
                'updated_at' => '2024-05-15 09:43:24',
            ),
            12 => 
            array (
                'id' => 13,
                'laboratory_id' => 14,
                'laboratory_type' => 10,
                'user_id' => 14,
                'role_id' => 4,
                'created_at' => '2024-05-15 09:44:02',
                'updated_at' => '2024-05-15 09:44:02',
            ),
            13 => 
            array (
                'id' => 14,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 15,
                'role_id' => 7,
                'created_at' => '2024-05-15 09:46:54',
                'updated_at' => '2024-05-15 09:46:54',
            ),
            14 => 
            array (
                'id' => 15,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 16,
                'role_id' => 6,
                'created_at' => '2024-05-16 09:14:00',
                'updated_at' => '2024-05-16 09:14:00',
            ),
            15 => 
            array (
                'id' => 16,
                'laboratory_id' => 14,
                'laboratory_type' => NULL,
                'user_id' => 17,
                'role_id' => 5,
                'created_at' => '2024-05-16 09:45:50',
                'updated_at' => '2024-05-16 09:45:50',
            ),
        ));

        
    }
}