<?php

namespace App\Http\Controllers;

use App\Grade;
use App\GradeUser;
use App\Student;
use App\User;
use App\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;
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

        $user = User::where('username',$username)->where('password',$password)->first();
        $token = JWTAuth::fromUser($user);
        $user = JWTAuth::toUser($token);
        
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
                $gradeId = $studentDetails->grade_id;
                $gradeDetails = Grade::where('id',$gradeId)->first();
                $grade = $gradeDetails->grade_name;
                $gradeUser = GradeUser::where('grade_id',$gradeId)->where('is_class_teacher',1)->first();
                $teacherId = $gradeUser->user_id;
                $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
                $teacherName = $teacherDetails->name;
                $teacherContact = $teacherDetails->contact;
                $userdata = array(
                    'token' => $token,
                    'username' => $user->username,
                    'parent_name' => $userDetails->name,
                    'contact' => $userDetails->contact,
                    'address' => $userDetails->address,
                    'studentName' => $studentDetails->name, 
                    'dob' => $studentDetails->dob,
                    'grade'=>$grade,
                    'teacherName'=>$teacherName,
                    'teacherContact'=>$teacherContact
                );
                $STATUS_CODE = Response::json(HttpResponse::HTTP_OK);
                //return Response::json(HttpResponse::HTTP_OK);
                return response()->json([$userdata, $STATUS_CODE]);
                //return Respon se::json(compact('token'));
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
        $contact = $request->get('contact');
        $address = $request->get('address');
        $profile_pic = $request->get("profile_pic");

        if(is_null($contact)||is_null($address)||is_null($profile_pic)){
            return Response::json(["Incomplete data",HttpResponse::HTTP_PARTIAL_CONTENT]);
        }
        try{
            $filePath = 'public/profile';
            $fileName = $userId.".jpg";
            $file = $this->base64_to_jpg($profile_pic,$filePath);
            Storage::putFileAs($filePath,$file,$fileName);
            $url = Storage::url('profile/'.$fileName);

            \DB::beginTransaction();

            \DB::table('userDetails')->where('id',$user->id)
                ->update([
                    'profilePhotoPath'=>  $url
                ]);

            \DB::table('userDetails')
                ->where('user_id',$userId)
                ->update([
                        'address'=>$address,
                        'contact' => $contact
                    ]
                );
        }catch (Exception $e){
            \DB::rollback();
            return Response::json(["Partial Content",HttpResponse::HTTP_PARTIAL_CONTENT]);
        }
        \DB::commit();
        return Response::json(["Success",HttpResponse::HTTP_OK]);
    }

    private function base64_to_jpg($base64_string, $output_file) {
        $ifp = fopen($output_file, "wb");
        fwrite($ifp, base64_decode($base64_string));
        fclose($ifp);
        return $output_file;
    }
}
