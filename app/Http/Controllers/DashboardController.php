<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\AnalystService;
use App\Services\Dashboard\FinanceService;
use App\Services\Dashboard\ReleasingService;
use App\Services\Dashboard\MainService;
use App\Services\DropdownService;

class DashboardController extends Controller
{
    public function __construct(FinanceService $finance, 
                                AnalystService $analyst, 
                                DropdownService $dropdown, 
                                ReleasingService $releasing, 
                                MainService $main)
    {
        $this->analyst = $analyst;
        $this->dropdown = $dropdown;
        $this->finance = $finance;
        $this->releasing = $releasing;
        $this->main = $main;
    }

    public function index(Request $request){
        $role = (\Auth::user()->userrole) ? \Auth::user()->userrole->role->name : null;
        switch($role){
            case 'Customer Relation Officer':
                return inertia('Modules/Dashboard/CRO/Index');
            break;
            case 'Lab Analyst':
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
                        'counts' => $this->finance->counts($request)
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
                        'statuses' => $this->dropdown->statuses('Payment'),
                        'orseries' => $this->finance->orseries(),
                        'counts' => $this->finance->counts($request)
                    ],
                    'statuses' => $this->finance->statuses($request),
                    'payments' => $this->finance->payments($request)
                ]);
            break;
            case 'Releasing Officer':
                return inertia('Modules/Dashboard/Releasing/Index',[
                    'lists' => $this->releasing->lists()
                ]);
            break;
            default:
                return inertia('Modules/Dashboard/Index',[
                    'laboratories' => $this->dropdown->laboratory_types(),
                ]);
        }   
    }
}
