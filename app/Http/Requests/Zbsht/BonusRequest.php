<?php

namespace App\Http\Requests\Zbsht;

class BonusRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()){
            case 'GET':
                return [];
                break;
            case 'POST':
                return [
                    'price'  =>  'required|regex:/^[1-9-.]+$/',
                ];
                break;
            case 'PATCH':
                return [
                    'price'  =>  'required|regex:/^[1-9-.]+$/',
                ];
                break;
        }
    }

    public function messages(){
        return [
            'price.required' =>  '奖金 不可为空',
            'price.regex'    =>  '奖金只能为数字',

        ];
    }
}
