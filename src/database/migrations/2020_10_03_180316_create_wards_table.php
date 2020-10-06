<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('slug');
            $table->string('type');
            $table->string('status')->default(1);
            $table->string('area')->nullable();
            $table->string('thumbnail')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wards');
    }
}
