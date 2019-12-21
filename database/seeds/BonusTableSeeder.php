<?php

use Illuminate\Database\Seeder;
use App\Models\Bonus;


class BonusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'  =>  '一级','price'  =>  '1000','describe'  =>  '直推一人',],
            ['name'  =>  '二级','price'  =>  '100','describe'  =>  '团队推荐',],
            ['name'  =>  '三级','price'  =>  '10','describe'  =>  '子团队推荐',],
            ['name'  =>  '四级','price'  =>  '1','describe'  =>  '子子团队推荐',]
        ];

        $bonus = factory(Bonus::class)->times(4)->make()->each(function($bonus , $index) use ($data){
            $bonus->name = $data[$index]['name'];
            $bonus->price = $data[$index]['price'];
            $bonus->describe = $data[$index]['describe'];
        });

        Bonus::insert($bonus->toArray());
        //
    }
}
