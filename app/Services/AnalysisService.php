<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\TsrAnalysis;
use App\Models\TsrPayment;
use App\Models\TsrService;
use App\Http\Resources\TestnameTopResource;
use App\Http\Resources\AnalysisResource;

class AnalysisService
{
    public function lists($request){
        $data = SampleResource::collection(
            TsrSample::query()->where('tsr_id',$request->id)
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")->orWhere('name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at','ASC')
            ->get()
        );
        return $data;
    }

    public function save($request){
        foreach($request->samples as $sample){
            foreach($request->lists as $list){
                $data = TsrAnalysis::create(array_merge($request->all(),[
                    'status_id' => 10,
                    'testservice_id' => $list['id'],
                    'fee' => $list['fee_num'],
                    'sample_id' => $sample
                ]));
                $data = TsrAnalysis::with('sample','testservice.method.method')->where('id',$data->id)->first();
                $total =  $this->updateTotal($data->sample->tsr_id,$list['fee']);
            }
        }
        return [
            'data' => $total,
            'message' => 'Analysis added was successful!', 
            'info' => "You've successfully created the new analysis."
        ];
    }

    public function service($request){
        $data = TsrService::create([
            'tsr_id' => $request->id,
            'service_id' => $request->service['value'],
            'fee' => $request->service['fee']
        ]);
        $total =  $this->updateTotal($request->id,$request->service['fee']);
        return [
            'data' => $total,
            'message' => 'Service added was successful!', 
            'info' => "You've successfully added a service."
        ];
    }

    public function delete($request){

    }

    private function updateTotal($id,$fee){
        $data = TsrPayment::with('discounted')->where('tsr_id',$id)->first();
        $fee = (float) trim(str_replace(',','',$fee),'₱ ');
        $subtotal = (float) trim(str_replace(',','',$data->subtotal),'₱ ');
        if($data->discount_id === 1){
            $discount = 0;
            $subtotal = $subtotal + $fee;
            $total = $subtotal;
        }else{
            $subtotal = $subtotal + $fee;
            $discount = (float) (($data->discounted->value/100) * $subtotal);
            $total =  ((float) $subtotal - (float) $discount);
        }
        $data->subtotal = $subtotal;
        $data->discount = $discount;
        $data->total = $total;
        $data->save();
        return $data;
    }

    public function start($request){
        $data = TsrAnalysis::find($request->id);
        $data->status_id = $request->status_id;
        $data->analyst_id = \Auth::user()->id;
        $data->start_at = $request->start_at;
        
        if($data->save()){
            if(TsrAnalysis::where('sample_id',$data->sample_id)->where('status_id',10)->count() === 0){
                $tsr = Tsr::where('id',$request->tsr_id)->update(['status_id' => 4]);
            }else{
                $tsr = Tsr::where('id',$request->tsr_id)->update(['status_id' => 3]);
            }
        }
        
        return [
            'data' => $data,
            'message' => 'Sample analysis successfully started!', 
            'info' => "You've successfully started the analyzation.",
        ];
    }

    public function end($request){
        $data = TsrAnalysis::find($request->id);
        $data->status_id = $request->status_id;
        $data->end_at = $request->end_at;
        $data->save();
        
        return [
            'data' => $data,
            'message' => 'TSR cancellation was successful!', 
            'info' => "You've successfully updated the tsr status.",
        ];
    }

    public function top($request){
        $year = $request->year;
        $sort = $request->sort;
        $laboratory = $request->laboratory;
        $data = TsrAnalysis::whereHas('sample',function ($query) use ($year,$laboratory){
            $query->whereHas('tsr',function ($query) use ($year,$laboratory){
                $query->where('status_id',3)->whereYear('created_at',$year)
                ->when($laboratory, function ($query, $laboratory) {
                    $query->where('laboratory_id', $laboratory);
                });
            });
        })
        ->join('list_testservices', 'tsr_analyses.testservice_id', '=', 'list_testservices.id')
        ->join('list_names', 'list_testservices.testname_id', '=', 'list_names.id')
        ->select('list_names.name', \DB::raw('COUNT(*) as count'))
        ->groupBy('list_testservices.testname_id')
        ->orderBy('count', $sort)->paginate(10);
        return TestnameTopResource::collection($data);
    }
}
