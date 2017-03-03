<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
//use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\User;

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
    
    public function showLogin(){
       // return "hello";
        return view('auth.login');
    }
    
    public function doLogin(Request $request)
    {


        $rules = array(
            'username'    => 'required|email',
            //'password' => 'required|alphaNum|min:8'
        );

        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return redirect('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $userdata = array(
                'username'     => Input::get('email'),
                'password'  => Input::get('password')
            );
            return var_export(Input::get('email'));
            $user = \DB::table('users')
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

    }

    public function returnToken(Request $request){

        $user = DB::table('users')
            ->whereEmailAndPassword(Input::get('email'),Input::get('password'))
            ->first();
        if ( !is_null($user) ) {
            try {
            } catch (Exception $e) {
                return Response::json(['error' => 'User already exists'], HttpResponse::HTTP_CONFLICT);

            }
            $token = JWTAuth::fromUser($user);
            return Response::json(compact('token'));
        }
    }

    public function doLogout($request){
        $request->session()->forget('id');
        return redirect('login');
    }
}
