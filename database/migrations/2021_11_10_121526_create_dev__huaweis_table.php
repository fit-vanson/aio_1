<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevHuaweisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngocphandang_dev_huawei', function (Blueprint $table) {
            $table->increments('id');
            $table->string('huawei_ga_name')->nullable();
            $table->string('huawei_dev_name')->nullable();
            $table->string('huawei_store_name')->nullable();
            $table->string('huawei_email')->nullable();
            $table->string('huawei_pass')->nullable();
            $table->integer('huawei_status')->nullable();
            $table->string('huawei_note')->nullable();
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
        Schema::dropIfExists('dev__huaweis');
    }
}
