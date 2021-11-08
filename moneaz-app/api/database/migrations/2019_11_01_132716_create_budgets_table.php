<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('parent')->unsigned()->nullable();
            $table->foreign('parent')
                ->references('id')->on('budgets')
                ->onDelete('cascade');
            $table->bigInteger('group_id')->unsigned();
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->onDelete('cascade');
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

        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign('parent');
            $table->dropForeign('group_id');
        });

        Schema::dropIfExists('budgets');
    }
}
