<?php

namespace App\Services\Insights;

use App\Models\Tsr;
use App\Models\TsrPayment;
use App\Models\ListDropdown;
use App\Models\Configuration;
use App\Http\Resources\DefaultResource;

class LaboratoryService
{
    public $laboratory;

    public function __construct()
    {   
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
     }

    public function total_request(){
        return Tsr::where('laboratory_id',$this->laboratory)->where('status_id',4)->count();
    }

    public function total_earnings(){
        return TsrPayment::whereHas('tsr', function ($query) {
            $query->where('laboratory_id',$this->laboratory);
        })->where('status_id',7)->where('is_paid',1)->sum('total');
    }

    public function info($request){
        $year = $request->year;
        $total = [
            ['name' => 'Total Requests', 'value' => Tsr::where('laboratory_id',$this->laboratory)->whereYear('created_at',$year)->where('status_id',4)->count()],
            ['name' => 'Total Earnings', 'value' => TsrPayment::whereHas('tsr', function ($query) {
                $query->where('laboratory_id',$this->laboratory);
            })->whereYear('created_at',$year)->where('status_id',7)->where('is_paid',1)->sum('total')],
            ['name' => 'Pending Collection', 'value' => 0],
        ];

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        foreach($total as $list){
            $data = [];
            for($month = 1; $month <= 12; $month++){
                if($list['name'] == 'Total Requests'){
                    $count = Tsr::where('laboratory_id',$this->laboratory)->whereYear('created_at',$year)->whereMonth('created_at',$month)->count();
                }else if($list['name'] == 'Total Earnings'){
                    $count = TsrPayment::whereHas('tsr', function ($query) {
                        $query->where('laboratory_id',$this->laboratory);
                    })->whereYear('created_at',$year)->whereMonth('created_at',$month)->where('status_id',7)->where('is_paid',1)->sum('total');
                }else{
                    $count = 0;
                }
                $data[] = $count;
            }
            $arr[] = [
                'name' => $list['name'],
                'data' => $data  
            ];
        }

        return $y =[
            'categories' => $months,
            'lists' => $arr,
            'total' => $total
        ];
    }

    public function tsrs($request){
        $ids = Configuration::where('laboratory_id',$this->laboratory)->value('laboratories');
        $sort = ($request->sort) ? $request->sort : 'desc';
        $year = $request->year;
        $month = $request->month;
  
        $query = ListDropdown::query()->select('id','name');
        $query->whereIn('id',json_decode($ids));
        $query->withCount(['tsrs' => function ($query) use ($year,$month){
            $query->where('status_id', 4)->where('laboratory_id', $this->laboratory);
            ($year) ? $query->whereYear('created_at',$year) : '';
            ($month) ? $query->whereMonth('created_at',$month) : '';
        }])
        ->orderBy('tsrs_count', $sort);
        $data = $query->get();
        return DefaultResource::collection($data);
    }

    public function earnings($request){
        $ids = Configuration::where('laboratory_id',$this->laboratory)->value('laboratories');
        $sort = ($request->sort) ? $request->sort : 'desc';
        $year = $request->year;
        $month = $request->month;

        $query = ListDropdown::query()->whereIn('list_dropdowns.id',json_decode($ids));
        $query->select('list_dropdowns.id','list_dropdowns.name',\DB::raw('SUM(tsr_payments.total) as total'))
        ->join('tsrs', 'list_dropdowns.id', '=', 'tsrs.laboratory_type')
        ->join('tsr_payments', 'tsrs.id', '=', 'tsr_payments.tsr_id')
        ->where('tsrs.status_id',4)
        ->where('tsrs.laboratory_id',$this->laboratory)
        ->groupBy('list_dropdowns.id', 'list_dropdowns.name')
        ->orderBy('total',$sort);
        ($year) ? $query->whereYear('tsr_payments.paid_at', $year) : '';
        ($month) ? $query->whereMonth('tsr_payments.paid_at', $month) : '';
        $data = $query->get();
        return DefaultResource::collection($data);
    }
}
