<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToMealsTable extends Migration
{
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->foreign('category_id', 'category_fk_1453554')->references('id')->on('categories');
        });
    }
}
