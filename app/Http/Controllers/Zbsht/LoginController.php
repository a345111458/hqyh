<?php

namespace App\Http\Controllers\Zbsht;


use App\Http\Requests\Zbsht\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class LoginController extends Controller
{
    public function __construct(){

        $this->middleware('guest',['only'=>['index']]);
    }

    // 登陆页面
    public function index(){


        return view('zbsht.login.index');
    }

    // 验证登陆
    public function land(LoginRequest $request , User $user){
        if ($request->method('ajax')){

            filter_var($request->email , FILTER_VALIDATE_EMAIL) ?
                $data['email'] = $request->email :
                $data['name'] = $request->email;
            $data['password'] = $request->password;

            if (Auth::attempt($data , $request->remember)){
                return response()->json(['status'=>1,'msg'=>'登陆成功！']);
            }else{
                return response()->json(['status'=>0,'msg'=>'登陆失败！']);
            }
            return ;
        }
    }

    // 退出登陆
    public function logout(){
        Auth::logout();

        return redirect()->route('login.index');
    }


    //
}
