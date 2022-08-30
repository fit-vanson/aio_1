<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeystoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ngocphandang_keystores', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name_keystore')->nullable();
            $table->text('pass_keystore')->nullable();
            $table->text('aliases_keystore')->nullable();
            $table->text('SHA_256_keystore')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('ngocphandang_keystores');
    }
}
