<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_id',
        'bussiness_id',
        'industry_id',
        'classification_id',
        'laboratory_id',
        'is_active',
        'is_main',
        'is_synced'
    ];
    
    protected $encryptable = ['contact_no','email'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function customer_name()
    {
        return $this->belongsTo('App\Models\CustomerName', 'name_id', 'id');
    }

    public function tsrs()
    {
        return $this->hasMany('App\Models\Tsr', 'customer_id');
    }

    public function conformes()
    {
        return $this->hasMany('App\Models\CustomerConforme', 'customer_id');
    }

    public function contact()
    {
        return $this->hasOne('App\Models\CustomerContact', 'customer_id');
    }

    public function wallet()
    {
        return $this->hasOne('App\Models\Wallet', 'customer_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'type_id', 'id');
    }

    public function bussiness()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'bussiness_id', 'id');
    }

    public function classification()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'classification_id', 'id');
    }

    public function industry()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'industry_id', 'id');
    }

    public function address()
    {
        return $this->morphOne('App\Models\Address', 'addressable');
    }

    public function setContactNoAttribute($value)
    {
        $this->attributes['contact_no'] = Crypt::encryptString($value);
    }

    public function getContactNoAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
