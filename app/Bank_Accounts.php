<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank_Accounts extends Model
{
  protected $fillable = [ 'id', 'user_id', 'account_id', 'account_item', 'balance_available', 'balance_current', 'institution_type', 'account_name', 'account_identifier', 'routing_number', 'account_number', 'account_type', 'account_subtype'];
  protected $table = 'bank_accounts';
}
