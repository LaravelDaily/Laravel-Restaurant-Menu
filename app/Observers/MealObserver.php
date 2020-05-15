<?php

namespace App\Observers;

use App\Meal;

class MealObserver
{
    /**
     * Handle the meal "creating" event.
     *
     * @param  \App\Meal  $meal
     * @return void
     */
    public function creating(Meal $meal)
    {
        if (is_null($meal->position)) {
            $meal->position = Meal::where('category_id', $meal->category_id)->max('position') + 1;
            return;
        }

        $lowerPriorityMeals = Meal::where('category_id', $meal->category_id)
            ->where('position', '>=', $meal->position)
            ->get();

        foreach ($lowerPriorityMeals as $lowerPriorityMeal) {
            $lowerPriorityMeal->position++;
            $lowerPriorityMeal->saveQuietly();
        }
    }

    /**
     * Handle the meal "updating" event.
     *
     * @param  \App\Meal  $meal
     * @return void
     */
    public function updating(Meal $meal)
    {
        if ($meal->isClean('position')) {
            return;
        }

        if (is_null($meal->position)) {
            $meal->position = Meal::where('category_id', $meal->category_id)->max('position');
        }

        if ($meal->getOriginal('position') > $meal->position) {
            $positionRange = [
                $meal->position, $meal->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $meal->getOriginal('position'), $meal->position
            ];
        }

        $lowerPriorityMeals = Meal::where('category_id', $meal->category_id)
            ->whereBetween('position', $positionRange)
            ->where('id', '!=', $meal->id)
            ->get();

        foreach ($lowerPriorityMeals as $lowerPriorityMeal) {
            if ($meal->getOriginal('position') < $meal->position) {
                $lowerPriorityMeal->position--;
            } else {
                $lowerPriorityMeal->position++;
            }
            $lowerPriorityMeal->saveQuietly();
        }
    }

    /**
     * Handle the meal "deleted" event.
     *
     * @param  \App\Meal  $meal
     * @return void
     */
    public function deleted(Meal $meal)
    {
        $lowerPriorityMeals = Meal::where('category_id', $meal->category_id)
            ->where('position', '>', $meal->position)
            ->get();

        foreach ($lowerPriorityMeals as $lowerPriorityMeal) {
            $lowerPriorityMeal->position--;
            $lowerPriorityMeal->saveQuietly();
        }
    }
}
