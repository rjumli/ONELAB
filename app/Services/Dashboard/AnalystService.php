<?php

namespace App\Services\Dashboard;

use App\Models\TsrAnalysis;

class AnalystService
{
    public function samples(){
        $laboratory = \Auth::user()->userrole->laboratory_id;
        return [
            'pending' => $this->pending($laboratory),
            'ongoing' => $this->ongoing($laboratory),
            'completed' => $this->completed($laboratory),
        ];
    }

    private function pending($laboratory){
        $data = TsrAnalysis::with('status','sample.tsr','testservice.testname','testservice.method.reference','testservice.method.method')
        ->where('status_id',10)
        ->whereHas('sample',function ($query) use ($laboratory) {
            $query->whereHas('tsr',function ($query) use ($laboratory) {
                $query->where('laboratory_id',$laboratory)->whereHas('payment',function ($query){
                    $query->whereIn('status_id',[7,8]);
                });
            });
        })
        ->get();
        return $data;
    }

    private function ongoing($laboratory){
        $data = TsrAnalysis::with('status','sample.tsr','testservice.testname','testservice.method.reference','testservice.method.method')
        ->where('status_id',11)
        ->where('analyst_id',\Auth::user()->id)
        ->whereHas('sample',function ($query) use ($laboratory) {
            $query->whereHas('tsr',function ($query) use ($laboratory) {
                $query->where('laboratory_id',$laboratory);
            });
        })
        ->get();
        return $data;
    }

    private function completed($laboratory){
        $data = TsrAnalysis::with('status','sample.tsr','testservice.testname','testservice.method.reference','testservice.method.method')
        ->where('status_id',12)
        ->whereHas('sample',function ($query) use ($laboratory) {
            $query->whereHas('tsr',function ($query) use ($laboratory) {
                $query->where('laboratory_id',$laboratory);
            });
        })
        ->get();
        return $data;
    }
}
