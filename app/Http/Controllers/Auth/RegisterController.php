<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

// email verification
use DB;
use Mail;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // register with email verification
    public function register(Request $request){
        $input = $request->all();
        $validator = $this->validator($input);

        if($validator->passes()){
            $user = $this->create($input)->toArray();
            $user['link'] = str_random(40);

            DB::table('users_activation')->insert(['user_id'=>$user['id'], 'token'=>$user['link']]);
            Mail::send('mail.activation', $user, function($message) use ($user){
                $message->to($user['email']);
                $message->subject('laravel - Activation Code');
            });
            return redirect()->to('login')->with('success', 'We sent you a verification code , please check your email');
        }
        return back()->with('Error', $validator->errors());
    }

    // log in when email is verified

    public function userActivation($token){
        $check = DB::table('users_activation')->where('token', $token)->first();

        if(!is_null($check)){
            $user = User::find($check->user_id);
            if($user->is_activated == 1){

                return redirectTo('login')->with('success', 'You are already active!');
            }
            $user->is_activated  = 1;
            $user->save();
            DB::table('users_activation')->where('token', $token)->delete();

            return redirect()->to('login')->with('success', 'You are just activated');
        }
        return redirect()->to('login')->with('error', 'You are not activated ');
    }
}
