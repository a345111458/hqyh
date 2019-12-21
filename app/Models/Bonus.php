<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use Traits\UserMemberList;
    //
    protected $fillable = ['name','price','describe'];
}
