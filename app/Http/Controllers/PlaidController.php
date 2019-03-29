<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Travoltron\Plaid\Plaid;
use App\Bank_Accounts;
use JWTFactory;
use App\User;
use JWTAuth;
use Auth;

class PlaidController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.auth');
  }

  public function addPlaid(Request $request) // adds a plaid user
  {
    $username = $request->input('bank_username'); // 'plaid_test'
    $password = $request->input('bank_password'); // 'plaid_good'
    $pin = $request->input('pin'); // set null
    $type = $request->input('type'); // 'chase'
    // $user = auth()->user();
    $user = JWTAuth::user();

    $authUser = Plaid::addAuthUser($username, $password, $pin, $type);
    User::where('id', '=', $user['id'])->update(['access_token' => $authUser['access_token']]); //stores value in db
    return $authUser;
  }

  public function enterAuth(Request $request) // enters MFA info for verification to banks, returns bank info
  {
    $user = auth()->user();
    $plaid_token = $user ['access_token'];
    $mfa = $request->input('mfa');
    $authMfa = Plaid::authMfa($mfa, $plaid_token);
    //$plaid_token = User::where('id', '=', $user['id'])->select(['access_token' => $user['access_token']]);

    for($i = 0; $i < count($authMfa); $i++){
      Bank_Accounts::insert([
        'user_id' => $user ['id'],
        'account_id' => $authMfa ['accounts'][$i]['_id'],
        'item' => $authMfa ['accounts'][$i]['_item'] ?? null,
        'user' => $authMfa ['accounts'][$i]['_user'] ?? null,
        'balance_available' => $authMfa ['accounts'][$i]['balance']['available'] ?? null,
        'balance_current' => $authMfa ['accounts'][$i]['balance']['current'] ?? null,
        'institution_type' => $authMfa ['accounts'][$i]['institution_type'] ?? null,
        'name' => $authMfa ['accounts'][$i]['meta']['name'] ?? null,
        'account_identifier' => $authMfa ['accounts'][$i]['meta']['number'] ?? null,
        'routing_number' => $authMfa ['accounts'][$i]['numbers']['routing'] ?? null,
        'account_number' => $authMfa ['accounts'][$i]['numbers']['account']?? null,
        'wire_routing_number' => $authMfa ['accounts'][$i]['numbers']['wireRouting']?? null,
        'account_type' => $authMfa ['accounts'][$i]['type'] ?? null,
        'account_subtype' => $authMfa ['accounts'][$i]['subtype'] ?? null
      ]);
    }
    return $authMfa;
  }

  public function connect(Request $request)// gets plaid transactional data
  {
    $user = auth()->user()->makeVisible('access_token');
    $plaid_token = $user ['access_token'];
    $start_date = $request->input('start_date'); // yyyy-mm-dd
    $end_date = $request->input('end_date'); // yyyy-mm-dd
    $business = 1;
    $getConnectData = Plaid::getConnectTransactions($plaid_token, $start_date, $end_date);
    $events = [];
    for($i = 0; $i < count($getConnectData); $i++){

      $transactions = Transaction::insert([
        'user_id' => $user ['id'],
        'business_id' => $business, //section may be deleted once business identifier has been chosen
        'plaid_transaction_id' => $getConnectData[$i]['_id'] ?? null,
        'account_id' => $getConnectData[$i]['_account'] ?? null,
        'amount' => $getConnectData[$i]['amount'] ?? null,
        'business_name' => $getConnectData[$i]['name'] ?? null,
        'is_pending' => $getConnectData[$i]['pending'] ?? null,
        'account_owner' => $getConnectData[$i]['account_owner'] ?? null,
        'section' => $getConnectData[$i]['category'][0] ?? null,
        'category' => $getConnectData[$i]['category'][1] ?? null,
        'attribute' => $getConnectData[$i]['category'][2] ?? null,
        'plaid_category_id' => $getConnectData[$i]['id'] ?? null,
        'transaction_type' => $getConnectData[$i]['type']['primary'] ?? null,
        'date' => $getConnectData[$i]['date'] ?? null
      ]);
      $events[$i] = event(new TransactionUpdate(Transaction::latest('id')->first()));
    }
    return $getConnectData;
  }

  public function exchange(Request $request) // exchanges public_token for plaid_token
  {
    $plaid_token = $request->input('plaid_token');
    $public_token = Plaid::exchangeToken($plaid_token);
    return $public_token;
  }

}
