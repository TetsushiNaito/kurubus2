<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PolenameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ( $this->path() == 'confirm' ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'depr_pole' => 'required|polename',
            'dest_pole' => 'required|polename',
        ];
    }
    public function messages() {
        return [
            'depr_pole.required' => '出発地のバス停を入力してください',
            'dest_pole.required' => '目的地のバス停を入力してください',
            'depr_pole.polename' => '正しいバス停名を入力してください',
            'dest_pole.polename' => '正しいバス停名を入力してください',
        ];
    }

}
