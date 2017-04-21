<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
//use Illuminate\Foundation\Auth\RegistersUsers;
//use Illuminate\Support\Facades\Auth;
use App\Role;
use App\User;
use App\UserDetails;
use App\Student;
use App\TeacherAppointmentSlots;
use App\AppointmentRequest;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showRegisterParent(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        return view('admin.registerParent',$data);
    }

    public function doRegisterParent(Request $request){

        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of admin is 1
        if ($user->role_id == 1){

            $rules = array('studentName' => 'required',
                'dob' => 'required|date',
                'studentGender'=>'required',
                'gradeId'=>'required',
                'parentName'=>'required',
                'parentGender'=>'required',
                'address' => 'required',
                'contact'=>'required|digits:10',
                'username'=>'required|email|unique:users',
                'password'=>'required|min:6|regex:/^[a-zA-Z0-9]*$/'
            );
            $this->validate($request,$rules);
            
            $studentName  = Input::get('studentName');
            $dob = Input::get('dob');
            $studentGender = Input::get('studentGender');
            $gradeId   = Input::get('gradeId');
            $parentName = Input::get('parentName');
            $parentGender = Input::get('parentGender');
            $address = Input::get('address');
            $contact = Input::get('contact');
            $username = Input::get('username');
            $password = Input::get('password');



            try {
                \DB::beginTransaction();
                $userId = \DB::table('users')->insertgetId(['username' => $username, 'password' => $password, 'role_id' => 2]);
                \DB::table('userDetails')->insert(['name' => $parentName, 'gender' => $parentGender, 'address' => $address,'contact'=>$contact,'user_id'=> $userId]);

                \DB::table('students')->insert(['name' => $studentName, 'dob' => $dob,'gender'=>$studentGender,'grade_id' => $gradeId, 'parent_id' => $userId]);

            }catch (Exception $e){
                \DB::rollBack();
                echo "insertion failed";
            }
            \DB::commit();
            return redirect('home');
        }
        else{
            echo "Permission Denied";
        }

    }

    public function showRegisterTeacher(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        return view('admin.registerTeacher',$data);
    }

    public function doRegisterTeacher(Request $request){

        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of admin is 1
        if ($user->role_id == 1){

            $rules = array(
                'teacherName'=>'required',
                'teacherGender'=>'required',
                'address' => 'required',
                'contact'=>'required|digits:10',
                'username'=>'required|email|unique:users',
                'password'=>'required|alphanum');

            $this->validate($request,$rules);

            $teacherName = Input::get('teacherName');
            $teacherGender = Input::get('teacherGender');
            $address = Input::get('address');
            $contact = Input::get('contact');
            $username = Input::get('username');
            $password = Input::get('password');

            try {
                \DB::beginTransaction();
                $userId = \DB::table('users')->insertgetId(['username' => $username, 'password' => $password, 'role_id' => 4]);
                \DB::table('userDetails')->insert(['name' => $teacherName, 'gender' => $teacherGender, 'address' => $address,'contact'=>$contact,'user_id'=> $userId]);
            }catch (Exception $e){
                \DB::rollBack();
                echo "insertion failed";

            }
            \DB::commit();
            return redirect('home');
        }
        else{
            echo "Permission Denied";
        }

    }
    
    public function getTeachersList(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        //role_id of admin is 1
        if ($user->role_id == 1) {
            $roleId=4;
            $teachers = User::all()->where('role_id', $roleId);
            $i = 0;
            foreach ($teachers as $teacher){
                $user_id = $teacher->id;
                $teacherInfo = UserDetails::all()->where('user_id',$user_id);
                foreach ($teacherInfo as $info) {
                    $teacherDetails[$i++] = array(
                        'name'=>$info->name,
                        'gender'=>$info->gender,
                        'address'=>$info->address,
                        'contact'=>$info->contact
                    );
                }
            }
           return view('admin.teachersList',compact('teacherDetails'),$data);
        }
        else{
            return Response::json(["Permission Denied", HttpResponse::HTTP_UNAUTHORIZED]);
        }
    }
    
    public function getParentsList(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        //role_id of admin is 1
        if ($user->role_id == 1) {
            $roleId=2;
            $parents = User::all()->where('role_id', $roleId);
            $i = 0;
            foreach ($parents as $parent){
                $user_id = $parent->id;
                $parentInfo = UserDetails::all()->where('user_id',$user_id);
                foreach ($parentInfo as $info) {
                    $parentDetails[$i++] = array(
                        'name'=>$info->name,
                        'gender'=>$info->gender,
                        'address'=>$info->address,
                        'contact'=>$info->contact
                    );
                }
            }
            return view('admin.parentsList',compact('parentDetails'),$data);
        }
        else{
            return Response::json(["Permission Denied", HttpResponse::HTTP_UNAUTHORIZED]);
        }
    
    }
    

}