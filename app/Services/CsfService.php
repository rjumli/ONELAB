<?php

namespace App\Services;

use App\Models\Tsr;
use App\Models\CsfEntry;
use App\Models\CsfRating;
use App\Models\CsfQuestion;

class CsfService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }

    public function tsrs(){
        $data = Tsr::where('status_id',2)->whereNotIn('code', function($query) {
            $query->select('tsr_id')->from('csf_entries');
        })->pluck('code');
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
            'is_comment' => 1,
        ];
        return $data;
    }

    public function survey($request){
        $ratings = $request->ratings;
        $data = CsfEntry::create($request->all());
        if($data){
            foreach($ratings as $rating){
                $rate = new CsfRating;
                $rate->rating = $rating->rating;
                $rate->importance = $rating->importance;
                $rate->question_id = $rating->question_id;
                $rate->csf_id = $data->id;
                $rate->save();
            }
        }

    }

    public function question($request){

    }
}
