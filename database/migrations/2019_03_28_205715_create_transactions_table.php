<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if(!Schema::hasTable('transactions')){
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('business_id')->nullable(); //may be deleted once business identifier is sorted
            $table->string('plaid_transaction_id')->nullable();
            $table->string('account_id')->nullable();
            $table->double('amount')->nullable();
            $table->string('business_name')->nullable();
            $table->string('is_pending')->nullable();
            $table->string('account_owner')->nullable();
            $table->string('section')->nullable();
            $table->string('category')->nullable();
            $table->string('attribute')->nullable();
            $table->string('plaid_category_id')->nullable();
            $table->string('transaction_type')->nullable();
            $table->double('cashback_amount')->nullable();

            $table->string('date')->nullable();
        });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
