<?php
namespace App\Models\Traits;

trait UserMemberList{

    // 获取用户会员数据
    public function getMemberList($request , $user , $where = null , $order = 'desc'){

        $pages = pageLimit($request);
        $users = $user->query()->orderBy('id', $order);

        if (!is_null($where)){
            $users->where('to_examine' , $where);
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
        $count = $users->count();
        $arr = $users->offset($pages['page'])->limit($pages['limit'])->get();

        return returnJson($arr , $count);
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
        $response = $this->where('pid', $arrId)->get()->count();

        return $response;
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









}













?>