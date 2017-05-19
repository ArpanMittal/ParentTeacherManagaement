<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use App\User;
use App\UserDetails;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\DB;

class RegisterTeacherController extends Controller
{
    //Get the details of specified teacher user
    public function getTeacherDetails ($teacherId){
        $teacher = User::where('id',$teacherId)->first();
        $username = $teacher->username;
        $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
        $teacherName = $teacherDetails->name;
        $teacherGender = $teacherDetails->gender;
        $contact = $teacherDetails->contact;
        $address = $teacherDetails->address;
        $teacher_details=array(
            'teacherId'=>$teacherId,
            'teacherName'=>$teacherName,
            'teacherGender'=>$teacherGender,
            'contact'=>$contact,
            'address'=>$address,
            'username'=>$username
        );
        return $teacher_details;
    }

    /**
     * Display a listing of the registered teacher users
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        //Role Id of teacher is 4
        $teachers = User::all()->where('role_id',4);
        $teacher_details = array();
        $i=0;
        foreach ($teachers as $teacher)
        {
            $teacherId = $teacher->id;
            $teacher_details[$i++]=$this->getTeacherDetails($teacherId);
            
        }
        return view('registerTeacher.index',compact('teacher_details'),$data);
    }
    /**
     * Show the form for creating a new teacher user.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        return view('registerTeacher.create',$data);
    }


    /**
     * Store a newly created teacher user.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();

        $rules = array(
            'teacherName'=>'required',
            'teacherGender'=>'required',
            'address' => 'required',
            'contact'=>'required|digits:10',
            'username'=>'required|email|unique:users',
            'password'=>'required|min:6|regex:/^[a-zA-Z0-9]*$/'
        );

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
            return redirect(route('registerTeacher.index'))->with('failure', 'Cannot create User');

        }
        \DB::commit();
        return redirect(route('registerTeacher.index'))->with('success', 'User created Successfully.');
    }

    /**
     * Display the specified teacher user.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $teacher_details = $this->getTeacherDetails($id);
        
        return view('registerTeacher.show', compact('teacher_details'),$data);
    }

    /**
     * Show the form for editing the specified teacher user.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $teacher_details = $this->getTeacherDetails($id);
        return view('registerTeacher.edit', compact('teacher_details'),$data);
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
        $rules = array(
            'contact'=>'digits:10'
        );
        $this->validate($request,$rules);
        $teacherName = $request->input("teacherName");
//        $teacherGender = $request->input("teacherGender");
        $teacherAddress = $request->input("address");
        $contact = $request->input("contact");
        try{
            \DB::beginTransaction();
            DB::table('userDetails')
                ->where('user_id', $id)
                ->update([
                    'name'=>$teacherName,
//                    'gender' => $teacherGender,
                    'address' => $teacherAddress,
                    'contact' => $contact
                ]);

        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('registerTeacher.edit'))->with('failure', 'Cannot update User Details');
        }
        \DB::commit();

        return redirect(route('registerTeacher.index'))->with('success', 'User Details Updated Successfully.');
    }

    /**
     * Remove the specified teacher user.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            \DB::beginTransaction();
            $teacherDetails = UserDetails::where('user_id',$id)->first();
            $user = User::where('id',$id);
            $teacherDetails->delete();
            $user->delete();
        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('registerTeacher.index'))->with('failure', 'Cannot delete User');
        }
        \DB::commit();
        return redirect(route('registerTeacher.index'))->with('success', 'User Deleted Successfully');
    }
}
