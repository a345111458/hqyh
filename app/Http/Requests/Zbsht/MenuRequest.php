<?php

namespace App\Http\Requests\Zbsht;


class MenuRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('menu');
        switch ($this->method()){
            case 'POST':
                return [
                    'name'  =>  'required|unique:menus,name|between:1,10',
                    'url'   =>  'regex:/^[A-Za-z-.]+$/',
                ];
                break;
            case 'PATCH':
                $data = [
                    'name'  =>  'required|between:1,10|unique:menus,name,'.$id->id,
                    'url'   =>  'regex:/^[A-Za-z-.]+$/',
                ];
                return $data;
                break;
        }

    }


    public function messages(){
        return [
            'name.required' =>  '不可以为空',
            'name.unique'   =>  '菜单已存在',
            'name.between'   =>  '菜单名称过长:1-10位',
            'url.regex' =>  'url 格式无效',
        ];
    }
}
