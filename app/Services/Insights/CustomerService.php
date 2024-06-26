<?php

namespace App\Services\Insights;

use App\Models\Tsr;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Wallet;
use App\Models\ListDropdown;
use App\Models\LocationProvince;
use App\Http\Resources\DefaultResource;

class CustomerService
{   
    public $laboratory;

    public function __construct()
    {   
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function total(){
        return Customer::where('laboratory_id',$this->laboratory)->count();
    }

    public function total_request(){
        return Tsr::where('laboratory_id',$this->laboratory)->where('status_id',4)->count();
    }

    public function total_wallet(){
        return [
            'total' => Wallet::whereHas('customer',function ($query){
                $query->where('laboratory_id',$this->laboratory);
            })->sum('available'),
            'count' => Wallet::where('available','>','00.0')->whereHas('customer',function ($query){
                $query->where('laboratory_id',$this->laboratory);
            })->count(),
        ];
    }

    public function wallet($request){
        $data = Customer::select('id','name','name_id','is_main','laboratory_id')->with('customer_name:id,name,has_branches')->with('wallet')
        ->where('laboratory_id',$this->laboratory)
        ->whereHas('wallet', function ($query) {
            $query->where('available', '>', 0)->orderBy('available','desc');
        })
        ->paginate(10);
        return DefaultResource::collection($data);
    }

    public function industry($request){
        $sort = ($request->sort) ? $request->sort : 'desc';
        $query = ListDropdown::query();
        $query->where('classification','Bussiness Nature')
        ->withCount(['customer_industry' => function ($query) {
            $query->where('laboratory_id', $this->laboratory);
        }])
        ->orderBy('customer_industry_count', $sort);
        $data = ($request->option == 'industry') ? $query->paginate(10) : $query->take(5)->get();
        return DefaultResource::collection($data);
    }

    public function tsr($request){
        $sort = ($request->sort) ? $request->sort : 'desc';
        $year = $request->year;
        $month = $request->month;

        $query = Customer::query()->select('id','name','is_main','name_id','laboratory_id')->with('customer_name:id,name,has_branches');
        $query->where('laboratory_id', $this->laboratory);
        $query->withCount(['tsrs' => function ($query) use ($year,$month){
            $query->where('status_id', 4);
            ($year) ? $query->whereYear('created_at',$year) : '';
            ($month) ? $query->whereMonth('created_at',$month) : '';
        }])
        ->orderBy('tsrs_count', $sort);
        $data = ($request->option == 'tsr') ? $query->paginate(10) : $query->take(5)->get();
        return DefaultResource::collection($data);
    }

    public function spender($request){
        $sort = ($request->sort) ? $request->sort : 'desc';
        $year = $request->year;
        $month = $request->month;

        $query = Customer::query();
        $query->select('customers.id','customers.laboratory_id', 'customers.name','customers.is_main', 'customer_names.name as customer_name','customer_names.has_branches as has_branches',\DB::raw('SUM(tsr_payments.total) as total'))
        ->join('tsrs', 'customers.id', '=', 'tsrs.customer_id')
        ->join('tsr_payments', 'tsrs.id', '=', 'tsr_payments.tsr_id')
        ->join('customer_names', 'customers.name_id', '=', 'customer_names.id')
        ->where('tsr_payments.status_id',7)
        ->where('customers.laboratory_id',$this->laboratory)
        ->groupBy('customers.id', 'customers.name')
        ->orderBy('total',$sort);
        ($year) ? $query->whereYear('tsr_payments.paid_at', $year) : '';
        ($month) ? $query->whereMonth('tsr_payments.paid_at', $month) : '';
        $data = ($request->option == 'spender') ? $query->paginate(10) : $query->take(5)->get();

        return DefaultResource::collection($data);
    }

    public function location($request){
        $provinces = Address::where('addressable_type','App\Models\Customer')
        ->groupBy('province_code')->pluck('province_code');

        $data = LocationProvince::withCount(['customers' => function ($query) use ($provinces){
            $query->where('addressable_type','App\Models\Customer');
        }])->whereIn('code',$provinces)->orderBy('customers_count','DESC')->get();

        return DefaultResource::collection($data);
    }

    public function info($request){
        $year = $request->year;
        $total = [
            ['name' => 'New Customers', 'value' => Customer::where('laboratory_id',$this->laboratory)->whereYear('created_at',$year)->count()],
            ['name' => 'Customer Transactions', 'value' => Tsr::where('laboratory_id',$this->laboratory)->where('status_id',4)->whereYear('created_at',$year)->count()],
            ['name' => 'Pending Collection', 'value' => 0],
        ];

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        foreach($total as $list){
            $data = [];
            for($month = 1; $month <= 12; $month++){
                if($list['name'] == 'New Customers'){
                    $count = Customer::where('laboratory_id',$this->laboratory)->whereYear('created_at',$year)->whereMonth('created_at',$month)->count();
                }else if($list['name'] == 'Customer Transactions'){
                    $count = Tsr::where('laboratory_id',$this->laboratory)->where('status_id',4)->whereYear('created_at',$year)->whereMonth('created_at',$month)->count();
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
}
