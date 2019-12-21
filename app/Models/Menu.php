<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    protected $fillable = ['name','pid','url','level'];

    public function child(){

        return $this->hasMany(get_class($this) , 'pid' , 'id');
    }

    public function allChild(){

        return $this->child()->with('allChild');
    }
    //
}
