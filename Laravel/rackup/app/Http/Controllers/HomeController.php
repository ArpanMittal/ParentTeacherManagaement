<?php

namespace App\Http\Controllers;

use App\Student;
use App\UserDetails;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use Psy\Exception\ErrorException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
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
    
    //Do login
    public function doLogin(Request $request)
    {
        $rules = array(
            'username'    => 'required|email',
            'password' => 'required|alphaNum'
        );
        $username = $request->input('email');
        $password = $request->input('password');

        $user =\DB::table('users')
            ->where('username','=',$username,'AND','password','=',$password)
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

    //Do logout
    public function doLogout(Request $request){
        $request->session()->forget('id');
        return redirect('login');
    }


    //Return token and save the gcm registration id of the logged in user
    public function returnToken(Request $request)
    {
        $user = \DB::table('users')
            ->whereUsernameAndPassword(Input::get('email'), Input::get('password'))
            ->first();

        if (!is_null($user)) {
            $userId = $user->id;
            $gcmRegistrationId = $request->get('gcmRegistrationId');
            if (!is_null($gcmRegistrationId)){
                try{
                    \DB::beginTransaction();
                    \DB::table('userDetails')
                        ->where('user_id', $userId)
                        ->update(['gcmRegistrationId' => $gcmRegistrationId]);
                }catch (Exception $e){
                    \DB::rollback();
                }
                \DB::commit();
            }
            try {
                $token = JWTAuth::fromUser($user);
                $user = JWTAuth::toUser($token);
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
    
    //API to update profile details 
    public function editProfile(Request $request){
        try {
            $token = $request->get('token');
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $parentName = $request->get('parentName');
//        $parentGender = $request->get('parentGender');
        $contact = $request->get('contact');
        $address = $request->get('address');
        $studentName  =$request->get('studentName');
        $dob = $request->get('dob');
//        $studentGender = $request->get('studentGender');
        $username = $request->get('username');

        try{
            \DB::beginTransaction();
            \DB::table('userDetails')
                ->where('user_id',$userId)
                ->update([
                        'name'=>$parentName,
//                        'gender'=>$parentGender,
                        'address'=>$address,
                        'contact' => $contact
                    ]
                );
            \DB::table('students')
                ->where('parent_id',$userId)
                ->update([
                        'name'=>$studentName,
                        'dob'=>$dob,
//                        'gender'=>$studentGender
                    ]
                );
        }catch (Exception $e){
            \DB::rollback();
        }
        \DB::commit();
    }
}
