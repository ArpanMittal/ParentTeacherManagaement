<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\UserDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use App\Grade;


class AdminController extends Controller
{
    /**
     * Show the form for creating a new parent user
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;
        
        return view('admin.create',$data);
    }
    /**
     * Store a newly created grade.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();

        $rules = array('gradeId' => 'required',
            'roomNo' => 'required'
        );
        $this->validate($request,$rules);

        $gradeId = Input::get('gradeId');
        $roomNo = Input::get('roomNo');
        try {
            \DB::beginTransaction();
            \DB::table('grades')->insert(['grade_name'=>$gradeId,'room_number'=>$roomNo]);

        }catch (Exception $e){
            \DB::rollBack();
            return redirect(route('admin.create'))->with('failure', 'Cannot add grade');
        }
        \DB::commit();

        return redirect(route('admin.create'))->with('success', 'Grade added successfully.');
    }

    public function getAssignTeacher(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;

        $teacherUsers = User::all()->where('role_id', 4);
        $i = 0;
        $teacherData=array();
        foreach ($teacherUsers as $teacherUser) {
            $teacherId = $teacherUser->id;
            $teacherDetails = UserDetails::where('user_id', $teacherId)->first();
            $teacherName = $teacherDetails->name;
            $teacherData[$i++] = array(
                'id' => $teacherId,
                'name' => $teacherName
            );
        }
        $grades = array();
        $j=0;
        $gradeDetails = Grade::all();
        foreach ($gradeDetails as $gradeDetail){
            $gradeId = $gradeDetail->id;
            $gradeName = $gradeDetail->grade_name;
            $grades[$i++] = array(
                'gradeId'=>$gradeId,
                'gradeName'=>$gradeName
            );
        }
        return view('admin.assignTeacher',compact('teacherData','grades'),$data);
    }

    public function postAssignTeacher(Request $request){
        $rules = array('gradeId' => 'required',
            'teacherId' => 'required',
            'isClassTeacher'=>'required'
        );
        $this->validate($request,$rules);

        $gradeId = Input::get('gradeId');
        $teacherId = Input::get('teacherId');
        $isClassTeacher = Input::get('isClassTeacher');
        try {
            \DB::beginTransaction();
            \DB::table('grade_user')->insert(['user_id'=>$teacherId,'grade_id'=>$gradeId,'is_class_teacher'=>$isClassTeacher]);

        }catch (Exception $e){
            \DB::rollBack();
            return redirect(route('getAssignTeacher'))->with('failure', 'Cannot add grade')->withInput();
        }
        \DB::commit();

        return redirect(route('getAssignTeacher'))->with('success', 'Grade added successfully.')->withInput();
    }
    
    //Show edit profile page for logged in teacher user
    public function editProfileDetails (Request $request){

        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;
        
        $username = $user->username;
        $userDetails = UserDetails::where('user_id',$id)->first();
        $name = $userDetails->name;
        $gender = $userDetails->gender;
        $contact = $userDetails->contact;
        $address = $userDetails->address;
        
        $profile_details=array(
            'id'=>$id,
            'name'=>$name,
            'gender'=>$gender,
            'contact'=>$contact,
            'address'=>$address,
            'username'=>$username
        );
        return view('admin.editProfile', compact('profile_details'),$data);
    }
    //Update edited profile details by logged in teacher user
    public function updateProfileDetails ($id,Request $request){

        $rules = array(
            'contact'=>'digits:10',
        );
        $this->validate($request,$rules);
        $flag = 0;
        if($request->hasFile('profilePhoto')){
            $file = $request->file('profilePhoto');
            $fileExtension = $file->getClientOriginalExtension();
            if ($fileExtension != 'jpg') {
                return redirect(route('editProfileDetails'))->with('failure', 'Invalid file format.Upload jpg files only')->withInput();
            }
            else {
                $fileName = $id.'.'.$fileExtension;
                $filePath = Storage::putFileAs('public/profilePhotos',$file,$fileName);
                $url = Storage::url('profilePhotos/'.$id.'.'.$fileExtension);
                try{
                    \DB::beginTransaction();
                    DB::table('userDetails')
                        ->where('user_id', $id)
                        ->update([
                            'profilePhotoPath'=>$url
                        ]);
                }catch (Exception $e){
                    \DB::rollBack();
                    $flag =1;
                    return redirect(route('editProfileDetails'))->with('failure','Unable to upload profile photo')->withInput();
                }
                \DB::commit();
//                return redirect(route('editProfileDetails'))->with('success','Profile Photo successfully uploaded');
            }
        }
        $address= $request->input("address");
        $contact = $request->input("contact");
        try{
            \DB::beginTransaction();
            DB::table('userDetails')
                ->where('user_id', $id)
                ->update([
                    'address' => $address,
                    'contact' => $contact
                ]);

        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('editProfileDetails'))->with('failure', 'Cannot update Profile Details');
        }
        \DB::commit();
        var_export($flag);
        return redirect(route('editProfileDetails'))->with('success', 'Profile Details Updated Successfully.');
    }
}