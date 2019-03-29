<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('account_id');
            $table->string('user');
            $table->string('item');
            $table->double('balance_available');
            $table->double('balance_current');
            $table->string('institution_type');
            $table->string('name');
            $table->string('account_identifier');
            $table->string('routing_number');
            $table->string('account_number');
            $table->string('wire_routing_number');
            $table->string('account_type');
            $table->string('account_subtype');
            $table->timestamps();
        });
        // Schema::rename('bank__acounts', 'bank_accounts');
        // Schema::dropIfExists('bank__accounts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
