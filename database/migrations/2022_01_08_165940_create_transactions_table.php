<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('account_id')->default(0)->comment('Relation with account table primary key');
            $table->tinyInteger('type')->default(0)->comment("1: Income; 2: Expense; 3: Transfer; 0: Null");
            $table->float('amount', 10,2);
            $table->integer('party_id')->comment('Relation with party table primary key. 0 for null')->default(0);
            $table->integer('operation_id')->comment('Relation with operation table primary key. 0 for null')->default(0);
            $table->text('description')->nullable();
            $table->float('debit', 10,2)->default(0);
            $table->float('credit', 10,2)->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
