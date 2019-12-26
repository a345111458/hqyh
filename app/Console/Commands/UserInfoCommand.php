<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;



class UserInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:hqyh-userinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'user 生成缓存';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('开始...');
//        $userId = $this->ask('输入密码！');
//        if ($userId == '369258147'){
            $user = new User();
            $user->calculateUsers();
//        }
    }
}
