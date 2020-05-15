<?php

namespace App\Observers;

use App\Category;

class CategoryObserver
{
    /**
     * Handle the category "creating" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function creating(Category $category)
    {
        if (is_null($category->position)) {
            $category->position = Category::max('position') + 1;
            return;
        }

        $lowerPriorityCategories = Category::where('position', '>=', $category->position)
            ->get();

        foreach ($lowerPriorityCategories as $lowerPriorityCategory) {
            $lowerPriorityCategory->position++;
            $lowerPriorityCategory->saveQuietly();
        }
    }

    /**
     * Handle the category "updating" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function updating(Category $category)
    {
        if ($category->isClean('position')) {
            return;
        }

        if (is_null($category->position)) {
            $category->position = Category::max('position');
        }

        if ($category->getOriginal('position') > $category->position) {
            $positionRange = [
                $category->position, $category->getOriginal('position')
            ];
        } else {
            $positionRange = [
                $category->getOriginal('position'), $category->position
            ];
        }

        $lowerPriorityCategories = Category::where('id', '!=', $category->id)
            ->whereBetween('position', $positionRange)
            ->get();

        foreach ($lowerPriorityCategories as $lowerPriorityCategory) {
            if ($category->getOriginal('position') < $category->position) {
                $lowerPriorityCategory->position--;
            } else {
                $lowerPriorityCategory->position++;
            }
            $lowerPriorityCategory->saveQuietly();
        }
    }

    /**
     * Handle the category "deleted" event.
     *
     * @param  \App\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        $lowerPriorityCategories = Category::where('position', '>', $category->position)
            ->get();

        foreach ($lowerPriorityCategories as $lowerPriorityCategory) {
            $lowerPriorityCategory->position--;
            $lowerPriorityCategory->saveQuietly();
        }
    }
}
