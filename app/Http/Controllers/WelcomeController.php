<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\Tsr;
use App\Models\User;
use App\Models\Configuration;
use App\Models\ListDropdown;
use App\Models\Laboratory;
use App\Models\TsrSample;
use App\Models\TsrAnalysis;
use Illuminate\Http\Request;
use App\Http\Resources\TsrResource;
use App\Http\Resources\SampleResource;
use App\Services\DropdownService;
use App\Http\Requests\UserRequest;
use App\Traits\HandlesTransaction;

class WelcomeController extends Controller
{
    use HandlesTransaction;

    public function __construct(DropdownService $dropdown){
        $this->dropdown = $dropdown;
    }

    public function landing(){
        return inertia('Auth/Login',[
            'dropdowns' => [
                'laboratories' => $this->dropdown->laboratories(),
                'types' => $this->dropdown->laboratory_all(),
                'roles' => $this->dropdown->roles(),
            ]
        ]);
    }

    public function index(){
        if(\Auth::check()){
            return inertia('Modules/Dashboard/Index',[
                'laboratories' => $this->dropdown->laboratory_types(),
            ]);
        }else{
            return inertia('Auth/Login');
        }
    }

    public function installation(){
        $laboratory_id = \Auth::user()->userrole->laboratory_id;
        $member = Laboratory::with('member')->where('id',$laboratory_id)->first();
        $laboratories = ListDropdown::where('classification','Laboratory')->get();
        if(\Auth::user()->is_active){
            return inertia('Auth/Installation',[
                'member' => $member,
                'laboratories'=> $this->dropdown->laboratory_all(),
            ]);
        }
    }

    public function install(Request $request){
        $data = Configuration::create(array_merge($request->all(),['laboratories' => json_encode($request->laboratories)]));
        if($data){
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    public function activation(){
        if(\Auth::user()->is_active){
            return redirect()->intended(route('dashboard', absolute: false));
        }else{
            return inertia('Auth/Activation');
        }
    }

    public function activate(Request $request){
        $id = \Auth::user()->id;
        $data = User::findOrFail($id);
        $data->is_active = 1;
        if($data->save()){
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    public function verification($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $tsr = Tsr::query()->where('id',$id)
        ->with('received:id','received.profile:id,firstname,lastname,user_id')
        ->with('laboratory:id,name','purpose:id,name','status:id,name,color,others')
        ->with('customer:id,name_id,email,name,contact_no,is_main','customer.customer_name:id,name,has_branches','customer.address:address,addressable_id,region_code,province_code,municipality_code,barangay_code','customer.address.region:code,name,region','customer.address.province:code,name','customer.address.municipality:code,name','customer.address.barangay:code,name')
        ->with('conforme:id,name,contact_no')
        ->with('payment:tsr_id,id,total,subtotal,discount,or_number,is_paid,paid_at,status_id,discount_id,collection_id,payment_id','payment.status:id,name,color,others','payment.collection:id,name','payment.type:id,name','payment.discounted:id,name,value')
        ->first();

        $samples = TsrSample::query()->where('tsr_id',$id)
            ->with('analyses.status','analyses.testservice.method.method','analyses.testservice.testname','analyses.sample')
            ->orderBy('created_at','ASC')
            ->get();

        $analyses = TsrAnalysis::query()->with('testservice.method.method','testservice.testname','sample')
        ->whereHas('sample',function ($query) use ($id) {
            $query->whereHas('tsr',function ($query) use ($id) {
                $query->where('id',$id);
            });
        })
        ->orderBy('created_at','ASC')
        ->get();

        $groupedData = [];
        foreach ($analyses as $row) {
            $sampleCode = $row['sample']['code'];
            $sampleName = $row['sample']['name'];
            $testName = $row['testservice']['testname']['name'];
            $testMethod = $row['testservice']['method']['method']['name'];
            
            $key = $sampleCode . "_" . $testName . "_" . $testMethod;
            
            if (!isset($groupedData[$key])) {
                $groupedData[$key] = [
                    "samplecode" => $sampleCode,
                    "samplename" => $sampleName,
                    "testname" => $testName,
                    "method" => $testMethod,
                    "count" => 0,
                    "fee" => $row['fee']
                ];
            }
            $groupedData[$key]["count"] += 1;
        }
        $analyses = array_values($groupedData);

        return inertia('Modules/Public/Verification',[
            'tsr' => new TsrResource($tsr),
            'samples' => SampleResource::collection($samples),
            'analyses' => $analyses
        ]);
    }

    public function register(UserRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            $user = User::create(array_merge($request->all(), ['password' => bcrypt('123456789'),'is_new' => 1, 'role' => 'Staff','avatar' =>'avatar.jpg']));
            $user->profile()->create($request->all());
            $user->userrole()->create($request->all());

            \Auth::login($user);
        });

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function new(){
        return inertia('Auth/New');
    }
}
