<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required|max:255',
            'subtitle'      => 'nullable|max:255',
            'introduction'  => 'nullable|max:255',
            'body'          => 'required',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id'   => 'nullable',
            'brand_id'      => 'nullable',
            'shop_id'       => 'nullable',
            'min_quantity'  => 'nullable',
            'measure'       => 'nullable',
            'product_id'    => 'nullable',
            'prices.price'          => 'nullable',
            'prices.quantity'       => 'nullable',
            'prices.currency_id'    => 'nullable',
        ];
    }
}
