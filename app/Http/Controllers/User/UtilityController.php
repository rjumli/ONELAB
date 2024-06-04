<?php

namespace App\Http\Controllers\User;

use App\Models\ListMenu;
use App\Models\Configuration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DropdownService;

class UtilityController extends Controller
{
    public function __construct(DropdownService $dropdown){
        $this->dropdown = $dropdown;
    }

    public function index($type){
        switch($type){
            case 'overview':
                return inertia('Modules/User/Utility/Pages/Overview',[
                    'configuration' =>  $this->configuration()
                ]);
            break;
            case 'users':
                return inertia('Modules/User/Utility/Pages/User',[
                    'configuration' =>  $this->configuration(),
                    'dropdowns' => [
                        'laboratories' => $this->dropdown->laboratories(),
                        'types' => $this->dropdown->laboratory_all(),
                        'roles' => $this->dropdown->roles(),
                    ]
                ]);
            break;
            case 'roles':
                return inertia('Modules/User/Utility/Pages/Role',[
                    'configuration' =>  $this->configuration()
                ]);
            break;
            case 'menus':
                return inertia('Modules/User/Utility/Pages/Menu',[
                    'configuration' =>  $this->configuration()
                ]);
            break;
            case 'access':
                return inertia('Modules/User/Utility/Pages/Access',[
                    'configuration' =>  $this->configuration(),
                    'activemenus' => $this->menus()
                ]);
            break;
            case 'authentications':
                return inertia('Modules/User/Utility/Pages/Authentication',[
                    'statistics' => [],
                    'configuration' =>  $this->configuration()
                ]);
            break;
            case 'activities':
                return inertia('Modules/User/Utility/Pages/Activity',[
                    'configuration' =>  $this->configuration()
                ]);
            break;
            case 'configurations':
                return inertia('Modules/User/Utility/Pages/System',[
                    'configuration' =>  $this->configuration()
                ]);
            break;  
            case 'backup':
                return inertia('Modules/User/Utility/Pages/Backup',[
                    'configuration' =>  $this->configuration()
                ]);
            break;  
        }
    }

    public function configuration(){
        $data = Configuration::where('id',1)->first();
        return $data;
    }

    public function menus(){
        $data = ListMenu::where('is_mother',1)->orderBy('order','ASC')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'is_active' => ($item->is_active) ? true : false,
                'is_mother' => $item->is_mother,
                'type' => $item->group
            ];
        });
        return $data;
    }
}
