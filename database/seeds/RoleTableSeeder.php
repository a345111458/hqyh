<?php

use Illuminate\Database\Seeder;
use App\Models\Role;


class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'zhuangzhan','describe'=>'站长'],
        ];
        $roles = factory(Role::class)->times(1)->make()->each(function($role) use ($data){
            $role->name = $data[0]['name'];
            $role->describe = $data[0]['describe'];
        });

        Role::insert($roles->toArray());
    }
}
