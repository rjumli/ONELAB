<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\UserRole;
use App\Models\FinanceOp;
use App\Models\TsrPayment;
use App\Models\FinanceReceipt;
use App\Models\FinanceReceiptDetail;
use App\Models\FinanceOpItem;
use App\Models\FinanceOrseries;
use App\Models\FinanceCheque;
use App\Models\Configuration;
use App\Http\Resources\Finance\OpResource;
use App\Exports\OrExport;
use Maatwebsite\Excel\Facades\Excel;

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


    public function store_wallet($request){
        $id = $request->id;
        $tsr_id = $request->tsr_id;
        $wallet_id = $request->wallet_id;
        $total = trim(str_replace(',','',$request->total),'₱');
        $wallet = Wallet::where('id',$wallet_id)->first();
        if($wallet){
            $wallet->available = $wallet->available - $total;
            if($wallet->save()){
                $code = date('Ymdgis');
                $data = Tsr::where('id',$tsr_id)->first();
                $data->transaction()->create([
                    'code' => $code,
                    'amount' => $total,
                    'balance' => $wallet->available,
                    'is_credit' => 0,
                    'wallet_id' => $wallet->id
                ]);

                if($data){
                    $payment = TsrPayment::where('id',$id)->update([
                        'is_paid' => 1,
                        'payment_id' => 129,
                        'status_id' => 7,
                        'collection_id' => 107,
                        'or_number' => $code,
                        'paid_at' => now()
                    ]);

                    if($payment){
                        $tsr = Tsr::where('id',$tsr_id)->first();
                        $tsr->status_id = 3;
                        $tsr->save();
                    }
                }
            }
        }

        return [
            'data' => $data,
            'message' => 'Wallet transaction was successful!', 
            'info' => "You've successfully used the wallet."
        ];

    }

    public function store_receipt($request){
        $result = \DB::transaction(function () use ($request){
            \DB::beginTransaction();
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
                    if($or->next == $or->end){
                        $or->is_active = 0;
                    }else{
                        $next = $or->next+1;
                        $or->next = $next;
                    }

                    if($or->save()){
                        if($request->type === 'Cheque'){
                            $cheque = new FinanceReceiptDetail;
                            $cheque->number = $request->cheque_number;
                            $cheque->amount = $request->cheque_amount;
                            $cheque->bank = $request->cheque_bank;
                            $cheque->date_at = $request->cheque_cheque_at;
                            $cheque->receipt_id = $data->id;
                            if($cheque->save()){
                                $amount = trim(str_replace(',','',$request->cheque_amount),'₱');
                                $total = trim(str_replace(',','',$request->total),'₱');
                                if($amount > $total){
                                    $total = $amount - $total;
                                    $customer_id = $request->selected['customer']['id'];

                                    $wallet = Wallet::where('customer_id',$customer_id)->first();
                                    if($wallet){
                                        $wallet->total = $wallet->total + $total;
                                        $wallet->available = $wallet->available + $total;
                                        if($wallet->save()){
                                            $data->transaction()->create([
                                                'code' => date('Ymdgia'),
                                                'amount' => $total,
                                                'balance' => $wallet->available,
                                                'is_credit' => 1,
                                                'wallet_id' => $wallet->id
                                            ]);
                                            \DB::commit();  
                                        }else{
                                            $data = 'error';
                                            \DB::rollback();
                                        }
                                    }else{
                                        $wallet = new Wallet;
                                        $wallet->total = $total;
                                        $wallet->available = $total;
                                        $wallet->customer_id = $customer_id;
                                        if($wallet->save()){
                                            $data->transaction()->create([
                                                'code' => date('Ymdgis'),
                                                'amount' => $total,
                                                'balance' => $total,
                                                'is_credit' => 1,
                                                'wallet_id' => $wallet->id
                                            ]);
                                            \DB::commit();  
                                        }else{
                                            $data = 'error';
                                            \DB::rollback();
                                        }
                                    }
                                }
                            }else{
                                $data = 'error';
                                \DB::rollback();
                            }
                        }else{
                            \DB::commit();  
                        }
                    }
                }else{
                    $data = 'error';
                    \DB::rollback();
                }
            }

            return ['data' => $data];
        });

        return [
            'data' => $result['data'],
            'message' => 'Receipt creation was successful!', 
            'info' => "You've successfully created the new receipt."
        ];
    }

    public function store_series($request){
        $data = FinanceOrseries::create(array_merge($request->all(),[
            'user_id' => \Auth::user()->id,
            'is_active' => 1,
            'laboratory_id' => \Auth::user()->userrole->laboratory_id
        ]));
        if($data){
            $series = FinanceOrseries::where('user_id',\Auth::user()->id)->where('id','!=',$data->id)->update(['is_active' => 0]);
        }
        return [
            'data' => $data,
            'message' => 'OR Series creation was successful!', 
            'info' => "You've successfully created the new series."
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
                })
                ->orWhereHas('or',function ($query) use ($keyword) {
                    $query->where('number', 'LIKE', "%{$keyword}%");
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
        return Excel::download(new OrExport($id), 'or.xlsx');
    }
}
