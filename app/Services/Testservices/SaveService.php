<?php

namespace App\Services\Testservices;

use App\Models\Laboratory;
use App\Models\ListName;
use App\Models\ListDropdown;
use App\Models\ListTestservice;
use App\Models\ListMethod;

class SaveService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function create($request){
        $service = ListTestservice::create(array_merge($request->all(),['laboratory_id' => $this->laboratory]));
        return [
            'data' => $service,
            'message' => 'Testservice creation was successful!', 
            'info' => "You've successfully created the new testservice."
        ];
    }

    public function add($request){
        $name = ListName::create($request->all());
        $data = Listname::findOrFail($name->id);
        $data = [
            'value' => $data->id,
            'name' => $data->name,
        ];
        return $data;
    }

    public function method($request){
        $method = ListMethod::create($request->all());
        $data = ListMethod::with('method')->where('id',$method->id)->first();
        return $data;
    }
}
