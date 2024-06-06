<?php

namespace App\Services\Dashboard;

use App\Models\Wallet;
use App\Models\FinanceOp;
use App\Models\TsrPayment;
use App\Models\FinanceOrseries;
use App\Models\ListStatus;
use App\Models\ListDropdown;

class FinanceService
{
    public function orseries(){
        $data = FinanceOrseries::where('is_active',1)->where('user_id',\Auth::user()->id)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'start' => $item->start,
                'next' => $item->next,
                'end' => $item->end
            ];
        });
        return $data;
    }

    public function counts($request){
        return [
            $this->total($request),
            $this->pending($request),
            $this->wallet($request)
        ];
    }

    private function total($request){
        return $arr = [
            'name' => 'Total Collection',
            'icon' => 'ri-hand-coin-fill',
            'color' => 'danger',
            'total' => TsrPayment::where('status_id', 7)->where('is_paid',1)->sum('total')
        ];
    }

    private function pending($request){
        return $arr = [
            'name' => 'Pending Collection',
            'icon' => 'ri-safe-2-fill',
            'color' => 'danger',
            'total' => TsrPayment::where('is_paid', 0)->sum('total')
        ];
    }

    private function wallet($request){
        return $arr = [
            'name' => 'Wallet Balance',
            'icon' => 'ri-wallet-3-fill',
            'color' => 'danger',
            'total' => Wallet::sum('available')
        ];
    }

    public function statuses(){
        $statuses = ListStatus::select('id','name','others','type')->where('type','Payment')->withCount('status')->orderBy('status_count', 'desc')->get();
        foreach($statuses as $status){
            $array [] = [
                'id' => $status['id'],
                'name' => $status['name'],
                'count' => $status['status_count'],
                'others' => $status['others'],
                'total' => TsrPayment::where('status_id',$status['id'])->sum('total')
            ];
        }
        return $array;
    }

    public function payments($request){
        $payments = ListDropdown::select('id','name')->where('classification','Payment Mode')->withCount('payment')->orderBy('payment_count', 'desc')->get();
        foreach($payments as $payment){
            $array [] = [
                'id' => $payment['id'],
                'name' => $payment['name'],
                'count' => $payment['payment_count'],
                'total' => TsrPayment::where('payment_id',$payment['id'])->sum('total')
            ];
        }
        return $array;
    }
}
