<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Mail;
use App\Mail\WelcomeUserMail;
use App\Models\Pm;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


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
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
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

        $user = User::create([
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
        $role = Role::where('slug', '=', 'member')->first();

        $system = User::where('username', 'System')->value('id');

        Pm::create([
            'sender_id' => $system,
            'receiver_id' => $user->id,
            'subject' => "Welcome to Top10torrent.site",
            'description' => "Dear ".$user->username.", welcome to Top10torrent.site. We are a friendly ratio less Tracker. Hope you will enjoy our site",
        ]);

        Mail::to($user->email)->send(new WelcomeUserMail($user));

        $user->roles()->attach($role);
        return $user;
    }
}
