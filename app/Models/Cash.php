<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{

    protected $fillable = ['user_id','divide_id','price','level'];

    public function user(){

        return $this->hasMany(User::class);
    }
    //
}
