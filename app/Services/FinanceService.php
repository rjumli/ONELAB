<?php

namespace App\Services;

use App\Models\FinanceOp;
use App\Models\FinanceOpItem;
use App\Models\TsrPayment;
use App\Http\Resources\Finance\OpResource;

class FinanceService
{
    public function store_op($request){
        $data = FinanceOp::create(array_merge($request->all(), ['code' => $this->generateCode(), 'created_by' => \Auth::user()->id, 'status_id' => 6]));
        if($data){
            $items = $request->selected;
            foreach($items as $item){
                $opitem = new FinanceOpItem;
                $opitem->amount = $item['payment']['total'];
                $opitem->tsr_id = $item['id'];
                $opitem->op_id = $data->id;
                if($opitem->save()){
                    $payment = TsrPayment::findOrFail($item['id']);
                    $payment->collection_id = $request->collection_id;
                    $payment->payment_id = $request->payment_id;
                    $payment->save();
                }
            }
        }
        $op = FinanceOp::findOrFail($data->id);

        return [
            'data' => $op,
            'message' => 'Op creation was successful!', 
            'info' => "You've successfully created the new op."
        ];
    }

    public function store_receipt($request){

    }

    private function generateCode(){
        $year = date('Y'); 
        $c = FinanceOp::whereYear('created_at',$year)->count();
        $code = date('Y').date('m').'-'.str_pad(($c+1), 5, '0', STR_PAD_LEFT);  
        return $code;
    }

    public function lists($request){
        $data = OpResource::collection(
            FinanceOp::query()
            ->with('items.tsr')
            ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
            ->with('collection:id,name','payment:id,name','status:id,name,color,others')
            ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
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
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }
}
