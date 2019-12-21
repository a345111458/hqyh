<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;


class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'菜单管理','pid'=>'0','url'=>'/'],
            ['name'=>'用户管理','pid'=>'0','url'=>'/'],
            ['name'=>'用户列表','pid'=>'2','url'=>'/'],
        ];

        foreach($data as $v){
            $menus = factory(\App\Models\Menu::class)->times(1)->make()->each(function($menu) use ($v){
                $menu->name = $v['name'];
                $menu->pid = $v['pid'];
                $menu->url = $v['url'];
            });
            Menu::insert($menus->toArray());
        }
    }
}
