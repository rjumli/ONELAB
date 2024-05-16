<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserProfilesTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_profiles')->delete();
        
        \DB::table('user_profiles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'firstname' => 'Ra-ouf',
                'lastname' => 'Jumli',
                'middlename' => 'Indanan',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09171531652',
                'avatar' => 'avatar.jpg',
                'user_id' => 1,
                'created_at' => '2024-05-15 08:46:10',
                'updated_at' => '2024-05-15 08:46:10',
            ),
            1 => 
            array (
                'id' => 2,
                'firstname' => 'Rosemarie',
                'lastname' => 'Salazar',
                'middlename' => 'Sinsuan',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09177224118',
                'avatar' => 'avatar.jpg',
                'user_id' => 2,
                'created_at' => '2024-05-15 09:20:30',
                'updated_at' => '2024-05-15 09:20:30',
            ),
            2 => 
            array (
                'id' => 3,
                'firstname' => 'Julius',
                'lastname' => 'Fojas',
                'middlename' => 'Taghap',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09668152170',
                'avatar' => 'avatar.jpg',
                'user_id' => 3,
                'created_at' => '2024-05-15 09:23:23',
                'updated_at' => '2024-05-15 09:23:23',
            ),
            3 => 
            array (
                'id' => 4,
                'firstname' => 'Joefry',
                'lastname' => 'Fernando',
                'middlename' => 'Pon',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09153907133',
                'avatar' => 'avatar.jpg',
                'user_id' => 4,
                'created_at' => '2024-05-15 09:25:38',
                'updated_at' => '2024-05-15 09:25:38',
            ),
            4 => 
            array (
                'id' => 5,
                'firstname' => 'Sonora',
                'lastname' => 'Buñag',
                'middlename' => 'Leonora',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09178494346',
                'avatar' => 'avatar.jpg',
                'user_id' => 5,
                'created_at' => '2024-05-15 09:27:16',
                'updated_at' => '2024-05-15 09:27:16',
            ),
            5 => 
            array (
                'id' => 6,
                'firstname' => 'Janice',
                'lastname' => 'Tangcalagan',
                'middlename' => 'Ong',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09954332769',
                'avatar' => 'avatar.jpg',
                'user_id' => 6,
                'created_at' => '2024-05-15 09:28:39',
                'updated_at' => '2024-05-15 09:28:39',
            ),
            6 => 
            array (
                'id' => 7,
                'firstname' => 'Ellyssa Mae',
                'lastname' => 'Pendergat',
                'middlename' => 'Agan',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09552730938',
                'avatar' => 'avatar.jpg',
                'user_id' => 7,
                'created_at' => '2024-05-15 09:29:51',
                'updated_at' => '2024-05-15 09:29:51',
            ),
            7 => 
            array (
                'id' => 8,
                'firstname' => 'Shadam',
                'lastname' => 'Suganob',
                'middlename' => 'Eroy',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09954502887',
                'avatar' => 'avatar.jpg',
                'user_id' => 8,
                'created_at' => '2024-05-15 09:35:43',
                'updated_at' => '2024-05-15 09:35:43',
            ),
            8 => 
            array (
                'id' => 9,
                'firstname' => 'Ruben Jr.',
                'lastname' => 'Lim',
                'middlename' => 'Matchon',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09176385319',
                'avatar' => 'avatar.jpg',
                'user_id' => 9,
                'created_at' => '2024-05-15 09:37:01',
                'updated_at' => '2024-05-15 09:37:01',
            ),
            9 => 
            array (
                'id' => 10,
                'firstname' => 'Noel',
                'lastname' => 'Arquiza',
                'middlename' => 'Toh',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09262142321',
                'avatar' => 'avatar.jpg',
                'user_id' => 10,
                'created_at' => '2024-05-15 09:37:46',
                'updated_at' => '2024-05-15 09:37:46',
            ),
            10 => 
            array (
                'id' => 11,
                'firstname' => 'Kevin Karl',
                'lastname' => 'Ramiso',
                'middlename' => 'Ramos',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09451422986',
                'avatar' => 'avatar.jpg',
                'user_id' => 11,
                'created_at' => '2024-05-15 09:40:51',
                'updated_at' => '2024-05-15 09:40:51',
            ),
            11 => 
            array (
                'id' => 12,
                'firstname' => 'Miguel Louis',
                'lastname' => 'Calunod',
                'middlename' => 'Atilano',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09774758971',
                'avatar' => 'avatar.jpg',
                'user_id' => 12,
                'created_at' => '2024-05-15 09:41:48',
                'updated_at' => '2024-05-15 09:41:48',
            ),
            12 => 
            array (
                'id' => 13,
                'firstname' => 'Arlene',
                'lastname' => 'Herbieto',
                'middlename' => 'Sabalde',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09052279213',
                'avatar' => 'avatar.jpg',
                'user_id' => 13,
                'created_at' => '2024-05-15 09:43:24',
                'updated_at' => '2024-05-15 09:43:24',
            ),
            13 => 
            array (
                'id' => 14,
                'firstname' => 'Gian Carlo',
                'lastname' => 'Acejas',
                'middlename' => 'Castillo',
                'suffix' => NULL,
                'gender' => 'Male',
                'mobile' => '09050633300',
                'avatar' => 'avatar.jpg',
                'user_id' => 14,
                'created_at' => '2024-05-15 09:44:02',
                'updated_at' => '2024-05-15 09:44:02',
            ),
            14 => 
            array (
                'id' => 15,
                'firstname' => 'Analyn',
                'lastname' => 'Sajiin',
                'middlename' => 'Lasola',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09567189629',
                'avatar' => 'avatar.jpg',
                'user_id' => 15,
                'created_at' => '2024-05-15 09:46:54',
                'updated_at' => '2024-05-15 09:46:54',
            ),
            15 => 
            array (
                'id' => 16,
                'firstname' => 'Jali',
                'lastname' => 'Badiola',
                'middlename' => 'Jamandre',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09177234840',
                'avatar' => 'avatar.jpg',
                'user_id' => 16,
                'created_at' => '2024-05-16 09:14:00',
                'updated_at' => '2024-05-16 09:14:00',
            ),
            16 => 
            array (
                'id' => 17,
                'firstname' => 'Ingrid',
                'lastname' => 'Abella',
                'middlename' => 'T',
                'suffix' => NULL,
                'gender' => 'Female',
                'mobile' => '09236536256',
                'avatar' => 'avatar.jpg',
                'user_id' => 17,
                'created_at' => '2024-05-16 09:45:50',
                'updated_at' => '2024-05-16 09:45:50',
            ),
        ));

        
    }
}