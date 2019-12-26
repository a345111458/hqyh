<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('divide_id')->nullable()->comment('推荐人-受益人');
            $table->decimal('price')->default(0)->comment('奖金');
            $table->integer('level')->comment('分成等级');
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
        Schema::dropIfExists('cashes');
    }
}
