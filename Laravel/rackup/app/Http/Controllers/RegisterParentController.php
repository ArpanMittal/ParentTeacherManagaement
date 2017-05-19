<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use App\User;
use App\UserDetails;
use App\Student;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\DB;

class RegisterParentController extends Controller
{
    // Get the details of the specified parent user
    public function getParentDetails ($parentId){
        $parent = User::where('id',$parentId)->first();
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
        $parent_details=array(
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
        return $parent_details;
    }
    
    
    
    /**
     * Display a listing of the registered parents and students
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
            $parent_details[$i++] = $this->getParentDetails($parentId);
        }

        return view('registerParent.index',compact('parent_details'),$data);
    }
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

        return view('registerParent.create',$data);
    }
    /**
     * Store a newly created parent user.
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
            return redirect(route('registerParent.create'))->with('failure', 'Cannot create User');
        }
        \DB::commit();

        return redirect(route('registerParent.index'))->with('success', 'User created Successfully.');
    }

    /**
     * Display the specified parent user.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $parent_details=$this->getParentDetails($id);
        return view('registerParent.show', compact('parent_details'),$data);
    }

    /**
     * Show the form for editing the specified parent user.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $parent_details=$this->getParentDetails($id);
        
        return view('registerParent.edit', compact('parent_details'),$data);
    }

    /**
     * Update the specified parent user.
     *
     * @param  int    $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'dob' => 'date',
            'contact'=>'digits:10'
        );
        $this->validate($request,$rules);
        
        $parentName = $request->input("parentName");
//        $parentGender = $request->input("parentGender");
        $parentAddress = $request->input("address");
        $contact = $request->input("contact");
        $studentName = $request->input("studentName");
        $dob =  $request->input("dob");
//        $studentGender =  $request->input("studentGender");
        try{
            \DB::beginTransaction();
            DB::table('userDetails')
                ->where('user_id', $id)
                ->update([
                    'name'=>$parentName,
//                    'gender' => $parentGender,
                    'address' => $parentAddress,
                    'contact' => $contact
                ]);
            DB::table('students')
                ->where('parent_id', $id)
                ->update([
                    'name'=>$studentName,
                    'dob'=>$dob
//                    'gender' => $studentGender
                ]);
        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('registerParent.edit'))->with('failure', 'Cannot update User Details');
        }
        \DB::commit();

        return redirect(route('registerParent.index'))->with('success', 'User Details Updated Successfully.');
    }

    /**
     * Remove the specified parent user.
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
            return redirect(route('registerParent.index'))->with('failure', 'Cannot delete User');
        }
        \DB::commit();
        return redirect(route('registerParent.index'))->with('success', 'User Deleted Successfully');
    }

}