<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
  protected $fillable = [ 'transaction_id', 'user_id', 'business_id', 'plaid_transaction_id', 'account_id', 'amount', 'iso_currency_code', 'unofficial_currency_code', 'business_address', 'business_city', 'business_state', 'business_zip', 'business_name', 'pending', 'account_owner', 'transaction_type' ];

  public $timestamps = false;

  // //protected $timestamps = true;

  protected $primaryKey = 'transaction_id';
}
