<?php

namespace App\Http\Controllers;

use App\Student;
use App\UserDetails;
use Illuminate\Http\Request;
use Psy\Exception\ErrorException;
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
use Symfony\Component\HttpFoundation\Session\Session;


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
    public function index(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        return view('home',$data);
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
            'password' => 'required|alphaNum'
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

    public function doLogout(Request $request){
        $request->session()->forget('id');
        return redirect('login');
    }


    public function returnToken(Request $request)
    {

        $user = \DB::table('users')
            ->whereUsernameAndPassword(Input::get('email'), Input::get('password'))
            ->first();


        if (!is_null($user)) {
            try {
                $token = JWTAuth::fromUser($user);
                $user = JWTAuth::toUser($token);

                $userId = $user->id;
                $userDetails = UserDetails::where('user_id', $userId)->first();
                $studentDetails = Student::where('parent_id', $userId)->first();

                $userdata = array(
                    'token' => $token,
                    'username' => $user->username,
                    'parent_name' => $userDetails->name,
                    'contact' => $userDetails->contact,
                    'address' => $userDetails->address,
                    'studentName' => $studentDetails->name,
                    'dob' => $studentDetails->dob

                );
                $STATUS_CODE = Response::json(HttpResponse::HTTP_OK);
                //return Response::json(HttpResponse::HTTP_OK);
                return response()->json([$userdata, $STATUS_CODE]);
                //return Response::json(compact('token'));
            } catch (ErrorException $e) {

                return Response::json(['error' => 'Invalid credentials'], HttpResponse::HTTP_UNAUTHORIZED);
            }
        } else {
            return Response::json(['error' => 'Null User'], HttpResponse::HTTP_NO_CONTENT);
        }

    }
}
