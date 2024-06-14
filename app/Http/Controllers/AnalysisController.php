<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalysisService;
use App\Traits\HandlesTransaction;
use App\Http\Requests\AnalysisRequest;

class AnalysisController extends Controller
{
    use HandlesTransaction;

    public function __construct(AnalysisService $analysis){
        $this->analysis = $analysis;
    }

    public function index(Request $request){
        switch($request->option){
            case 'lists':
                return $this->analysis->lists($request);
            break;
            case 'top':
                return $this->analysis->top($request);
            break;
        }
    }

    public function store(AnalysisRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            switch($request->option){
                case 'service':
                    return $this->analysis->service($request);
                break;
                case 'delete':
                    return $this->analysis->delete($request);
                break;
                default:
                    return $this->analysis->save($request);
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
                case 'start':
                    return $this->analysis->start($request);
                break;
                case 'end':
                    return $this->analysis->end($request);
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
