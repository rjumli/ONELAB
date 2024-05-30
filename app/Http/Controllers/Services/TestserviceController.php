<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Services\Testservices\ApiService;
use App\Services\Testservices\SaveService;
use App\Services\Testservices\ViewService;
use App\Services\DropdownService;
use App\Traits\HandlesTransaction;
use Illuminate\Http\Request;
use App\Http\Requests\NameRequest;

class TestserviceController extends Controller
{
    use HandlesTransaction;

    public function __construct(ViewService $view, SaveService $save, DropdownService $dropdown){
        $this->dropdown = $dropdown;
        $this->view = $view;
        $this->save = $save;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
                return $this->view->lists($request);
            break;
            case 'syncable':
                return $this->view->syncable();
            break;
            case 'fetch':
                return $this->dropdown->samples($request);
            break;
            case 'testservices':
                return $this->view->testservices($request);
            break;
            case 'search':
                return $this->view->search($request);
            break;
            case 'methods':
                return $this->view->methods($request);
            break;
            default : 
            return inertia('Modules/Services/Testservices/Index',[
                'dropdowns' => [
                    'laboratories' => $this->dropdown->laboratories(),
                    'types' => $this->dropdown->laboratory_types()
                ]
            ]);
        }
    }

    public function store(NameRequest $request){
        $option = $request->option;
        switch($option){
            case 'add':
                return $this->save->add($request);
            break;
            case 'create':
                $result = $this->handleTransaction(function () use ($request) {
                    return $this->save->create($request);
                });
                return back()->with([
                    'data' => $result['data'],
                    'message' => $result['message'],
                    'info' => $result['info'],
                    'status' => $result['status'],
                ]);
            break;
            case 'method':
                return $this->save->method($request);
            break;
        }
    }
}
