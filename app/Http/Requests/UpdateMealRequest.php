<?php

namespace App\Http\Requests;

use App\Meal;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateMealRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('meal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'        => [
                'required'],
            'category_id' => [
                'required',
                'integer'],
            'price'       => [
                'required'],
            'position'    => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647'],
        ];
    }
}
