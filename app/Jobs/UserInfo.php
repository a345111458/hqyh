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

    protected $hqyh_active_users = 'hqyh_active_users';
    protected $cache_key_offset = "cache_key_offset";

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->users = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::put($this->cache_key_offset, Cache::get($this->cache_key_offset) + $this->users->count());

        if ($this->users->count() > 0){
            // 查询出来的用户数据存入缓存
            Cache::put($this->hqyh_active_users , array_merge(Cache::get($this->hqyh_active_users , []) , $this->users->toArray()));
            
            exec('php artisan command:hqyh-userinfo');
        }
    }
}
