<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(User::class , 3)->create();


        $user = User::find(1);
        $user->name = '345111458';
        $user->email = '345111458@qq.com';
        $user->password = bcrypt('123456');
        $user->save();
    }
}
