<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LogHistory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('action');
            $table->string('device');
            $table->string('user_agent');
            $table->string('ip_addr');
            $table->string('email');
            $table->dateTime('login_try_date');
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
        Schema::dropIfExists('LogHistory');
    }
}
