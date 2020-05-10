<?php

namespace App\Http\Requests;

use App\Meal;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMealRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('meal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:meals,id',
        ];
    }
}
