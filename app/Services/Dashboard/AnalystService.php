<?php

namespace App\Services\Dashboard;

use App\Models\Tsr;
use App\Models\TsrAnalysis;
use App\Http\Resources\AnalysisResource;

class AnalystService
{
    public function samples(){
        $laboratory = \Auth::user()->userrole->laboratory_type;
        $data = Tsr::with('status')->whereIn('status_id',[3,4])->where('released_at',null)
        ->with(['samples' => function ($query) {
            $query->withCount([
                'analyses as analyses_count',
                'analyses as completed_analyses_count' => function ($query) {
                    $query->where('status_id', 12);
                },
                'analyses as ongoing_analyses_count' => function ($query) {
                    $query->where('status_id', 11);
                },
                'analyses as pending_analyses_count' => function ($query) {
                    $query->where('status_id', 10);
                }
            ]);
        }])
        ->get()
        ->map(function ($tsr) {
            $tsr->total_analyses_count = $tsr->samples->sum('analyses_count');
            $tsr->total_completed_analyses_count = $tsr->samples->sum('completed_analyses_count');
            $tsr->total_ongoing_analyses_count = $tsr->samples->sum('ongoing_analyses_count');
            $tsr->total_pending_analyses_count = $tsr->samples->sum('pending_analyses_count');
            $tsr_id = $tsr->id;
            return [
                'id' => $tsr->id,
                'tsr' => $tsr,
                'lists' => AnalysisResource::collection(TsrAnalysis::with('sample','result','testservice.testname','testservice.method.reference','testservice.method.method')
                ->whereHas('sample',function ($query) use ($tsr_id) {
                    $query->whereHas('tsr',function ($query) use ($tsr_id){
                        $query->where('id',$tsr_id);
                    });
                })->orderBy('status_id','ASC')->get()),
                'samples' => $tsr->samples_count,
                'analyses' => $tsr->total_analyses_count,
                'pending' => $tsr->total_pending_analyses_count,
                'ongoing' => $tsr->total_ongoing_analyses_count,
                'completed' => $tsr->total_completed_analyses_count
            ];
        });
        return $data;
    }

    private function tests($laboratory){
        // $data = TsrAnalysis::with('status','sample.tsr','testservice.testname','testservice.method.reference','testservice.method.method')
        // ->where('status_id',10)
        // ->whereHas('sample',function ($query) use ($laboratory) {
        //     $query->whereHas('tsr',function ($query) use ($laboratory) {
        //         $query->where('laboratory_type',$laboratory)->whereHas('payment',function ($query){
        //             $query->whereIn('status_id',[7,8]);
        //         });
        //     });
        // })
        // ->get();
        $data = Tsr::with('status')->whereIn('status_id',[3,4])
        // ->withCount('samples')
        ->with(['samples' => function ($query) {
            // $query->withCount('analyses');
            $query->withCount([
                'analyses as analyses_count',
                'analyses as completed_analyses_count' => function ($query) {
                    $query->where('status_id', 12);
                },
                'analyses as ongoing_analyses_count' => function ($query) {
                    $query->where('status_id', 11);
                },
                'analyses as pending_analyses_count' => function ($query) {
                    $query->where('status_id', 10);
                }
            ]);
        }])
        ->get()
        ->map(function ($tsr) {
            $tsr->total_analyses_count = $tsr->samples->sum('analyses_count');
            $tsr->total_completed_analyses_count = $tsr->samples->sum('completed_analyses_count');
            $tsr->total_ongoing_analyses_count = $tsr->samples->sum('ongoing_analyses_count');
            $tsr->total_pending_analyses_count = $tsr->samples->sum('pending_analyses_count');
            $tsr_id = $tsr->id;
            return [
                'id' => $tsr->id,
                'tsr' => $tsr,
                'lists' => AnalysisResource::collection(TsrAnalysis::with('sample','testservice.testname','testservice.method.reference','testservice.method.method')
                ->whereHas('sample',function ($query) use ($tsr_id) {
                    $query->whereHas('tsr',function ($query) use ($tsr_id){
                        $query->where('id',$tsr_id);
                    });
                })->orderBy('status_id','ASC')->get()),
                'samples' => $tsr->samples_count,
                'analyses' => $tsr->total_analyses_count,
                'pending' => $tsr->total_pending_analyses_count,
                'ongoing' => $tsr->total_ongoing_analyses_count,
                'completed' => $tsr->total_completed_analyses_count
            ];
        });
        return $data;
    }
}
