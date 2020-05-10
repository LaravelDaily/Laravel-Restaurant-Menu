<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTable extends Migration
{
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->longText('description')->nullable();
            $table->integer('position')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
