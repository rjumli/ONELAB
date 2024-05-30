<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Services\DropdownService;
use App\Services\PackageService;
use App\Http\Requests\PackageRequest;

class PackageController extends Controller
{
    use HandlesTransaction;

    public function __construct(PackageService $package, DropdownService $dropdown){
        $this->dropdown = $dropdown;
        $this->package = $package;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
                return $this->package->lists($request);
            break;
            case 'testservices':
                return $this->package->testservices($request);
            break;
            default : 
            return inertia('Modules/Services/Packages/Index',[
                'dropdowns' => [
                    'laboratories' => $this->dropdown->laboratories(),
                    'types' => $this->dropdown->laboratory_types()
                ]
            ]);
        }
    }

    public function store(PackageRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->package->save($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }
}
