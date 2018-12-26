<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    // Determine if the user is authorized to make this request
    public function authorize()
    {
        return true;
    }

    // Get the validation rules that apply to the request
    public function rules()
    {
        $order_max = config('valid.slider.order.max');

        return [
            'order' => "nullable|numeric|max:{$order_max}",
        ];
    }

    // Custom messages
    public function messages()
    {
        return [
            'order.numeric' => trans('slider.order_numeric'),
            'order.max' => trans('slider.order_max'),
        ];
    }
}
