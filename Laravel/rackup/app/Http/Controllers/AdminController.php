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
            $age = Input::get('age');
            $gradeId   = Input::get('gradeId');
            $parentName = Input::get('parentName');
            $gender = Input::get('gender');
            $address = Input::get('address');
            $role = Input::get('role');
            $username = Input::get('username');
            $password = Input::get('password');

            try {
                \DB::beginTransaction();
                $userId = \DB::table('users')->insertgetId(['username' => $username, 'password' => $password, 'role_id' => $role]);
               \DB::table('userDetails')->insert(['name' => $parentName, 'gender' => $gender, 'address' => $address, 'user_id' => $userId]);
               
                \DB::table('students')->insert(['name' => $studentName, 'age' => $age, 'grade_id' => $gradeId, 'parent_id' => $userId]);
                
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