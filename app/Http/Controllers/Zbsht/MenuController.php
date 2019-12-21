<?php

namespace App\Http\Controllers\Zbsht;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Requests\Zbsht\MenuRequest;



class MenuController extends Controller
{
    public function store(Menu $menu){
        $data = $menu->paginate(100);
        $menus = getson($data->items());
        foreach($menus as $k=>$v){
            if ($v['pid'] != 0){
                $group[$v['pid'].'name'][] = $v->toArray();
            }
            if ($v['pid'] == 0){
                $groupTic[$v['id'].'name'] = $v->toArray();
            }
        }


        return view('zbsht.menu.index',compact('group','groupTic'));
    }

    public function getMenuResponse(Request $request , Menu $menu){
        if ($request->method('AJAX')){
            $data = $menu->paginate(100);

            echo returnJson(getson($data->items()) , $data->count());
            return ;
        }
    }


    public function show(){
        $menus = getson(Menu::query()->get());

        return view('zbsht.menu.add',compact('menus'));
    }


    /**
     * 添加菜单
     * */
    public function addMenu(MenuRequest $request,Menu $menu){

        if ($request->method('AJAX')){
            list($pid,$level) = explode('_' , $request->pid);
            $menu->pid = $pid;
            $menu->level = $level;
            $menu->url = $request->url;
            $menu->name = $request->name;
            $menu->is_show = $request->has('is_show') ? 1 : 0;

            if ($menu->save()){
                return response()->json(['status'=>1,'msg'=>'添加成功']);
            }else{
                return response()->json(['status'=>0,'msg'=>'添加失败']);
            }
        }
    }


    /**
     * 编辑菜单
     *
     */
    public function edit(Request $request,Menu $menu){
        $menus = getson(Menu::query()->get());

        return view('zbsht.menu.edit',compact('menu','menus'));
    }

    /**
    * 确定修改
     */
    public function update(MenuRequest $request , Menu $menu){

        list($pid,$level) = explode('_' , $request->pid);
        $menu->name = $request->name;

        if($menu->id ==  $pid){
            throw new AuthenticationException('自身不可为自身父类');
            abort(422,'自身不可为自身父类');
        }
        $menu->pid = $pid;

        $menu->url = $request->url;
        $menu->is_show = $request->has('is_show') ? 1 : 0;

        if ($menu->update()){
            return response()->json(['status'=>1,'msg'=>'修改成功']);
        }else{
            return response()->json(['status'=>0,'msg'=>'修改失败']);
        }
    }

    //
}
