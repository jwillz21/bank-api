<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use Validator;
use JWTFactory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
      $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
   {
     $validator = Validator::make($request->all(), [
         'email' => 'required|string|email|max:255',
         'password'=> 'required'
     ]);
     if ($validator->fails()) {
         return response()->json($validator->errors());
     }
     $credentials = $request->only('email', 'password');
     try {
         if (! $token = JWTAuth::attempt($credentials)) {
             return response()->json(['error' => 'invalid_credentials'], 401);
         }
     } catch (JWTException $e) {
         return response()->json(['error' => 'could_not_create_token'], 500);
       }
    return $this->respondWithToken($token);
   }

   public function logout()
   {
     auth()->logout();
     return response()->json(['message' => 'Successfully logged out']);
   }

   protected function respondWithToken($token)
   {
   $json = [
       'access_token' => $token,
       'token_type' => 'bearer',
       // 'expires_in' => auth('api')->factory()->getTTL() * 60,
   ];
     return response()->json($json);
  }
}
