<?php

namespace App\Http\Requests\Zbsht;

use App\Models\User;

class UserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user');

        switch ($this->method()){
            case 'POST':
                return [
                    'email'     =>  'required_without:name|email|unique:users',
                    'name'      =>  'required_without:email|unique:users|string',
                    'password'  =>  'required|between:6,18',
                    'phone'     =>  'required|unique:users',
                    'pid'       =>  [
                        'required',
                        function($attribute , $value , $fail){
                            if (!$user = User::where('email',$value)->first()){
                                return abort('403' , '无效推荐人');
                            }
                        }
                    ]
                    //'captcha'   =>  'captcha|required'
                    //
                ];
                break;
            case 'PATCH':
                $data = [
                    'email'  =>  'required|unique:users,email,'.$id->id,
                    'name'   =>     'required',
                    'phone'     =>  'required|unique:users,phone,'.$id->id,
                    'password'  =>  ['nullable'],
                ];
                return $data;
                break;
        }
    }


    public function messages(){
        return [
            'email.email'   =>  '必须是合法邮箱',
//            'captcha.required'  =>  '验证码不可为空',
//            'captcha.captcha'   =>  '验证码错误',
        ];
    }
}
