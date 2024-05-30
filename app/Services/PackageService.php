<?php

namespace App\Services;

use App\Models\ListPackage;
use App\Models\ListPackageList;
use App\Models\ListTestservice;
use App\Http\Resources\TestserviceResource;
use App\Http\Resources\PackageResource;

class PackageService
{
    public function __construct()
    {
        $this->role = (\Auth::check()) ? \Auth::user()->role : null;
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function lists($request){
        $data = PackageResource::collection(
            ListPackage::query()
            ->with('type','sampletype','laboratory.member')
            ->with('lists.testservice.sampletype','lists.testservice.testname','lists.testservice.method.method','lists.testservice.method.reference')
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->where('laboratory_id',$this->laboratory)
            ->paginate($request->count)
        );
        return $data;
    }

    public function testservices($request){
        $data = TestserviceResource::collection(
            ListTestservice::query()
            ->when($this->role != 'Administrator', function ($query) {
                $query->where('laboratory_id',$this->laboratory);
            })
            ->when($request->laboratory_type, function ($query, $laboratory) {
                $query->where('laboratory_type',$laboratory);
            })
            ->when($request->sampletype_id, function ($query, $sampletype) {
                $query->where('sampletype_id',$sampletype);
            })
            ->with('sampletype','testname','laboratory.member','laboratory.address.region','type')
            ->with('method.method','method.reference')
            ->orderBy('created_at','DESC')
            ->get()
        );
        return $data;
    }

    public function save($request){
        $package = ListPackage::create(array_merge($request->all(),['laboratory_id' => $this->laboratory]));
        $lists = $request->lists;
        foreach($lists as $list){
            $p = new ListPackageList;
            $p->testservice_id = $list;
            $p->package_id = $package->id;
            $p->save();
        }
        return [
            'data' => $package,
            'message' => 'Package creation was successful!', 
            'info' => "You've successfully created the new package."
        ];
    }   
}
