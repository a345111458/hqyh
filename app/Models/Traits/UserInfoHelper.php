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

    /**
     *  运行 exec('php artisan command:hqyh-userinfo');
     *  UserInfoCommand 会来调用这个方法生成用户缓存
     */
    public function calculateUsers(){

        Cache::has($this->cache_key_offset) ?:Cache::put($this->cache_key_offset , 0 , $this->cache_expire_in_seconds);
        $user = User::Query()->offset(Cache::get($this->cache_key_offset))->limit(1000)->get();

        if($user->count() > 0){
            return dispatch(new Userinfo($user)); // 执行 Userinfo.php 队列文件
        }
        return Cache::forget($this->cache_key_offset);
    }

    /**
    *  时时写入缓存 ，判断有没有重复的
     */
    public function isArrayFilterUser($models){
        // 这里是时时写入缓存   #上面是队列写入缓存
        $users = Cache::get('hqyh_active_users') ?? [];
        $newUser = $models->toArray();// 传入用户数据,数组
        $newArr = array_filter($users , function($users) use ($newUser){
            // 这里面的 $users 已从二维数组变成一维数组了
            if ($users['id'] == $newUser['id']){
                return false;
            }
            return true;
        });
        return Cache::put('hqyh_active_users',array_merge($newArr , [$newUser]));
    }









}



















?>