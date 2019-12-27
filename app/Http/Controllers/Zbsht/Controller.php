<?php

namespace App\Http\Controllers\Zbsht;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;



class Controller extends BaseController
{

    protected $hqyh_active_users;

    public function __construct(){
        $this->hqyh_active_users = config('usercache.user.hqyh_active_users');
    }

    //
}
