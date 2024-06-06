<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Services\FinanceService;
use App\Http\Requests\FinanceRequest;

class FinanceController extends Controller
{
    use HandlesTransaction;

    public function __construct(FinanceService $finance){
        $this->finance = $finance;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->finance->lists($request);
            break;
            case 'print':
                return $this->finance->print($request);
            break;
        }
    }

    public function store(FinanceRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            if($request->option == 'op'){
                return $this->finance->store_op($request);
            }else if($request->option == 'receipt'){
                return $this->finance->store_receipt($request);
            }else if($request->option == 'series'){
                return $this->finance->store_series($request);
            }else if($request->option == 'wallet'){
                return $this->finance->store_wallet($request);
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
