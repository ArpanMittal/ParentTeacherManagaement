<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
//use Illuminate\Foundation\Auth\RegistersUsers;
//use Illuminate\Support\Facades\Auth;
use App\Role;
use App\User;
use App\UserDetails;
use App\Student;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showRegisterParent(){
        return view('registerParent');
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
                'role'=>'required',
                'username'=>'required|email|distinct',
                'password'=>'required|alphanum');


            $this->validate($request,$rules);
            
            $studentName  = Input::get('studentName');
            $dob = Input::get('dob');
            $studentGender = Input::get('studentGender');
            $gradeId   = Input::get('gradeId');
            $parentName = Input::get('parentName');
            $parentGender = Input::get('parentGender');
            $address = Input::get('address');
            $contact = Input::get('contact');
            $role = Input::get('role');
            $username = Input::get('username');
            $password = Input::get('password');



            try {
                \DB::beginTransaction();
                $userId = \DB::table('users')->insertgetId(['username' => $username, 'password' => $password, 'role_id' => $role]);
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

    public function showRegisterTeacher(){
        return view('registerTeacher');
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
                'role'=>'required',
                'username'=>'required|email|distinct',
                'password'=>'required|alphanum');
            
            $this->validate($request,$rules);
            
            $teacherName = Input::get('teacherName');
            $teacherGender = Input::get('teacherGender');
            $address = Input::get('address');
            $contact = Input::get('contact');
            $role = Input::get('role');
            $username = Input::get('username');
            $password = Input::get('password');
            
            try {
                \DB::beginTransaction();
                $userId = \DB::table('users')->insertgetId(['username' => $username, 'password' => $password, 'role_id' => $role]);
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

}