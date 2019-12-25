<?php
namespace App\Models\Traits;
use App\Models\User;
use Cache;
use Carbon\Carbon;



trait UserMemberList{


    // 获取用户会员数据
    public function getMemberList($request , $user , $order = 'desc' , array $with = []){

        // 返回 layui 分页所需格式
        $pages = pageLimit($request);
        $users = $user->query()->orderBy('id', $order);

        // 判断有没有传时间过来，如果没有生成一个
        $this->getRequestTime($request , $users);

        if (!empty($with)){
            $users->with($with);
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


    public function forPriceArr($data , $arr , $request){
        //dd($data->count(),$arr , $request->all());
        foreach ($data as $k=>&$v){
            $v['priceOne'] = $this->PriceTwo($v['id'] , $arr , $request);
        }
    }

    /**
     * 获取提成金额
     * @param  [type]  $id [传入ID]
     * @param  [type]  $arr  [传入数据数组 => ['static'=>true , 'level'=>1]]
     * @param  boolean $clean [是否清除静态变量,默认不清除]
     * @return [type]         [description]
     */
    public function PriceTwo($id , $static = true , $level = 1){
//    public function PriceTwo($id , $price = 0 , $level = 1 , $static = false){
        static $newArr = ['priceOne'=>'0','priceTwo'=>'0','priceThree'=>'0','priceFour'=>'0'];
        if ($static){ // 判断是否清除 静态变量里的数据，true 清除
            $newArr['priceOne'] = 0;
            $newArr['priceTwo'] = 0;
            $newArr['priceThree'] = 0;
            $newArr['priceFour'] = 0;
        }

        $response = $this->where('pid',$id)->get();

        if ($response->count() > 0){
            foreach($response as $v){
                if ($v->is_to_examine == 1){
                    switch ($level){
                        case '1':
                            $newArr['priceOne'] += self::$cashStatusMap['nameOne'];
                            break;
                        case '2':
                            $newArr['priceTwo'] += self::$cashStatusMap['nameTwo'];
                            break;
                        case '3':
                            $newArr['priceThree'] += self::$cashStatusMap['nameThree'];
                            break;
                        case '4':
                            $newArr['priceFour'] += self::$cashStatusMap['nameFour'];
                            break;
                    }
                    if ($level <= 4 ){
                        $this->PriceTwo($v->id , false , $level+1);
                    }
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
     * 判断有没有传时间过来,如果没有生成一个
     * @param  [type]  $request [传过来的数据]
     * @param  boolean $users [传过来的 模型]
     * @return [return]         [返回查询 条件]
     */
    public function getRequestTime($request , $users){
        if (!$request->filled('time_out') && !$request->filled('time_end')){
            $request->time_out = Carbon::now()->subDays(0)->toDateString(); // 当前时间 减少一天，
            $request->time_end = Carbon::now()->toDateString(); // 当前时间
        }

        if (!empty($request->time_out) && !empty($request->time_end)){
            return $users->whereDate('created_at','>=', $request->time_out)
                ->whereDate('created_at','<=', $request->time_end);
        }else if(!empty($request->time_out)){
            return $users->whereDate('created_at','>=', $request->time_out);
        }else if(!empty($request->time_end)){
            return $users->whereDate('created_at','>=', $request->time_end);
        }
    }









}













?>