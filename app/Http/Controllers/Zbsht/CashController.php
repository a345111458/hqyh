<?php

namespace App\Http\Controllers\Zbsht;

use Illuminate\Http\Request;
use App\Http\Requests\Zbsht\CashRequest;
use App\Models\User;
use Carbon\Carbon;
use Cache;


class CashController extends Controller
{

    // 显示页面
    public function index(CashRequest $request , User $user){

//        $users = $user->all();
//        Cache::put('users' , $users);
//            $cache = Cache::get('users');
//        dd($cache->firstWhere('id',1)->team_zon);

        return view('zbsht.cash.index');
    }

    // 显示页面 返回数据
    public function userIndex(CashRequest $request , User $user){

        $response = $user->getMemberList($request , $user);
        foreach ($response['arr'] as $k=>&$v){
            $v['priceOne'] = $user->PriceTwo($v['id']);
        }
        return returnJson($response['arr'] , $response['count']);
    }

    // 创建数据
    public function create(){

        return view('zbsht.cash.edit');
    }

    // 提交创建数据
    public function store(CashRequest $request , User $user){
        $user->fill($request->all());

        if ($user->save()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.create_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.create_true')]);
        }
    }

    // 编辑页面
    public function edit(CashRequest $request , User $user){

        return view('zbsht.cash.edit',['bonus'=>$user]);
    }

    // 提交编辑页面数据
    public function update(CashRequest $request , User $user){
        $user->fill($request->all());

        if ($user->save()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.edit_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.edit_false')]);
        }
    }

    // 删除数据
    public function destroy(User $user){
        if ($user->delete()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.del_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.del_false')]);
        }
    }
    //
}
