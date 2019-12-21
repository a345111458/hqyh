<?php

namespace App\Http\Requests\Zbsht;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     =>  'required_without:name|string',
            'name'      =>  'required_without:email|string',
            'password'  =>  'required|between:6,18',
//            'captcha'   =>  'captcha|required'
            //
        ];
    }


    public function messages(){
        return [
            'email.email'   =>  '必须是合法邮箱',
            'captcha.required'  =>  '验证码不可为空',
//            'captcha.captcha'   =>  '验证码错误',
        ];
    }
}
