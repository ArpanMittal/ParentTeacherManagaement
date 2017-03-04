<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    //Shows the login form
    public function showLogin(){
        return view('auth.login');
    }

    //Login Process
    /*public function doLogin(Request $request)
    {
        $rules = array(
            'username' => 'required|email',
            'password' => 'required|alphaNum|min:8'
        );
        $user = \DB::table('users')
            ->whereUsernameAndPassword(Input::get('email'), Input::get('password'))
            ->first();
        if (!is_null($user)) {
            //echo "logged in";
            $user = $request->user();
            echo $user;
            $user = Auth::user();
            //auth()->login($user);
            return redirect('home');
        } else {
            echo "no";
            //return redirect('login');
        }
    }*/
    
   public function doLogin(Request $request)
    {
       /*$validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $userdata = array(
                'username'     => Input::get('email'),
                'password'  => Input::get('password')
            );
            return var_export(Input::get('email'));*/
        $rules = array(
            'username'    => 'required|email',
            'password' => 'required|alphaNum|min:8'
        );
        $user =\DB::table('users')
            ->whereUsernameAndPassword(Input::get('email'),Input::get('password'))
            ->first();
        if ( !is_null($user) ){
            $request->session()->put('id',$user->id);
            return redirect('home');
        }else{
            return redirect('login')
                ->withErrors('email','Email or Password not matched.')
                ->withInput(Input::except('password'));
        }
    }



    public function returnToken(Request $request){

        $user = \DB::table('users')
            ->whereUsernameAndPassword(Input::get('email'),Input::get('password'))
            ->first();
        if ( !is_null($user) ) {
            $token = JWTAuth::fromUser($user);
            //return Response::json(compact('token'));
            $user=JWTAuth::toUser($token);
            return Response::json(['data'=>['email'=>$user->username]]);
        }
        else{
            echo 'User is null';
        }
    }

    public function doLogout($request){
        $request->session()->forget('id');
        //Auth::logout();
       // $this->auth->logout();
        return redirect('login');
    }
}
