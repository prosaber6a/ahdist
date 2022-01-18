<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank');
            $table->string('branch');
            $table->string('name');
            $table->bigInteger('acc_no');
            $table->float('initial_balance', 10,2)->default(0);
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Active; 0: Inactive');
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
        Schema::dropIfExists('accounts');
    }
}
