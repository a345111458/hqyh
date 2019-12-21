<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use Cache;

class UserInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;

    protected $cache_key = 'hqyh_active_users';
    protected $cache_expire_in_seconds = 65 * 60;
    protected $cache_key_offset = "cache_key_offset";

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->users = $user;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::has($this->cache_key) ?:Cache::put($this->cache_key , [] , $this->cache_expire_in_seconds);
        Cache::put($this->cache_key_offset, Cache::get($this->cache_key_offset) + $this->users->count() , $this->cache_expire_in_seconds);

        if ($this->users->count() > 0){
            // 查询出来的用户数据存入缓存
            Cache::put($this->cache_key , array_merge(Cache::get($this->cache_key) , $this->users->toArray()) , $this->cache_expire_in_seconds);
            
            exec('php artisan command:hqyh-userinfo');
        }
    }
}
