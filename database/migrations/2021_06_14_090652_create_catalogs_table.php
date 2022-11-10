<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('cid')->unique();
            $table->enum('type', ['single', 'ep','album'])->default('single');
            $table->string('title');
            $table->string('artis_name')->nullable();
            $table->string('composer',100)->nullable();
            $table->string('produced_by',100)->nullable();
            $table->string('record_label',50)->nullable();
            $table->string('genre',30)->nullable();
            $table->string('sub_genre',30)->nullable();
            $table->year('production_year')->nullable()->default(Date("Y"));
            $table->date('first_release_date')->nullable();
            $table->date('release_date')->nullable();
            $table->string('lyric_language',30)->nullable();
            $table->string('artwork_url')->nullable();
            $table->string('lyric_url')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('created_by')->unsigned()->nullable();
            $table->string('created_by_name')->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogs');
    }
}
