<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ListMenusTableSeeder extends Seeder
{
    /**
     * Auto generated seeder file.
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('list_menus')->truncate();
        
        \DB::table('list_menus')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Dashboard',
                'icon' => 'ri-apps-line',
                'route' => '/',
                'path' => 'Modules/Dashboard',
                'group' => 'Menu',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Dropdown',
                'icon' => 'ri-list-check',
                'route' => '/lists/dropdowns',
                'path' => 'Modules/Lists/Dropdowns',
                'group' => 'Lists',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Locations',
                'icon' => 'ri-earth-fill',
                'route' => NULL,
                'path' => 'Modules/Lists/Locations',
                'group' => 'Lists',
                'order' => 3,
                'has_child' => 1,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Regions',
                'icon' => NULL,
                'route' => '/lists/locations/regions',
                'path' => '/lists/locations/regions',
                'group' => 'Locations',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Provinces',
                'icon' => NULL,
                'route' => '/lists/locations/provinces',
                'path' => '/lists/locations/provinces',
                'group' => 'Locations',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Municipalities',
                'icon' => NULL,
                'route' => '/lists/locations/municipalities',
                'path' => '/lists/locations/municipalities',
                'group' => 'Locations',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Barangays',
                'icon' => NULL,
                'route' => '/lists/locations/barangays',
                'path' => '/lists/locations/barangays',
                'group' => 'Locations',
                'order' => 4,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Laboratories',
                'icon' => 'ri-government-line',
                'route' => '/lists/laboratories',
                'path' => 'Modules/Lists/Laboratories',
                'group' => 'Lists',
                'order' => 4,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Customers',
                'icon' => 'ri-team-fill',
                'route' => '/customers',
                'path' => 'Modules/Customers',
                'group' => 'Menu',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Services',
                'icon' => 'bx bxs-flask',
                'route' => NULL,
                'path' => 'Modules/Services',
                'group' => 'Menu',
                'order' => 4,
                'has_child' => 1,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Testservices',
                'icon' => 'bx bxs-flask',
                'route' => '/services/testservices',
                'path' => '/services/testservices',
                'group' => 'Services',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Packages',
                'icon' => 'bx bxs-flask',
                'route' => '/services/packages',
                'path' => '/services/packages',
                'group' => 'Services',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'TS Requests',
                'icon' => 'ri-hand-coin-fill',
                'route' => '/requests',
                'path' => 'Modules/Requests',
                'group' => 'Menu',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Quotation',
                'icon' => 'ri-price-tag-3-fill',
                'route' => '/quotations',
                'path' => 'Modules/Quotation',
                'group' => 'Menu',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Inventory',
                'icon' => 'ri-store-3-fill',
                'route' => NULL,
                'path' => 'Modules/Inventory',
                'group' => 'Menu',
                'order' => 11,
                'has_child' => 1,
                'is_mother' => 1,
                'is_active' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Dashboard',
                'icon' => NULL,
                'route' => '/inventory',
                'path' => '/inventory',
                'group' => 'Inventory',
                'order' => 1,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Entries',
                'icon' => NULL,
                'route' => '/inventory/entries',
                'path' => '/inventory/entries',
                'group' => 'Inventory',
                'order' => 2,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Withdrawals',
                'icon' => NULL,
                'route' => '/inventory/withdrawals',
                'path' => '/inventory/withdrawals',
                'group' => 'Inventory',
                'order' => 3,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Equipments',
                'icon' => NULL,
                'route' => '/inventory/equipments',
                'path' => '/inventory/equipments',
                'group' => 'Inventory',
                'order' => 4,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Products',
                'icon' => NULL,
                'route' => '/inventory/products',
                'path' => '/inventory/products',
                'group' => 'Inventory',
                'order' => 5,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Suppliers',
                'icon' => NULL,
                'route' => '/inventory/suppliers',
                'path' => '/inventory/suppliers',
                'group' => 'Inventory',
                'order' => 6,
                'has_child' => 0,
                'is_mother' => 0,
                'is_active' => 1,
            ),
        ));

        
    }
}