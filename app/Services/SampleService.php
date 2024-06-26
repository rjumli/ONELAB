<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\TsrSample;
use App\Models\TsrPayment;
use App\Models\Configuration;
use App\Models\ListDropdown;
use App\Http\Resources\SampleResource;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;

class SampleService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
        $this->configuration = Configuration::with('laboratory.address')->where('laboratory_id',$this->laboratory)->first();
    }

    public function lists($request){
        $data = SampleResource::collection(
            TsrSample::query()->with('analyses.status','analyses.testservice.method.method','analyses.testservice.testname','analyses.sample','analyses.analyst.profile')->where('tsr_id',$request->id)
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('code', 'LIKE', "%{$keyword}%")->orWhere('name', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('created_at','ASC')
            ->get()
        );
        return $data;
    }

    public function save($request){
        $data = TsrSample::create($request->all());
        $data = TsrSample::with('analyses.status','analyses.testservice.method.method','analyses.sample','analyses.analyst')->where('id',$data->id)->first();
        
        return [
            'data' => $data,
            'message' => 'Sample added was successful!', 
            'info' => "You've successfully created the new sample."
        ];
    }

    public function remove($request){
        $id = $request->id;
        $tsr_id = $request->tsr_id;
        $data = TsrSample::find($id);
        $fee = $data->analyses()->sum('fee');
        if($data->delete()){
            $payment = TsrPayment::with('discounted')->where('tsr_id',$tsr_id)->first();
            $subtotal = (float) trim(str_replace(',','',$payment->subtotal),'₱ ');
            $total = (float) trim(str_replace(',','',$payment->total),'₱ ');
            if($payment->discount_id === 1){
                $discount = 0;
                $subtotal = $subtotal - $fee;
                $total = $total - $fee;
            }else{
                $subtotal = $subtotal - $fee;
                $discount = (float) (($payment->discounted->value/100) * $subtotal);
                $total =  ((float) $total - (float) $discount);
            }
            $payment->subtotal = $subtotal;
            $payment->discount = $discount;
            $payment->total = $total;
            $payment->save();
        }
        return [
            'data' => $payment,
            'message' => 'Sample was removed successful!', 
            'info' => "You've successfully remove the sample."
        ];
    }

    private function generateCode($request){
        $laboratory_type = $request->laboratory_id; $year = date('Y');
        $lab_type = ListDropdown::select('others')->where('id',$laboratory_type)->first();
        $c = TsrSample::whereHas('tsr',function ($query) use ($laboratory_type) {
            $query->where('laboratory_id',$laboratory_type)->where('status_id','!=',1);
        })->whereYear('created_at',$year)->count();
        return $lab_type->others.'-'.$year.'-'.str_pad(($c+1), 5, '0', STR_PAD_LEFT); 
    }

    public function print($request){
        $id = $request->id;
        $sample = TsrSample::with('analyses:id,testservice_id,sample_id','analyses.testservice:id,testname_id,sampletype_id,method_id','analyses.testservice.sampletype:id,name','analyses.testservice.testname:id,name','analyses.testservice.method:id,method_id,fee','analyses.testservice.method.method:id,name,short')
        ->with('tsr:id,code,created_at,customer_id','tsr.customer:id,name_id,name,is_main','tsr.customer.customer_name:id,name,has_branches','tsr.customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','tsr.customer.address.region:code,name,region','tsr.customer.address.province:code,name','tsr.customer.address.municipality:code,name','tsr.customer.address.barangay:code,name')
        ->where('id',$id)->first();
        // return $sample;
        $qrCodeBase64 = $this->generateQrCodeBase64($id);
        dd($qrCodeBase64);

        $array = [
            'configuration' => $this->configuration,
            'sample' => $sample
        ];

        $pdf = \PDF::loadView('reports.test',$array)->setPaper('a4', 'portrait');
        return $pdf->download('TestReport.pdf');
    }

    public function generateQrCodeBase64($data) {
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new ImagickImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeImage = $writer->writeString($data);
        return base64_encode($qrCodeImage);
    }
}
