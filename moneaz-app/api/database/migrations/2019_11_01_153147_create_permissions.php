<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->bigInteger('budget_id')->unsigned();
            $table->foreign('budget_id')
                ->references('id')->on('budgets')
                ->onDelete('cascade');

            $table->primary(['user_id', 'budget_id']);
            $table->index(['user_id', 'budget_id']);

            $table->enum('perm', array('edit', 'view', 'none'));

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
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign('user_id');
            $table->dropForeign('budget_id');
        });
        Schema::dropIfExists('permissions');
    }
}
