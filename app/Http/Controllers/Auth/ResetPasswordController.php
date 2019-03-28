<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function changePassword(Request $request)
    {
      if ($request->has("token")){
        $token = $request->token;
        $data = PasswordReset::where('reset_token', '=', $token)->get();
        //return response()->json($data);
        $db_token = $data[0]['reset_token'];
        $id = $data[0]['user_id'];
        $created_at = $data[0]['created_at'];
        if($token == $db_token){
          // return response()->json(['success' => 'token good']);
          if(Carbon::now() <= $created_at){
            $update = User::where('id', '=', $id)
                            ->update(['password' => bcrypt($request->get('password'))]);
            if ($update == 0){
              return response()->json(['failed' => 'password not updated']);
            }else{
              PasswordReset::where('reset_token', '=', $token)
                              ->delete();
              return response()->json(['success' => 'password updated']);
            }
          }else{
            return response()->json(['failed' => 'token has expired']);
          }
        }else{
          return response()->json(['failed' => 'invalid token']);
        }
      }
    }
}
