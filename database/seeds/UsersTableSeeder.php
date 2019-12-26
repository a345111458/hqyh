<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{

    protected $appends = [];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = factory(User::class)->times(1)->make()
            ->makeHidden(['team_ztui','team_zon','pid_name','price_one','price_two','price_three','price_four','is_to_examine'])->toArray();

        User::insert($data);

        $user = User::find(1);
        $user->name = '345111458';
        $user->email = '345111458@qq.com';
        $user->password = bcrypt('123456');
        $user->pid = 0;
        $user->save();
    }
}
