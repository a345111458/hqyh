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

    // 返佣提现金额设置
    const PRICE_NAME_ONT = 'nameOne';
    const PRICE_NAME_TWO = 'nameTwo';
    const PRICE_NAME_THREE = 'nameThree';
    const PRICE_NAME_FOUR = 'nameFour';

    public static $cashStatusMap = [
        self::PRICE_NAME_ONT    =>      '1000',
        self::PRICE_NAME_TWO    =>      '100',
        self::PRICE_NAME_THREE  =>      '10',
        self::PRICE_NAME_FOUR   =>      '1',
    ];

    // public $timestamps = false;

    // protected $primaryKey = 'user_id';


    public function cash(){

        return $this->belongsTo(Cash::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
    protected $fillable = ['name','email','phone','password','to_examine','pid'];
    protected $appends = ['team_zon','pid_name','team_ztui','price_one','price_two','price_three','price_four','is_to_examine'];


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
        $pid = $this->attributes['pid'];
        $res = $this->where('id',$pid)->get()->pluck('name');
        return $res ?? '无';
    }


    /**
     * 设置一个字段 ，给来存放自身直推会员
     * @return [type] [description]
     */
    public function getTeamZtuiAttribute(){

        return $this->getTeamZtui($this->attributes['id'])->count();
    }

    /**
     * 设置一个字段 ，给来存放自身下所有会员
     * @return [type] [description]
     */
    public function getTeamZonAttribute(){

        return $this->getTeamZon($this->attributes['id'] , true , true);
    }

    // 设置一个字段 ，给来存放自身 直推奖金
    public function getPriceOneAttribute(){

        //return $this->PriceTwo();
    }

    // 设置一个字段 ，给来存放自身 团队奖金
    public function getPriceTwoAttribute(){

    }

    // 设置一个字段 ，给来存放自身 子团队奖金
    public function getPriceThreeAttribute(){

    }

    // 设置一个字段 ，给来存放自身 子子团队奖金
    public function getPriceFourAttribute(){

    }

    // 用访问器创建一个 没有的字段
    public function getIsToExamineAttribute(){

        //return $this->attributes['to_examine'];
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

        // 更新数据库之后进行操作 // 用户数据修改，更新缓存
        static::updated(function($models){
            $userId = $models->toArray();
            // 队列 更新缓存 ，目前不用，高并发可用
            //dispatch(new UpdateUserCache($userId));

            // 这里是时时写入缓存，高并发会有问题   #上面是队列写入缓存
            parent::isArrayFilterUser($models);
            // 这里是时时写入缓存 END
        });

        // 插入/保存 数据库后，添加一条缓存
        static::created(function($model){
            // 这里是时时写入缓存   #上面是队列写入缓存
            parent::isArrayFilterUser($model);
            // 这里是时时写入缓存 END
        });

        // 入库前 修改数据
        static::saving(function($model){

        });
    }







    //
}
