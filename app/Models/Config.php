<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{

    use Traits\UserMemberList;

    protected $fillable = ['name','value','inc_type','describe'];


    //
}
