<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\CsfEntry;
use App\Models\CsfRating;
use App\Models\CsfQuestion;
use App\Http\Resources\DefaultResource;

class CsfService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function lists($request){
        $data = DefaultResource::collection(
            CsfEntry::query()
            ->with('tsr','tsr.customer:id,name_id,name,is_main','tsr.customer.customer_name:id,name,has_branches')
            ->orderBy('created_at','DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function tsrs(){
        $data = Tsr::where('status_id',4)->whereNotIn('id', function($query) {
            $query->select('tsr_id')->from('csf_entries');
        })->select('id','code')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'code' => $item->code
            ];
        });
        return $data;
    }

    public function questions(){
        $data = CsfQuestion::where('is_active',1)->orderBy('sequence','ASC')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'is_overall' => $item->is_overall,
                'is_rating' => $item->is_rating,
                'is_active' => $item->is_active,
                'is_comment' => false,
                'rating' => null,
                'importance' => null,
                'answer' => null,
                'color' => null,
                'is_disabled' => ($item->id == 1) ? false : true
            ];
        });
        $data[] = [
            'id' => 12,
            'name' => 'Please write your comment/suggestions below. (Optional)',
            'is_overall' => 0,
            'is_rating' => 0,
            'is_active' => 0,
            'is_comment' => true,
            'is_submit' => true,
            'is_disabled' => false
        ];
        return $data;
    }

    public function survey($request){
        $ratings = $request->questions;
        $data = CsfEntry::create($request->all());
        if($data){
            foreach($ratings as $rating){
                if(!$rating['is_comment']){
                    $rate = new CsfRating;
                    $rate->answer = $rating['answer'];
                    $rate->rating = $rating['rating'];
                    $rate->importance = $rating['importance'];
                    $rate->question_id = $rating['id'];
                    $rate->csf_id = $data->id;
                    $rate->save();
                }
            }
        }
        return [
            'data' => $data,
            'message' => 'Suvery completed!', 
            'info' => "You've successfully submitted the csf survey.",
        ];

    }

    public function question($request){

    }
}
