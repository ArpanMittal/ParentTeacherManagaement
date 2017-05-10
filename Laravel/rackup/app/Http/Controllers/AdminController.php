<?php

namespace App\Http\Controllers;

use App\Grade;
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
use Mockery\CountValidator\Exception;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        //Role Id of parent is 2
        $parents = User::all()->where('role_id',2);
        $parent_details = array();
        $i=0;
        foreach ($parents as $parent)
        {
            $parentId = $parent->id;
            $username = $parent->username;
            $parentDetails = UserDetails::where('user_id',$parentId)->first();
            $parentName = $parentDetails->name;
            $parentGender = $parentDetails->gender;
            $contact = $parentDetails->contact;
            $address = $parentDetails->address;
            $students = Student::where('parent_id',$parentId)->first();
            $studentId = $students->id;
            $studentName = $students->name;
            $dob = $students->dob;
            $studentGender = $students->gender;
            $gradeId = $students->grade_id;
            $grade = Grade::where('id',$gradeId)->first();
            $gradeName = $grade->grade_name;
            $parent_details[$i++]=array(
                'studentId'=>$studentId,
                'studentName'=>$studentName,
                'studentGender'=>$studentGender,
                'dob'=>$dob,
                'gradeName'=>$gradeName,
                'parentId'=>$parentId,
                'parentName'=>$parentName,
                'parentGender'=>$parentGender,
                'contact'=>$contact,
                'address'=>$address,
                'username'=>$username
            );
        }

        return view('admin.index',compact('parent_details'),$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        return view('admin.create',$data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();

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
            return redirect(route('admin.index'))->with('message', 'Cannot create User');
        }
        \DB::commit();

        return redirect(route('admin.index'))->with('message', 'User created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        
        $parent = User::where('id',$id)->first();
        $username = $parent->username;
        $parentDetails = UserDetails::where('user_id',$id)->first();
        $parentName = $parentDetails->name;
        $parentGender = $parentDetails->gender;
        $contact = $parentDetails->contact;
        $address = $parentDetails->address;
        $students = Student::where('parent_id',$id)->first();
        $studentId = $students->id;
        $studentName = $students->name;
        $dob = $students->dob;
        $studentGender = $students->gender;
        $gradeId = $students->grade_id;
        $grade = Grade::where('id',$gradeId)->first();
        $gradeName = $grade->grade_name;
        $parent_details=array(
            'studentId'=>$studentId,
            'studentName'=>$studentName,
            'studentGender'=>$studentGender,
            'dob'=>$dob,
            'gradeName'=>$gradeName,
            'parentId'=>$id,
            'parentName'=>$parentName,
            'parentGender'=>$parentGender,
            'contact'=>$contact,
            'address'=>$address,
            'username'=>$username
        );
    

        return view('admin.show', compact('parent_details'),$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;


        $parent = User::where('id',$id)->first();
        $username = $parent->username;
        $parentDetails = UserDetails::where('user_id',$id)->first();
        $parentName = $parentDetails->name;
        $parentGender = $parentDetails->gender;
        $contact = $parentDetails->contact;
        $address = $parentDetails->address;
        $students = Student::where('parent_id',$id)->first();
        $studentId = $students->id;
        $studentName = $students->name;
        $dob = $students->dob;
        $studentGender = $students->gender;
        $gradeId = $students->grade_id;
        $grade = Grade::where('id',$gradeId)->first();
        $gradeName = $grade->grade_name;
        $parent_details=array(
            'studentId'=>$studentId,
            'studentName'=>$studentName,
            'studentGender'=>$studentGender,
            'dob'=>$dob,
            'gradeName'=>$gradeName,
            'parentId'=>$id,
            'parentName'=>$parentName,
            'parentGender'=>$parentGender,
            'contact'=>$contact,
            'address'=>$address,
            'username'=>$username
        );
        return view('admin.edit', compact('parent_details'),$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int    $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try{
            \DB::beginTransaction();
            $parentDetails = UserDetails::where('user_id',$id)->first();
            $parentDetails->name = $request->input("parentName");
            $parentDetails->gender = $request->input("parentGender");
            $parentDetails->address = $request->input("address");
            $parentDetails->contact = $request->input("contact");
            $parentDetails->save();
            $studentDetails = Student::where('parent_id',$id)->first();
            $studentDetails->name = $request->input("studentName");
            $studentDetails->dob = $request->input("dob");
            $studentDetails->gender = $request->input("studentGender");
            $studentDetails->save();
            
        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('admin.index'))->with('failure', 'Cannot update User Details');
        }
        \DB::commit();

        return redirect(route('admin.index'))->with('success', 'User Details Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            \DB::beginTransaction();
            $parentDetails = UserDetails::where('user_id',$id)->first();
            $studentDetails = Student::where('parent_id',$id)->first();
            $user = User::where('id',$id);
            $parentDetails->delete();
            $studentDetails->delete();
            $user->delete();
        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('admin.index'))->with('failure', 'Cannot delete User');
        }
        \DB::commit();
        return redirect(route('admin.index'))->with('success', 'User Deleted Successfully');
    }
    
    
    
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