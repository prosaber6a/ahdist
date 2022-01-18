<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('image')->nullable();
            $table->string('nid')->nullable();
            $table->string('company', 60)->nullable();
            $table->text('address')->nullable();
            $table->string('mobile', 20);
            $table->string('email', 30)->nullable();
            $table->integer('type')->comment('1: Supplier; 2: Customer');
            $table->integer('status')->comment('1: Active; 0: Inactive;')->default(1);
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
        Schema::dropIfExists('parties');
    }
}
