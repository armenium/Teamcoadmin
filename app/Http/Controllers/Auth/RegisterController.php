<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests\storeUser;
use Auth;

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
        $this->middleware('auth');
    }
    public function index()
    {
      
        $users = User::all()->except(Auth::user()->id);
        return view('auth.list',['users'=>$users]);
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function store(storeUser $request)
    {
        $User = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect('/register')->with('status','user registered');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('auth.edit',['user'=>$user]);
    }
    public function update(storeUser $request, $id)
    {
        switch ($request->type_form) {
            case 'general_user':
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ];
                break;
            case 'my_user':
                $data = [
                    'name' => $request->name,
                    'password' => Hash::make($request->password)
                ];
                break;
        }
        $user = User::find($id)->update($data);

        return redirect('list-user/'.$id.'/edit')->with('status','User updated');
    }
    public function destroy($id)
    {
         $user = User::find($id)->delete();
         return redirect('list-users')->with('status', 'User Destroyed');
    }
}
