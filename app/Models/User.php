<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Cache;
use App\Jobs\UpdateUserCache;


class User extends Authenticatable
{
    use Traits\UserInfoHelper;
    use Traits\UserMemberList;


    // public $timestamps = false;

    // protected $primaryKey = 'user_id';


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $fillable = ['name','email','phone','password','to_examine','pid'];

    protected $guarded = ['team_ztui'];

    protected $appends = ['team_ztui','team_zon','pid_name'];



//    // 找到自己的推荐人
//    protected $appends = ['tuijren'];
//    public function getTuijrenAttribute(){
//        return $this->where('user_id',$this->first_leader)->pluck('account');
//    }
//
        // 自关联，查询自身下的会员
        public function child(){

            return $this->hasMany(get_class($this) , 'pid' , 'id');
        }

       // 通过自关联，查询自身下的所有会员
       public function allChild(){

           return $this->child()->with('allChild');
       }

    // 找到自己的推荐人
    public function getPidNameAttribute(){

        return $this->find($this->attributes['pid'])['name'] ?? '无';
    }


    /**
     * 设置一个字段 ，给来存放自身直推会员
     * @return [type] [description]
     */
    public function getTeamZtuiAttribute(){

        return $this->getTeamZtui($this->attributes['id']);
    }

    /**
     * 设置一个字段 ，给来存放自身下所有会员
     * @return [type] [description]
     */
    public function getTeamZonAttribute(){

        return $this->getTeamZon($this->attributes['id'] ?? 0 , true , true);
    }

    // 用访问器创建一个 没有的字段
    public function getIsToExamineAttribute(){

        return $this->attributes['to_examine'];
    }

    //用访问器 设置一个字段的内容
    public function getToExamineAttribute($value){
        $attribute = $value;

        switch ($value){
            case '0':
                $attribute = '<b style="color:orangered;">未审核</b>';
                break;
            case '1':
                $attribute = '<b style="color:deepskyblue;">已审核</b>';
                break;
            case '2':
                $attribute = '<b style="color:navy;">冻结用户</b>';
                break;
        }
        return $this->attributes['to_examine'] = $attribute;
    }


    // 模型 观察者
    protected static function boot(){
        parent::boot();

        // 保存数据库之后进行操作 // 用户数据修改，更新缓存
        static::saved(function($models){

            $userId = $models->toArray();
            // 队列 更新缓存
            dispatch(new UpdateUserCache($userId));
        });

        // 入库前 修改数据
        static::saving(function($model){

        });
    }







    //
}
