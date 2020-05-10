@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.meal.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.meals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.id') }}
                        </th>
                        <td>
                            {{ $meal->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.name') }}
                        </th>
                        <td>
                            {{ $meal->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.category') }}
                        </th>
                        <td>
                            {{ $meal->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.price') }}
                        </th>
                        <td>
                            {{ $meal->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.description') }}
                        </th>
                        <td>
                            {{ $meal->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.photo') }}
                        </th>
                        <td>
                            @if($meal->photo)
                                <a href="{{ $meal->photo->getUrl() }}" target="_blank">
                                    <img src="{{ $meal->photo->getUrl('thumb') }}" width="50px" height="50px">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.position') }}
                        </th>
                        <td>
                            {{ $meal->position }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.meals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection