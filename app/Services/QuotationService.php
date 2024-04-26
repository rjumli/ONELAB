<?php

namespace App\Services;

use App\Models\Laboratory;
use App\Models\UserRole;
use App\Models\Configuration;
use App\Models\Quotation;
use App\Models\QuotationSample;
use App\Models\QuotationAnalysis;
use App\Models\ListDropdown;
use App\Http\Resources\QuotationResource;
use App\Http\Resources\AnalysisResource;
use App\Http\Resources\SampleResource;

class QuotationService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function lists($request){
        $data = QuotationResource::collection(
            Quotation::query()
            ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
            ->with('laboratory:id,name','type:id,name','purpose:id,name','status:id,name,color,others','discounted:id,name,value')
            ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('conforme:id,name,contact_no')
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
            ->when($request->laboratory, function ($query, $laboratory) {
                $query->where('laboratory_type',$laboratory)->where('laboratory_id',$this->laboratory);
            })
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function samples($request){
        $data = SampleResource::collection(
            QuotationSample::query()->with('analyses.testservice.method.method','analyses.testservice.testname','analyses.sample')
            ->where('quotation_id',$request->id)
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")->orWhere('name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at','ASC')
            ->get()
        );
        return $data;
    }

    public function save($request){
        $data = Quotation::create(array_merge($request->all(),[
            'mode' => json_encode($request->mode),
            'status_id' => 13,
            'laboratory_id' => $this->laboratory,
            'analyses' => json_encode([]),
            'customer_id' => $request->customer['value'],
            'created_by' => \Auth::user()->id
        ]));

        return [
            'data' => $data,
            'message' => 'Quotation creation was successful!', 
            'info' => "You've successfully created the new quotation."
        ];
    }

    private function generateCode($data){
        $laboratory_type = $data->laboratory_type;
        $lab = Laboratory::where('id',$this->laboratory)->first();
        $year = date('Y'); 
        $lab_type = ListDropdown::select('others')->where('id',$laboratory_type)->first();
        $c = Quotation::where('laboratory_type',$laboratory_type)
        ->where('laboratory_id',$this->laboratory)
        ->whereYear('created_at',$year)
        ->where('code','!=',NULL)->count();
        $code = $lab->code.'QOU-'.$lab_type->others.'-'.date('Y').str_pad(($c+1), 3, '0', STR_PAD_LEFT);  
        return $code;
    }

    public function saveSample($request){
        $data = QuotationSample::create($request->all());
        $data = QuotationSample::with('analyses.status','analyses.testservice.method.method','analyses.sample')->where('id',$data->id)->first();
        
        return [
            'data' => $data,
            'message' => 'Sample added was successful!', 
            'info' => "You've successfully created the new sample."
        ];
    }

    public function saveOne($request){
        $data = QuotationAnalysis::create(array_merge($request->all(),[
            'status_id' => 9,
        ]));
        $data = QuotationAnalysis::with('sample','testservice.method.method')->where('id',$data->id)->first();
        $this->updateTotal($data->sample->quotation_id,$request->fee);

        return [
            'data' => new AnalysisResource($data),
            'message' => 'Analysis added was successful!', 
            'info' => "You've successfully created the new analysis."
        ];
    }

    public function saveMany($request){
        $samples = $request->samples;
        foreach($samples as $sample){
            $data = QuotationAnalysis::create(array_merge($request->all(),[
                'sample_id' => $sample
            ]));
            if($data){
                $data = QuotationAnalysis::with('sample','testservice.method.method')->where('id',$data->id)->first();
                $this->updateTotal($data->sample->quotation_id,$request->fee);
            }
        }

        return [
            'data' => 'Completed',
            'message' => 'Analysis added was successful!', 
            'info' => "You've successfully created the new analysis."
        ];
    }

    private function updateTotal($id,$fee){
        $data = Quotation::with('discounted')->where('id',$id)->first();
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
    }

    public function cancel($request){
        $data = Quotation::find($request->id);
        $data->update($request->except(['option']));
        
        return [
            'data' => $data,
            'message' => 'Quotation cancellation was successful!', 
            'info' => "You've successfully updated the quotation status.",
        ];
    }

    public function confirm($request){
        $data = Quotation::where('id',$request->id)->first();
        $data->status_id = $request->status_id;
        $data->code = $this->generateCode($data);
        $data->save();

        $final =  Quotation::query()
        ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
        ->with('laboratory:id,name','purpose:id,name','status:id,name,color,others')
        ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        ->with('conforme:id,name,contact_no')
        ->where('id',$request->id)
        ->first();
        return [
            'data' => new QuotationResource($final),
            'message' => 'Quotation was successfully confirmed!', 
            'info' => "You've successfully updated the quotation status.",
        ];
    }

    public function PRINT($request){
        $id = $request->id;
        $quotation =  Quotation::query()
        ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
        ->with('laboratory:id,name','purpose:id,name','status:id,name,color,others')
        ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        ->with('conforme:id,name,contact_no')
        ->where('id',$request->id)
        ->first();

        $samples = QuotationAnalysis::query()->with('testservice.method.method','testservice.testname','sample')
        ->whereHas('sample',function ($query) use ($id) {
            $query->whereHas('quotation',function ($query) use ($id) {
                $query->where('id',$id);
            });
        })
        ->orderBy('created_at','ASC')
        ->get();

        $groupedData = [];
        foreach ($samples as $row) {
            $sampleName = $row['sample']['name'];
            $testName = $row['testservice']['testname']['name'];
            $testMethod = $row['testservice']['method']['method']['name'];
            
            $key = $sampleName . "_" . $testName . "_" . $testMethod;
            
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
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

        $descs = QuotationSample::query()
        ->where('quotation_id',$id)
        ->get();

        $head = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
        ->where('laboratory_id',$quotation->laboratory_id)->whereHas('role',function ($query){
            $query->where('name','Technical Manager');
        })->first();


        $array= [
            'configuration' => Configuration::first(),
            'quotation' => new QuotationResource($quotation),
            'samples' => $samples,
            'descs' => $descs,
            'manager' => $head->user->profile->firstname.' '.$head->user->profile->middlename[0].'. '.$head->user->profile->lastname,
            'user' => \Auth::user()->profile->firstname.' '.\Auth::user()->profile->middlename[0].'. '.\Auth::user()->profile->lastname
        ]; 
        // dd($array);
        $pdf = \PDF::loadView('reports.quotation',$array)->setPaper('a4', 'portrait');
        return $pdf->download('Quotation.pdf');
    }

}
