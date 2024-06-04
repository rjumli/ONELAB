<?php

namespace App\Services;

use App\Models\{
    LocationRegion,
    LocationProvince,
    LocationMunicipality,
    LocationBarangay,
    ListDropdown,
    ListProgram,
    ListCourse,
    ListStatus,
    SchoolCampus,
    Laboratory,
    ListDiscount,
    ListName,
    ListTestservice,
    ListRole,
    InventorySupplier,
    FinanceOrseries,
    Configuration
};
use App\Http\Resources\TestserviceResource;

class DropdownService
{
    public function __construct()
    {
        $this->laboratory = (\Auth::check()) ? (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null : '';
        $this->ids =(\Auth::check()) ? (\Auth::user()->role == 'Administator') ? [] : json_decode(Configuration::where('laboratory_id',$this->laboratory)->value('laboratories')) : '';
    }

    public function regions(){
        $data = LocationRegion::all()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->region
            ];
        });
        return $data;
    }

    public function provinces($code){
        $data = LocationProvince::where('region_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function municipalities($code){
        $data = LocationMunicipality::where('province_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function barangays($code){
        $data = LocationBarangay::where('municipality_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function bussiness_nature(){
        $data = ListDropdown::where('classification','Bussiness Nature')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function industry_type(){
        $data = ListDropdown::where('classification','Industry Type')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function classes(){
        $data = ListDropdown::where('classification','Class')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function laboratory_types(){
        $data = ListDropdown::where('classification','Laboratory')->whereIn('id',$this->ids)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'others' => $item->others
            ];
        });
        return $data;
    }

    public function laboratory_all(){
        $data = ListDropdown::where('classification','Laboratory')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'others' => $item->others
            ];
        });
        return $data;
    }

    public function purposes(){
        $data = ListDropdown::where('classification','Purpose')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function inventory(){
        $data = ListDropdown::where('classification','Inventory')->where('type','Category')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function units(){
        $data = ListDropdown::where('classification','Inventory')->where('type','Unit')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function modes(){
        $data = ListDropdown::where('classification','Mode of Release')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }


    public function laboratories(){
        $data = Laboratory::with('member')->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->member->name,
                'short' => $item->name
            ];
        });
        return $data;
    }

    public function discounts(){
        $data = ListDiscount::with('based','type','subtype')->where('is_active',1)->get()->map(function ($item) {
            $total = ($item->subtype->name == 'Percentage') ? $item->value.'%' : '₱'.$item->value;
            $name = ($item->name === 'Regular') ? $item->name : $item->name.' ('.$total.')';
            return [
                'value' => $item->id,
                'name' => $name,
                'number' => $item->value,
                'type' => $item->type->name,
                'based' => $item->based->name,
                'subtype' => $item->subtype->name,
            ];
        });
        return $data;
    }

    public function statuses($type){
        $data = ListStatus::where('type',$type)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'color' => $item->color,
                'others' => $item->others,
            ];
        });
        return $data;
    }

    public function samples($request){
        $type = $request->type;
        $laboratory = $request->laboratory;
        $keyword = $request->keyword;
        $data = ListName::where('name', 'LIKE', "%{$keyword}%")->where('type_id',$type)->where('laboratory_type',$laboratory)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'testnames' => ListTestservice::select('testname_id')->with('testname')->where('sampletype_id',$item->id)->distinct()->get()->map(function ($item) {
                    return [ 
                        'value' => $item->testname->id,
                        'name' => $item->testname->name
                    ];
                }),
                'testservices' => TestserviceResource::collection(ListTestservice::with('sampletype','testname','method.reference','method.method')->where('sampletype_id',$item->id)->get())
            ];
        });
        return $data;
    }

    public function roles(){
        $data = ListRole::where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'has_lab' => $item->has_lab
            ];
        });
        return $data;
    }

    public function collections(){
        $data = ListDropdown::where('classification','Collection Type')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function payments(){
        $data = ListDropdown::where('classification','Payment Mode')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function deposits(){
        $data = ListDropdown::where('classification','Deposit Type')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function orseries(){
        $data = FinanceOrseries::where('user_id',\Auth::user()->id)->get()->map(function ($item) {
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
}
