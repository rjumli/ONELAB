<?php

namespace App\Http\Controllers\User\Utility;

use App\Models\User;
use App\Models\UserProfile;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HandlesTransaction;
use App\Http\Requests\UserRequest;


class UserController extends Controller
{
    use HandlesTransaction;

    public function index(Request $request){
        $options = $request->option;
        switch($options){
            case 'lists':
                return $this->lists($request);
            break;
            case 'token':
                return $this->token($request);
            break;
            default : 
            return inertia('Modules/Utility/Users/Index');
        }
    }

    public function store(UserRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            $user = User::create(array_merge($request->all(), ['password' => bcrypt('123456789'), 'role' => 'Staff','avatar' =>'avatar.jpg']));
            $user->profile()->create($request->all());
            $user->userrole()->create($request->all());
            return [
                'data' => $user,
                'message' => 'User creation was successful!', 
                'info' => "You've successfully created an account for the user."
            ];
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function update(Request $request){
        $result = $this->handleTransaction(function () use ($request) {
            $user = User::findOrFail($request->id);
            $user->update($request->all());
            // $profile = UserProfile::where('user_id',$request->id); //separate for logging
            // $profile->update($request->except(['email','type','is_active']));
            $updatedUser = User::with('profile')->findOrFail($request->id);
            return [
                'data' => new UserResource($updatedUser),
                'message' => 'User update was successful!', 
                'info' => "You've successfully updated the selected user."
            ];
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    protected function lists($request){
        $data = UserResource::collection(
            User::query()
            ->with('profile','userrole.type','userrole.laboratory')
            ->when($request->keyword, function ($query, $keyword) {
                $query->whereHas('profile',function ($query) use ($keyword) {
                    $query->where(\DB::raw('concat(firstname," ",lastname)'), 'LIKE', "%{$keyword}%")
                    ->orWhere(\DB::raw('concat(lastname," ",firstname)'), 'LIKE', "%{$keyword}%");
                })->orWhere(function ($query) use ($keyword) {
                    $query->where('username', 'LIKE', "%{$keyword}%")->whereNotIn('role',['Scholar','Administrator']);
                });
            })
            ->when($request->laboratory, function ($query, $laboratory) {
                $query->whereHas('userrole',function ($query) use ($laboratory) {
                    $query->where('laboratory_id',$laboratory);
                });
            })
            ->when($request->type, function ($query, $type) {
                $query->whereHas('userrole',function ($query) use ($type) {
                    $query->where('laboratory_type',$type);
                });
            })
            ->where('role','Staff')
            ->paginate($request->count)
        );
        return $data;
    }

    public function token($request){
        $user = User::findOrFail($request->id);
        $user->tokens()->delete();
        $token = $user->createToken('kradworkz')->plainTextToken;
        $id = $user->profile->agency->id;
        $url = $request->url;
        $data = $id.' '.$url.' '.$token;
        return $this->simpleEncrypt($data);
    }

    public function simpleEncrypt($data) {
        $key = "KradWorkZ";
        $result = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $result .= $data[$i] ^ $key[$i % strlen($key)];
        }
        return base64_encode($result);
    }
}
