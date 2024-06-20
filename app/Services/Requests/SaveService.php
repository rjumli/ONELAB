<?php

namespace App\Services\Requests;

use App\Models\Tsr;
use App\Models\TsrSample;
use App\Models\Laboratory;
use App\Models\ListDropdown;
use App\Models\Configuration;
use App\Http\Resources\TsrResource;

class SaveService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
    }

    public function tsr($request){
        $data = Tsr::create(array_merge($request->all(),[
            'status_id' => 1,
            'laboratory_id' => $this->laboratory,
            // 'mode' => json_encode($request->mode),
            'customer_id' => $request->customer['value'],
            'conforme_id' => $request->conforme['value'],
            'received_by' => \Auth::user()->id
        ]));
        
        $payment = (in_array($request->discount_id, [5, 6, 7])) ? ['status_id' => 8,'is_free' => 1] : ['status_id' => 6];
        $data->payment()->create(array_merge($request->all(),$payment));

        return [
            'data' => $data,
            'message' => 'TS Request creation was successful!', 
            'info' => "You've successfully created the new request."
        ];
    }

    public function cancel($request){
        $data = Tsr::find($request->id);
        $data->update($request->except(['option']));
        
        return [
            'data' => $data,
            'message' => 'TSR cancellation was successful!', 
            'info' => "You've successfully updated the tsr status.",
        ];
    }

    public function release($request){
        $data = Tsr::find($request->id);
        $data->update($request->except(['option']));
        
        return [
            'data' => $data,
            'message' => 'TSR was released successfully!', 
            'info' => "You've successfully released the tsr.",
        ];
    }

    public function confirm($request){
        $data = Tsr::with('payment')->where('id',$request->id)->first();
        $data->status_id = (in_array($data->payment->discount_id, [5, 6, 7])) ? 3 : $request->status_id;
        $data->due_at = $request->due_at;
        $data->code = $this->generateCode($data);
        if($data->save()){
            $samples = TsrSample::where('tsr_id',$request->id)->get();
            foreach($samples as $sample){
                $s = TsrSample::findOrFail($sample->id);
                $s->code = $this->generateSampleCode($data);
                $s->save();
            }
        }

        $final =  Tsr::query()
        ->with('laboratory','purpose','status','received.profile')
        ->with('customer.customer_name','conforme','customer.address.region','customer.address.province','customer.address.municipality','customer.address.barangay')
        ->with('payment.status','payment.collection','payment.type','payment.discounted')
        ->where('id',$request->id)
        ->first();
        return [
            'data' => new TsrResource($final),
            'message' => 'TSR was successfully confirmed!', 
            'info' => "You've successfully updated the tsr status.",
        ];
    }

    private function generateCode($data){
        $laboratory_type = $data->laboratory_type;
        $lab = Laboratory::where('id',$this->laboratory)->first();
        $year = date('Y'); 
        $lab_type = ListDropdown::select('others')->where('id',$laboratory_type)->first();
        $c = Tsr::where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory_type)
        ->whereYear('created_at',$year)->where('code','!=',NULL)->count();
        $code = $lab->code.'-'.date('m').date('Y').'-'.$lab_type->others.'-'.str_pad(($c+1), 4, '0', STR_PAD_LEFT);  
        return $code;
    }

    private function generateSampleCode($data){
        $laboratory_type = $data->laboratory_type;
        $year = ($this->configuration->samplecode_year) ? '-'.date('Y') : '';
        $lab = Laboratory::where('id',$this->laboratory)->first();
        $year = date('Y'); 
        $lab_type = ListDropdown::select('others')->where('id',$laboratory_type)->first();
        $c = TsrSample::whereHas('tsr',function ($query) use ($laboratory_type) {
            $query->where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory_type);
        })->whereYear('created_at',$year)->where('code','!=','NULL')->count();
        return $lab_type->others.'-'.str_pad(($c+1), 4, '0', STR_PAD_LEFT); 
    }
}
