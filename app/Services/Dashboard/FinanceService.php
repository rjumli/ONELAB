<?php

namespace App\Services\Dashboard;

use App\Models\Wallet;
use App\Models\FinanceOp;
use App\Models\TsrPayment;
use App\Models\FinanceOrseries;

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
            'total' => FinanceOp::where('status_id', 7)->sum('total')
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
}
