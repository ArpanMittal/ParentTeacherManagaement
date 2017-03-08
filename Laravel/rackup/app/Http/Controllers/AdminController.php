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
    public function showRegister(){
        return view('registerUser');
    }

    public function doRegister(Request $request){

        $request->session()->put('id');
        $user = \DB::table('users')->first();
        //role_id of admin is 1
        if ($user->role_id == 1){
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

}