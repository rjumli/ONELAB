<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DropdownService;
use App\Traits\HandlesTransaction;
use App\Services\Inventory\SaveService;
use App\Http\Requests\InventoryRequest;

class InventoryController extends Controller
{
    use HandlesTransaction;

    public function __construct(SaveService $save, DropdownService $dropdown){
        $this->dropdown = $dropdown;
        $this->save = $save;
    }

    public function index(Request $request){
        return inertia('Modules/Inventory/Index');
    }

    public function show($code){
        switch($code){
            case 'entries':
                return inertia('Modules/Inventory/Entries/Index');
            break;
            case 'withdrawals':
                return inertia('Modules/Inventory/Withdrawals/Index');
            break;
            case 'products':
                return inertia('Modules/Inventory/Products/Index');
            break;
            case 'suppliers':
                return inertia('Modules/Inventory/Suppliers/Index',[
                    'dropdowns' => [
                        'regions' => $this->dropdown->regions()
                    ]
                ]);
            break;
        }
    }

    public function store(InventoryRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'supplier':
                    return $this->save->supplier($request);
                break;
            }
        });
        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
