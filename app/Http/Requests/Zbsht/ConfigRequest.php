<?php

namespace App\Http\Requests\Zbsht;


class ConfigRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('config');
        switch ($this->method()){
            case 'POST':
                return [
                    'name'  =>  'required|unique:configs',
                    'inc_type'  =>  'required',
                ];
                break;
            case 'PATCH':
                return [
                    'name'  =>  'required|unique:configs,name,'.$id->id,
                    'inc_type'  =>  'required'
                ];
                break;
        }
        return [
            //
        ];
    }
}
