<?php

namespace App\Http\Resources;

use Crypt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'assigned_role' => ($this->userrole) ? $this->userrole->role->name : 'n/a',
            'assigned_laboratory' => ($this->userrole) ? $this->userrole->laboratory->name : 'n/a',
            'assigned_type' => ($this->userrole) ? ($this->userrole->type) ? $this->userrole->type->name : 'n/a' : 'n/a',
            'avatar' => ($this->profile->avatar === 'avatar.jpg') ? '/images/avatars/'.$this->profile->avatar : '/storage/'.$this->profile->avatar,
            'name' => $this->profile->firstname.' '.$this->profile->lastname,
            'firstname' => $this->profile->firstname,
            'lastname' => $this->profile->lastname,
            'middlename' => $this->profile->middlename,
            'gender' => $this->profile->gender,
            'suffix' => $this->profile->suffix,
            'mobile' => $this->profile->mobile,
            'profile_id' => $this->profile->id,
            'is_active' => $this->is_active,
            'is_new' => $this->is_new,
            'two_factor_enabled' => ($this->two_factor_secret) ? true : false,
            'two_factor_confirmed' => ($this->two_factor_confirmed_at) ? true : false,
            'password_changed_at' => $this->password_changed_at,
            'password_confirmed_at' => session('auth'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
