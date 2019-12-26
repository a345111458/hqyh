<?php

namespace App\Http\Controllers\Zbsht;

use Cache;
use App\Http\Requests\Zbsht\MemberRequest;
use App\Http\Requests\Zbsht\UserRequest;
use App\Models\User;
use App\Models\Cash;
use Braintree\Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\UserResource;
use Illuminate\Support\Arr;


class MemberController extends Controller
{
    // 表格
    public function index(Request $request , User $user){

//        Cache::put('UserHandleData' , $user->all());
        $user = Cache::get('hqyh_active_users');



//        dd($user);




        //dd(Cache::get('UserHandleData'));

        return view('zbsht.member.index');
    }


    /**layui 返回表格数据**/
    public function userIndex(MemberRequest $request , User $user){
        $pages = pageLimit($request);
        $hqyh_active_users = Cache::get('hqyh_active_users');
        $UserNumInfo = Cache::get('UserNumInfo');

        // 这里用了缓存 速度快了N倍
        //if (Cache::has('hqyh_active_users') && $UserNumInfo == count($hqyh_active_users)){
            //$arr = array_values(arraySort($hqyh_active_users, 'id','desc'));
            //$newArr = array_slice($arr , $pages['page'] , $pages['limit']); // 数组里取多少条数据

            //return returnJson($newArr , count($arr));
        //}else{
            $response = $user->getMemberList($request , $user);

            return returnJson($response['arr'] , $response['count']);
        //}
    }

    /*创建用户页面*/
    public function create(){

        return view('zbsht.member.edit');
    }

    // 提交创建用户
    public function store(UserRequest $request,User $user){
        if ($request->isMethod('POST')){
            if($res = $user->addUser($request , $user)){
                return responseJson(['status'=>1,'msg'=>Lang::get('function.create_true')]);
            }else{
                return responseJson(['status'=>0,'msg'=>Lang::get('function.create_false')]);
            }
        }
    }

    // 修改页面
    public function edit(User $user){

        return view('zbsht.member.edit' , ['user'=>$user]);
    }

    // 提交 修改
    public function update(UserRequest $request , User $user){
        $this->authorize('update', $user);

        $user->fill($request->all());
        if (!empty($user->password)){
            $user->password = bcrypt($user->password);
        }
        if ($user->save()){
            return responseJson(['status'=>1,'msg'=>'修改成功！']);
        }else{
            return response()->json(['status'=>0,'msg'=>'修改失败！']);
        }

    }

    // 删除
    public function destroy(User $user){
        $this->authorize('destroy',$user);
        if ($user->delete()){
            return responseJson(['status'=>1,'msg'=>'删除成功！']);
        }else{
            return response()->json(['status'=>0,'msg'=>'删除失败！']);
        }
    }

    // 未审核会员
    public function isNotExamile(Request $request,User $user){

        if($request->isMethod('POST')){
            $response = $user->getMemberList($request , $user);
            return returnJson($response['arr'] , $response['count']);
        }
        return view('zbsht.member.notexamine' , ['param'=>$request->param]);
    }


    //
}
