<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Http\Requests\TsrRequest;
use App\Services\DropdownService;
use App\Services\Requests\SaveService;
use App\Services\Requests\ViewService;

class RequestController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownService $dropdown, SaveService $save, ViewService $view){
        $this->dropdown = $dropdown;
        $this->save = $save;
        $this->view = $view;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'customer':
                return $this->view->customer($request);
            break;
            case 'print':
                return $this->view->print($request);
            break;
            case 'tsrs':
                return $this->view->tsrs($request);
            break;
            default :
            return inertia('Modules/Requests/Index',[
                'dropdowns' => [
                    'laboratories' => $this->dropdown->laboratory_types(),
                    'purposes' => $this->dropdown->purposes(),
                    'modes' => $this->dropdown->modes(),
                    'discounts' => $this->dropdown->discounts(),
                    'statuses' => $this->dropdown->statuses('Request'),
                    'services' => $this->dropdown->services()
                ]
            ]);
        }
    }

    public function store(TsrRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->tsr($request);
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
                case 'Confirm':
                    return $this->save->confirm($request);
                break;
                case 'Cancel':
                    return $this->save->cancel($request);
                break;
                case 'release':
                    return $this->save->release($request);
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
