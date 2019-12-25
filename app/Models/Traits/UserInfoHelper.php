<?php
namespace App\Models\Traits;

use App\Models\User;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;
use App\Jobs\UserInfo;


trait UserInfoHelper{

    // 缓存相关配置
    protected $cache_key = 'hqyh_active_users';
    protected $cache_expire_in_seconds = 65 * 60;
    protected $cache_key_offset = "cache_key_offset";

    // 用于存放临时用户数据
    protected $users = [];

    // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
    // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
    public function getUser(){

        return Cache::remember($this->cache_key , $this->cache_expire_in_seconds , function(){

            return ;
        });
    }


    public function calculateUsers(){

        Cache::has($this->cache_key_offset) ?:Cache::put($this->cache_key_offset , 0 , $this->cache_expire_in_seconds);
        $user = User::Query()->offset(Cache::get($this->cache_key_offset))->limit(1000)->get();

        if($user->count() > 0){
            return dispatch(new Userinfo($user));
        }
        return Cache::forget($this->cache_key_offset);
    }








}



















?>