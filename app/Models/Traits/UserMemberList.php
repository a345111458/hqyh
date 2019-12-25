<?php
namespace App\Models\Traits;
use App\Models\User;
use Cache;
use Carbon\Carbon;



trait UserMemberList{


    // 获取用户会员数据
    public function getMemberList($request , $user , $order = 'desc' , array $with = []){
        // 判断有没有传时间过来，如果没有生成一个
        $this->getRequestTime($request);
        // 返回 layui 分页所需格式
        $pages = pageLimit($request);
        $users = $user->query()->orderBy('id', $order);

        if (!empty($with)){
            $users->with($with);
        }

        if (!empty($request->time_out) && !empty($request->time_end)){
            $users->whereDate('created_at','>=', $request->time_out)
                ->whereDate('created_at','<=', $request->time_end);
        }else if(!empty($request->time_out)){
            $users->whereDate('created_at','>=', $request->time_out);
        }else if(!empty($request->time_end)){
            $users->whereDate('created_at','>=', $request->time_end);
        }

        if (!is_null($request->param)){
            $users->where('to_examine' , $request->param);
        }

        if ($request->filled('key.email')){
            $users->where('email','like',$request->key['email'].'%');
        }

        if($request->filled('key.phone')){
            $users->where('phone','like',$request->key['phone'].'%');
        }else if($request->filled('key.email')){
            $users->where('email','like',$request->key['email'].'%');
        }

        //返回表格数据
        $newArr['count'] = $users->count();
        $newArr['arr'] = $users->offset($pages['page'])->limit($pages['limit'])->get();

        // 执行队列 写入缓存
        Cache::put('UserNumInfo' , $newArr['count']);

        return $newArr;
    }


    // 添加 会员 trait
    public function addUser($request , $user){
        $user->fill($request->all());
        $user->pid = $user->where('email',$request->pid)->get()->pluck('id')[0];
        $user->password = bcrypt($user->password);
        if ($user->save()){
            return $user;
        }else{
            return false;
        }
    }


    /**
     * 查询自己直推会员
     * @return [type] [description]
     */
    public function getTeamZtui($arrId){
        $response = $this->where('pid', $arrId)->get();
        return $response;
    }


    public function PriceTwo($arr , $price = 0){
        $newArr = 0;
        $level = 0;
        foreach($arr as $v){
            if ($v->is_to_examine == 1){
                $newArr += $price ;
                $level++;
                if (is_array($v->priceOne) && $level <= 2){
                    $this->PriceTwo($v->priceOne , $price);
                }
            }
        }
        return $newArr;
    }

    /**
     * 查询自己团队下有多少人
     * @param  [type]  $arrId [传入ID]
     * @param  [type]  $type  [返回数据类型]
     * @param  boolean $clean [是否清除静态变量,默认不清除]
     * @return [type]         [description]
     */
    public function getTeamZon($arrId , $type = null , $clean = false){
        static $arr = [];
        $res = $this->where('pid', $arrId)->get();

        $clean ? $arr = null : false;

        if (count($res) > 0){
            foreach ($res as $k=>$v){
                $arr[] = $v->id;
                $this->getTeamZon($v['id'] , $type);
            }
        }

        if (is_null($type)){
            return $arr;
        }else{
            return count(collect($arr));  // 好奇 为什么这个地方， 返回的数据不可以是  count($arr);
                                    // 我是想返回 这个数组 有多少条数据，可是死活不行。 只能做成一个集合 
                                    //  它返回的数据结构应该是  [1,2,3,4,5,6]
        }
    }


    /**
    * 判断有没有传时间过来，如果没有生成一个
     */
    public function getRequestTime($request){
        if (!$request->filled('time_out') && !$request->filled('time_end')){
            $request->time_out = Carbon::now()->subDays(1)->toDateString(); // 当前时间 减少一天，
            $request->time_end = Carbon::now()->toDateString(); // 当前时间
        }
        return $request;
    }









}













?>