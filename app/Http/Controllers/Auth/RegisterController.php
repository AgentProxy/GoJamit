<?php

namespace App\Http\Controllers\Auth;
use Auth;
use App\User;
use App\Instrument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
            // 'birthdate'=> 'required|date|',
            // 'fname' => 'required|string',
            // 'lname' => 'required|string',
            // 'age' => 'required|integer|3'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
         $user = User::create([
            'email' => $data['email'],
            'prof_pic'=> null,
            'sex' => $data['sex'],
            'latitude'=> null,
            'longitude'=> null,
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'birthdate' => $data['birthdate'],
            'username' => $data['username'],
            // 'password' => bcrypt($data['password'])
            'password' => $data['password']
        ]);

        $instruments = Input::get('instruments');
        if($instruments!=""){
            foreach($instruments as $instrument){
                DB::table('user_instruments')->insert(
                array('user_id'=>($user->id),'instrument_id'=>$instrument)
                );
            }
        }

        $genres = Input::get('genres');
        if($genres!=""){
            foreach($genres as $genre){
                DB::table('user_genres')->insert(
                array('user_id'=>($user->id),'genre_id'=>$genre)
                );
            }
        }



        return $user;
        
    

    }
}
