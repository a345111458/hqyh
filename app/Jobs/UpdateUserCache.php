<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Cache;
use App\Models\User;



class UpdateUserCache implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arr)
    {
        $this->user = $arr;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = Cache::get('hqyh_active_users') ?? [];
        $nweUser = User::find($this->user['id']);

        $newArr = array_filter($users , function($user) use ($nweUser){
            foreach ($user as $k=>$v){
                if ($v == $nweUser->id){
                    return false;
                }
                return true;
            }
        });

        if (count($newArr) > 0){
            return Cache::put('hqyh_active_users',array_merge($newArr , [$nweUser->toArray()]));
        }
    }
}
