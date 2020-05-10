<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMealRequest;
use App\Http\Requests\StoreMealRequest;
use App\Http\Requests\UpdateMealRequest;
use App\Meal;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MealsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('meal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meals = Meal::all();

        return view('admin.meals.index', compact('meals'));
    }

    public function create()
    {
        abort_if(Gate::denies('meal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.meals.create', compact('categories'));
    }

    public function store(StoreMealRequest $request)
    {
        $meal = Meal::create($request->all());

        if ($request->input('photo', false)) {
            $meal->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $meal->id]);
        }

        return redirect()->route('admin.meals.index');
    }

    public function edit(Meal $meal)
    {
        abort_if(Gate::denies('meal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $meal->load('category');

        return view('admin.meals.edit', compact('categories', 'meal'));
    }

    public function update(UpdateMealRequest $request, Meal $meal)
    {
        $meal->update($request->all());

        if ($request->input('photo', false)) {
            if (!$meal->photo || $request->input('photo') !== $meal->photo->file_name) {
                $meal->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))->toMediaCollection('photo');
            }
        } elseif ($meal->photo) {
            $meal->photo->delete();
        }

        return redirect()->route('admin.meals.index');
    }

    public function show(Meal $meal)
    {
        abort_if(Gate::denies('meal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meal->load('category');

        return view('admin.meals.show', compact('meal'));
    }

    public function destroy(Meal $meal)
    {
        abort_if(Gate::denies('meal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meal->delete();

        return back();
    }

    public function massDestroy(MassDestroyMealRequest $request)
    {
        Meal::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('meal_create') && Gate::denies('meal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Meal();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
