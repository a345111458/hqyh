<?php

namespace App\Http\Controllers\Zbsht;

use App\Http\Requests\Zbsht\ConfigRequest;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;


class ConfigController extends Controller
{
    // 显示页面
    public function index(){

        return view('zbsht.config.index');
    }

    // 显示页面 返回数据
    public function userIndex(ConfigRequest $request , Config $config){

        $response = $config->getMemberList($request , $config);
        return returnJson($response['arr'] , $response['count']);
    }

    // 创建数据
    public function create(){

        return view('zbsht.config.edit');
    }

    // 提交创建数据
    public function store(ConfigRequest $request , Config $config){
        $config->fill($request->all());

        if ($config->save()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.create_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.create_true')]);
        }
    }

    // 编辑页面
    public function edit(ConfigRequest $request , Config $config){

        return view('zbsht.config.edit',['config'=>$config]);
    }

    // 提交编辑页面数据
    public function update(ConfigRequest $request , Config $config){
        $config->fill($request->all());

        if ($config->save()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.edit_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.edit_false')]);
        }
    }

    public function destroy(Config $config){
        if ($config->delete()){
            return responseJson(['status'=>1,'msg'=>Lang::get('function.del_true')]);
        }else{
            return responseJson(['status'=>0,'msg'=>Lang::get('function.del_false')]);
        }
    }
}
