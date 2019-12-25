<?php

namespace App\Http\Controllers\Zbsht;

use Illuminate\Http\Request;
use App\Http\Requests\Zbsht\CashRequest;
use App\Models\User;
use Carbon\Carbon;


class CashController extends Controller
{

    // 显示页面
    public function index(CashRequest $request , User $user){

        $response = $user->whereIn('id',['1','2'])->get();

        foreach ($response as $k=>&$v){
            $v['priceOne'] = $user->PriceTwo($v['id']);
        }

        dd($response->toArray());

        return view('zbsht.cash.index');
    }

    // 显示页面 返回数据
    public function userIndex(CashRequest $request , User $user){
//        $users = $user->whereDate('created_at','>=', $request->time_end)
//            ->whereDate('created_at','<=', $request->time_end)
//            ->get();
//
//        dd($users->toArray());exit;
//        dd($request->time_out , $request->time_end);exit;
        $response = $user->getMemberList($request , $user);
        $arr = ['static'=>true , 'level'=>1];
        //dd($response['arr']->toArray());
        foreach($response['arr'] as $k=>&$v){
            $v['priceOne'] = $user->PriceTwo($response['arr'] , $arr , $request);
        }
        //$response['arr'] = $user->PriceTwo($response['arr'] , $arr , $request);
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
