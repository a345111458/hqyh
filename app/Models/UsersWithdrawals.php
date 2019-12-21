<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersWithdrawals extends Model
{

    public function user(){

        return $this->belongsTo(User::class , 'user_id' , 'user_id');
    }

    // 格式化时间戳
    public function getCreateTimeAttribute($value){

        return date('Y/m/d H:i:s' , $value);
    }
    //
}
