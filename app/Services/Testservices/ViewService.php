<?php

namespace App\Services\Testservices;

use App\Models\ListName;
use App\Models\Laboratory;
use App\Models\ListMethod;
use App\Models\ListTestservice;
use App\Http\Resources\DefaultResource;

class ViewService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function lists($request){
        $data = DefaultResource::collection(
            ListTestservice::query()
            ->when($this->laboratory, function ($query, $laboratory) {
                $query->where('laboratory_id', $laboratory);
            })
            ->with('sampletype','testname','laboratory.member','laboratory.address.region','type')
            ->with('method.method','method.reference')
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function search($request){
        $keyword = $request->keyword;
        $type = $request->type;
        $laboratory = $request->laboratory_type;

        $data = ListName::where('name', 'LIKE', "%{$keyword}%")->where('type_id',$type)->where('laboratory_type',$laboratory)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
            ];
        });
        return $data;
    }

    public function methods($request){
        $laboratory = $request->laboratory_type;
        $keyword = $request->keyword;
      
        $data = DefaultResource::collection(
            ListMethod::query()
            ->withWhereHas('method', function ($query) use ($keyword){
                $query->select('id','name','short');
                $query->when($keyword, function ($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%")->orWhere('short', 'LIKE', "%{$keyword}%");
                });
            })
            ->withWhereHas('reference', function ($query) use ($keyword){
                $query->select('id','name');
                $query->when($keyword, function ($query, $keyword) {
                    $query->orWhere('name', 'LIKE', "%{$keyword}%");
                });
            })
            ->where('laboratory_type',$laboratory)
            ->paginate($request->count)
        );
        return $data;
    }

    public function syncable(){
        $data = ListTestservice::where('is_synced',0)->count();
        return $data;
    }
}
