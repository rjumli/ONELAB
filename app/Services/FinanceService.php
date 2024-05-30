<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\UserRole;
use App\Models\FinanceOp;
use App\Models\TsrPayment;
use App\Models\FinanceReceipt;
use App\Models\FinanceOpItem;
use App\Models\FinanceOrseries;
use App\Models\Configuration;
use App\Http\Resources\Finance\OpResource;

class FinanceService
{
    public function store_op($request){
        $payment_id = $request->payment_id;
        $collection_id = $request->collection_id;
        $data = FinanceOp::create(array_merge($request->all(), [
            'code' => $this->generateCode(), 
            'created_by' => \Auth::user()->id, 
            'status_id' => 6,
            'laboratory_id' => \Auth::user()->userrole->laboratory_id
        ]));
        $id = $data->id;
        if($data){
            $items = $request->selected;
            foreach($items as $item){
                $opitem = new FinanceOpItem;
                $opitem->amount = $item['payment']['total'];
                $opitem->tsr_id = $item['id'];
                $opitem->op_id = $id;
                if($opitem->save()){
                    $payment = TsrPayment::findOrFail($item['id']);
                    $payment->collection_id = $collection_id;
                    $payment->payment_id = $payment_id;
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
        $data = FinanceReceipt::create(array_merge($request->all(), [
            'number' => $request->orseries['next'],
            'orseries_id' => $request->orseries['value'],  
            'op_id' => $request->selected['id'],
            'payor_id' => $request->selected['customer']['id'],
            'created_by' => \Auth::user()->id,
            'laboratory_id' => \Auth::user()->userrole->laboratory_id
        ]));

        if($data){
            $items = $request->selected['items'];
            $op = FinanceOp::where('id',$request->selected['id'])->first();
            $op->status_id = 7;
            if($op->save()){
                foreach($items as $item){
                    $id = $item['tsr_id'];
                    $payment = TsrPayment::where('tsr_id',$id)->first();
                    $payment->or_number = $request->orseries['next'];
                    $payment->is_paid = 1;
                    $payment->paid_at = now();
                    $payment->status_id = 7;
                    if($payment->save()){
                        $tsr = Tsr::where('id',$id)->first();
                        $tsr->status_id = 3;
                        $tsr->save();
                    }
                }

                $or = FinanceOrseries::where('id',$request->orseries['value'])->first();
                $next = $or->next+1;
                $or->next = $next;
                if($next == $or->end){
                    $or->is_active = 0;
                }
                $or->save();
            }
        }

        return [
            'data' => $data,
            'message' => 'Receipt creation was successful!', 
            'info' => "You've successfully created the new receipt."
        ];
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
            ->with('items.tsr','or')
            ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
            ->with('collection:id,name','payment:id,name','status:id,name,color,others')
            ->with('customer:id,name_id,name,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
            ->with('customer.contact:id,email,contact_no,customer_id')
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

    public function print($request){
        $id = $request->id;
        $op = FinanceOp::query()->where('id',$id)
        ->with('items.tsr.samples.analyses.testservice.testname')
        ->with('createdby:id','createdby.profile:id,firstname,lastname,user_id')
        ->with('collection:id,name','payment:id,name','status:id,name,color,others')
        ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        ->first();
        
        $cashier = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
        ->where('laboratory_id',$op->laboratory_id)->whereHas('role',function ($query){
            $query->where('name','Cashier');
        })->first();

        $accountant = UserRole::with('user:id','user.profile:id,user_id,firstname,middlename,lastname')
        ->where('laboratory_id',$op->laboratory_id)->whereHas('role',function ($query){
            $query->where('name','Accountant');
        })->first();

        $array = [
            'configuration' => Configuration::first(),
            'op' => new OpResource($op),
            'cashier' => $cashier->user->profile->firstname.' '.$cashier->user->profile->middlename[0].'. '.$cashier->user->profile->lastname,
            'accountant' => $accountant->user->profile->firstname.' '.$accountant->user->profile->middlename[0].'. '.$accountant->user->profile->lastname,
        ];

        $pdf = \PDF::loadView('printings.op',$array)->setPaper('a4', 'portrait');
        return $pdf->download($op->code.'.pdf');
    }
}
