<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('party_id')->comment('Relation with Party table primary key');
            $table->string('w_no')->nullable();
            $table->string('truck_no')->nullable();
            $table->integer('product_id')->comment('Relation with product table primary key');
            $table->integer('bag');
            $table->float('bag_weight', 10,2)->default(0)->comment('Bag * 0.150');
            $table->float('send_weight', 10,2)->default(0);
            $table->float('receive_weight', 10,2)->default(0);
            $table->float('final_weight', 10,2)->default(0)->comment('Receive Weight - Bag Weight');
            $table->float('labour_value')->default(0);
            $table->float('labour_bill')->default(0)->comment('Final Weight / 1000 * Labour Value');
            $table->float('rate')->default(0);
            $table->tinyInteger('truck_fare_operation')->comment('1: Addition; 2: Subtraction')->default(1);
            $table->float('truck_fare', 10,2)->default(0);
            $table->float('amount', 10,2)->default(0)->comment('Final Weight * Rate - Labour Bill [truck_fare_operation] Truck Fare)');
            $table->text('note')->nullable();
            $table->tinyInteger('type')->comment('1: Purchase; 2: Sale');
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
        Schema::dropIfExists('operations');
    }
}
