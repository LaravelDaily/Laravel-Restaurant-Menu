@extends('layouts.admin')
@section('content')
@can('meal_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.meals.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.meal.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.meal.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Meal">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.meal.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.meal.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.meal.fields.category') }}
                        </th>
                        <th>
                            {{ trans('cruds.meal.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.meal.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.meal.fields.photo') }}
                        </th>
                        <th>
                            {{ trans('cruds.meal.fields.position') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                @foreach($categories as $category)
                    <tbody data-id="{{ $category->id }}" class="sortable">
                        <tr>
                            <td colspan="8" style="background-color:#ddd;">{{ $category->name }}</td>
                        </tr>
                        @foreach($category->meals as $meal)
                            <tr data-id="{{ $meal->id }}" class="meal">
                                <td>
                                    {{ $meal->id ?? '' }}
                                </td>
                                <td>
                                    {{ $meal->name ?? '' }}
                                </td>
                                <td class="category-name">
                                    {{ $meal->category->name ?? '' }}
                                </td>
                                <td>
                                    {{ $meal->price ?? '' }}
                                </td>
                                <td>
                                    {{ $meal->description ?? '' }}
                                </td>
                                <td>
                                    @if($meal->photo)
                                        <a href="{{ $meal->photo->getUrl() }}" target="_blank">
                                            <img src="{{ $meal->photo->getUrl('thumb') }}" width="50px" height="50px">
                                        </a>
                                    @endif
                                </td>
                                <td class="position">
                                    {{ $meal->position ?? '' }}
                                </td>
                                <td>
                                    @can('meal_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.meals.show', $meal->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('meal_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.meals.edit', $meal->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('meal_delete')
                                        <form action="{{ route('admin.meals.destroy', $meal->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                        <tr class="empty-message" @if($category->meals->count()) style="display:none;"@endif>
                            <td colspan="8" class="text-center">
                                There are no meals in this category
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>



@endsection

@section('scripts')
@parent
<script>
    function sendReorderMealsRequest($category) {
        var items = $category.sortable('toArray', {attribute: 'data-id'});
        var ids = $.grep(items, (item) => item !== "");

        if ($category.find('tr.meal').length) {
            $category.find('.empty-message').hide();
        }
        $category.find('.category-name').text($category.find('tr:first td').text());

        $.post('{{ route('admin.meals.reorder') }}', {
                _token,
                ids,
                category_id: $category.data('id')
            })
            .done(function (response) {
                $category.children('tr.meal').each(function (index, meal) {
                    $(meal).children('.position').text(response.positions[$(meal).data('id')])
                });
            })
            .fail(function (response) {
                alert('Error occured while sending reorder request');
                location.reload();
            });
    }

    $(document).ready(function () {
        var $categories = $('table');
        var $meals = $('.sortable');

        $categories.sortable({
            cancel: 'thead',
            stop: () => {
                var items = $categories.sortable('toArray', {attribute: 'data-id'});
                var ids = $.grep(items, (item) => item !== "");
                $.post('{{ route('admin.categories.reorder') }}', {
                        _token,
                        ids
                    })
                    .fail(function (response) {
                        alert('Error occured while sending reorder request');
                        location.reload();
                    });
            }
        });

        $meals.sortable({
            connectWith: '.sortable',
            items: 'tr.meal',
            stop: (event, ui) => {
                sendReorderMealsRequest($(ui.item).parent());

                if ($(event.target).data('id') != $(ui.item).parent().data('id')) {
                    if ($(event.target).find('tr.meal').length) {
                        sendReorderMealsRequest($(event.target));
                    } else {
                        $(event.target).find('.empty-message').show();
                    }
                }
            }
        });
        $('table, .sortable').disableSelection();
    });
</script>
@endsection
