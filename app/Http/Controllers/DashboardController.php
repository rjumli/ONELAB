<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\AnalystService;
use App\Services\DropdownService;

class DashboardController extends Controller
{
    public function __construct(AnalystService $analyst, DropdownService $dropdown){
        $this->analyst = $analyst;
        $this->dropdown = $dropdown;
    }

    public function index(){
        $role = (\Auth::user()->userrole) ? \Auth::user()->userrole->role->name : null;
        switch($role){
            case 'Customer Relation Officer':
                return inertia('Modules/Dashboard/CRO/Index');
            break;
            case 'Analyst':
                return inertia('Modules/Dashboard/Analyst/Index',[
                    'samples' => $this->analyst->samples()
                ]);
            break;
            case 'Accountant':
                return inertia('Modules/Dashboard/Accountant/Index',[
                    'dropdowns' => [
                        'laboratories' => $this->dropdown->laboratory_types(),
                        'collections' => $this->dropdown->collections(),
                        'payments' => $this->dropdown->payments(),
                        'statuses' => $this->dropdown->statuses('Payment'),
                    ]
                ]);
            break;
            case 'Cashier':
                return inertia('Modules/Dashboard/Accountant/Index',[
                    'dropdowns' => [
                        'laboratories' => $this->dropdown->laboratory_types(),
                        'collections' => $this->dropdown->collections(),
                        'payments' => $this->dropdown->payments(),
                        'deposits' => $this->dropdown->deposits(),
                        'orseries' => $this->dropdown->orseries(),
                        'statuses' => $this->dropdown->statuses('Payment'),
                    ]
                ]);
            break;
            default:
                return inertia('Modules/Dashboard/Index',[
                    'laboratories' => $this->dropdown->laboratory_all(),
                ]);
        }   
    }
}
