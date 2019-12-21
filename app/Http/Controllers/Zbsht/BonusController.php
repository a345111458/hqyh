<?php

namespace App\Http\Controllers\Zbsht;

use App\Http\Requests\Zbsht\BonusRequest;
use App\Models\Bonus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;


class BonusController extends Controller
{
    // 显示页面
    public function index(){

        return view('zbsht.bonus.index');
    }

    // 显示页面 返回数据
    public function userIndex(BonusRequest $request , Bonus $bonus){


        return $bonus->getMemberList($request , $bonus , null , 'asc');
    }

    // 创建数据
    public function create(){

        return view('zbsht.bonus.edit');
    }

    // 提交创建数据
    public function store(BonusRequest $request , Bonus $bonus){
        $bonus->fill($request->all());

        if ($bonus->save()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.create_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.create_true')]);
        }
    }

    // 编辑页面
    public function edit(BonusRequest $request , Bonus $bonus){

        return view('zbsht.bonus.edit',['bonus'=>$bonus]);
    }

    // 提交编辑页面数据
    public function update(BonusRequest $request , Bonus $bonus){
        $bonus->fill($request->all());

        if ($bonus->save()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.edit_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.edit_false')]);
        }
    }

    // 删除数据
    public function destroy(Bonus $bonus){
        if ($bonus->delete()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.del_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.del_false')]);
        }
    }
}
