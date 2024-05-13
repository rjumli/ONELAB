<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\TsrSample;
use App\Models\TsrAnalysis;
use App\Models\UserRole;
use App\Models\Laboratory;
use App\Models\ListDropdown;
use App\Models\Configuration;
use App\Http\Resources\TsrResource;
use App\Http\Resources\Tsr\ListResource;

class RequestService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function lists($request){
        $data = TsrResource::collection(
            Tsr::query()
            ->with('received:id','received.profile:id,firstname,lastname,user_id')
            ->with('laboratory','laboratory_type:id,name','purpose:id,name','status:id,name,color,others')
            ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('conforme:id,name,contact_no')
            ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others','payment.collection:id,name','payment.type:id,name','payment.discounted:id,name,value')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")
                ->orWhereHas('customer',function ($query) use ($keyword) {
                    $query->whereHas('customer_name',function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status_id',$status);
            })
            ->when($this->laboratory, function ($query, $lab) {
                $query->where('laboratory_id',$lab);
            })
            ->when($request->laboratory, function ($query, $laboratory) {
                $query->where('laboratory_type',$laboratory);
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function save($request){
        $data = Tsr::create(array_merge($request->all(),[
            // 'code' => $this->generateCode($request),
            'status_id' => 1,
            'laboratory_id' => $this->laboratory,
            'mode' => json_encode($request->mode),
            'customer_id' => $request->customer['value'],
            'received_by' => \Auth::user()->id
        ]));
        $data->payment()->create(array_merge($request->all(),[
            'status_id' => 6,
        ]));

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

    public function confirm($request){
        $data = Tsr::where('id',$request->id)->first();
        $data->status_id = $request->status_id;
        $data->code = $this->generateCode($data);
        $data->save();

        $samples = TsrSample::where('tsr_id',$request->id)->get();
        foreach($samples as $sample){
            $s = TsrSample::findOrFail($sample->id);
            $s->code = $this->generateSampleCode($data);
            $s->save();
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
        $code = $lab->code.'-'.date('m').date('Y').'-'.$lab_type->others.'-'.str_pad(($c+1), 5, '0', STR_PAD_LEFT);  
        return $code;
    }

    private function generateSampleCode($data){
        $laboratory_type = $data->laboratory_type;
        $lab = Laboratory::where('id',$this->laboratory)->first();
        $year = date('Y'); 
        $lab_type = ListDropdown::select('others')->where('id',$laboratory_type)->first();
        $c = TsrSample::whereHas('tsr',function ($query) use ($laboratory_type) {
            $query->where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory_type);
        })->whereYear('created_at',$year)->where('code','!=','NULL')->count();
        return $lab_type->others.'-'.$year.'-'.str_pad(($c+1), 5, '0', STR_PAD_LEFT); 
    }

    public function print($request){
        $id = $request->id;
        $tsr = Tsr::query()->where('id',$id)
        ->with('received:id','received.profile:id,firstname,lastname,user_id')
        ->with('laboratory:id,name','purpose:id,name','status:id,name,color,others')
        ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        ->with('conforme:id,name,contact_no')
        ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others','payment.collection:id,name','payment.type:id,name','payment.discounted:id,name,value')
        ->first();

        $samples = TsrAnalysis::query()->with('testservice.method.method','testservice.testname','sample')
        ->whereHas('sample',function ($query) use ($id) {
            $query->whereHas('tsr',function ($query) use ($id) {
                $query->where('id',$id);
            });
        })
        ->orderBy('created_at','ASC')
        ->get();

        $groupedData = [];
        foreach ($samples as $row) {
            $sampleCode = $row['sample']['code'];
            $sampleName = $row['sample']['name'];
            $testName = $row['testservice']['testname']['name'];
            $testMethod = $row['testservice']['method']['method']['name'];
            
            $key = $sampleCode . "_" . $testName . "_" . $testMethod;
            
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    "samplecode" => $sampleCode,
                    "samplename" => $sampleName,
                    "testname" => $testName,
                    "method" => $testMethod,
                    "count" => 0,
                    "fee" => $row['fee']
                ];
            }
            $groupedData[$key]["count"] += 1;
        }
        $samples = array_values($groupedData);
        
        $head = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
        ->where('laboratory_id',$tsr->laboratory_id)->whereHas('role',function ($query){
            $query->where('name','Technical Manager');
        })->first();

        $descs = TsrSample::query()
        ->where('tsr_id',$id)
        ->get();

        $array = [
            'configuration' => Configuration::first(),
            'tsr' => new TsrResource($tsr),
            'samples' => $samples,
            'descs' => $descs,
            'manager' => $head->user->profile->firstname.' '.$head->user->profile->middlename[0].'. '.$head->user->profile->lastname,
            'user' => \Auth::user()->profile->firstname.' '.\Auth::user()->profile->middlename[0].'. '.\Auth::user()->profile->lastname
        ];

        $pdf = \PDF::loadView('reports.tsr',$array)->setPaper('a4', 'portrait');
        return $pdf->download($tsr->code.'.pdf');
    }

    public function tsrs($request){
        $data = ListResource::collection(
            Tsr::query()
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches')
            ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,paid_at,status_id','payment.status:id,name,color,others')
            ->whereHas('payment',function ($query){
                $query->where('is_paid', 0)->where('payment_id',null)->where('collection_id',null);
            })
            ->whereIn('customer_id',$request->customer_id)
            ->orderBy('created_at','DESC')
            ->get()
        );
        return $data;
    }
}
