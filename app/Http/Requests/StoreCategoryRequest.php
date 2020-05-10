<?php

namespace App\Http\Requests;

use App\Category;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'     => [
                'required'],
            'position' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647'],
        ];
    }
}
