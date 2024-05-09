<?php

namespace App\Http\Controllers;

use App\Models\InventorySupplier;
use Illuminate\Http\Request;
use App\Services\DropdownService;
use App\Traits\HandlesTransaction;
use App\Services\Inventory\ViewService;
use App\Services\Inventory\SaveService;
use App\Services\Inventory\UpdateService;
use App\Http\Requests\InventoryRequest;

class InventoryController extends Controller
{
    use HandlesTransaction;

    public function __construct(SaveService $save, ViewService $view, UpdateService $update, DropdownService $dropdown){
        $this->dropdown = $dropdown;
        $this->save = $save;
        $this->view = $view;
        $this->update = $update;
    }

    public function index(Request $request){
        switch($request->option){
            case 'suppliers':
                return $this->view->suppliers($request);
            break;
            case 'items':
                return $this->view->items($request);
            break;
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'search':
                return $this->view->search($request);
            break;
            default:
                return inertia('Modules/Inventory/Index',[
                    'dropdowns' => [
                        'statistics' => $this->view->statistics(),
                        'suppliers' => $this->view->supplier_lists()
                    ]
                ]); 
        }   
    }

    public function show($code){
        switch($code){
            case 'entries':
                return inertia('Modules/Inventory/Entries/Index');
            break;
            case 'withdrawals':
                return inertia('Modules/Inventory/Withdrawals/Index');
            break;
            case 'items':
                return inertia('Modules/Inventory/Items/Index',[
                    'dropdowns' => [
                        'categories' => $this->dropdown->inventory(),
                        'units' => $this->dropdown->units(),
                        'suppliers' => $this->view->supplier_lists()
                    ]
                ]);
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
                case 'item':
                    return $this->save->item($request);
                break;
                case 'stock':
                    return $this->save->stock($request);
                break;
                case 'withdraw':
                    return $this->save->withdraw($request);
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

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'supplier':
                    return $this->update->supplier($request);
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
