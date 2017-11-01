<?php

namespace App\Http\Controllers;

use App\Category;
use App\ContentType;
use App\GradeUser;
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
            'room_number' => 'required|unique:grades'
        );
        $this->validate($request,$rules);

        $gradeId = Input::get('gradeId');
        if ($gradeId == 'other'){
            $gradeId = Input::get('other');
        }
        $roomNo = Input::get('room_number');
        if(Grade::where('grade_name','=',$gradeId)->exists()){
            return redirect(route('admin.create'))->with('failure', 'Grade already exists');
        }
        else{
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
    }
    //Assign registered teachers to grades
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

        $gradeUser = GradeUser::where('user_id','=',$teacherId)->where('grade_id','=',$gradeId)->first();
        if ($gradeUser != null){
            return redirect(route('getAssignTeacher'))->with('failure','Teacher already assigned to selected grade');
        }
        else{
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


    //Function to show all uploaded contents (videos, images,files)
    public function showAll(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $token = JWTAuth::fromUser($user);
        $user = JWTAuth::toUser($token);
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;

        $videoType = ContentType::where('name','Video')->first();
        $videoTypeId = $videoType->id;
        
        $imageType = ContentType::where('name','Image')->first();
        $imageTypeId = $imageType->id;

        $htmlType = ContentType::where('name','html')->first();
        $htmlTypeId = $htmlType->id;
        
        $gradeHtmlType = ContentType::where('name','grade_html')->first();
        $gradeHtmlTypeId = $gradeHtmlType->id;
        
        $schoolHtmlType = ContentType::where('name','school_html')->first();
        $schoolHtmlTypeId = $schoolHtmlType->id;
        
        $uploadedFileDetails = Category::where('type',$htmlTypeId)
            ->orwhere('type',$gradeHtmlTypeId)
            ->orwhere('type',$schoolHtmlTypeId)
            ->orwhere('type',$videoTypeId)
            ->orwhere('type',$imageTypeId)
            ->get();
        $uploadedFiles = array();
        $i= 0;
        foreach ($uploadedFileDetails as $uploadedFileDetail){
            $title = $uploadedFileDetail->name;
            $id = $uploadedFileDetail->id;
            $url = $uploadedFileDetail->url;
            $file_token = array("filePath"=>$url,"token"=>$token);
            $file_token=encrypt($file_token);
            $file_token_path = $url;
            $description = $uploadedFileDetail->description;
            $uploadedBy = $uploadedFileDetail->teacherName;
            $type = $uploadedFileDetail->type;
            $typeDetails = ContentType::where('id',$type)->first();
            $typeName = $typeDetails->name;
            $uploadedFiles[$i++] = array(
                'title'=>$title,
                'id'=>$id,
                'url'=>$file_token,
                'url_main' => $file_token_path, 
                'description'=>$description,
                'uploadedBy'=>$uploadedBy,
                'type'=>$typeName
            );
        }
        
        $deleteContents = array();
        $i =0;
        $deleted_image = DB::table('categories_delete')->get();
        foreach ($deleted_image as $deletedimage){
            $name = $deletedimage->name; 
            $url = $deletedimage->url;
            $description = $deletedimage->description;
            $deletedBy = $deletedimage->teacherName;
            $time = $deletedimage->created_at;
            $deleteContents[$i++]= array('name'=>$name, 'url' => $url, 'description' => $description, 'deletedBy' => $deletedBy, 'time' => $time);
        }

        return view('admin.showAll',compact('uploadedFiles','deleteContents' ),$data);
    }

    public function destroy($id){
        
        try {
            \DB::beginTransaction();
            $imageDetails = Category::where('id',$id)->first();
            $fileUrl = $imageDetails->url;
            $path = 'public/'.substr($fileUrl,9);
            Storage::delete($path);
            $imageDetails->delete();
//            $studentImages = ImageStudent::where('image_id',$id)->first();
//            $studentImages->delete();
        }
        catch (Exception $e){
            \DB::rollback();
            return redirect(route('showAll'))->with('failure', 'Cannot delete image.');
        }
        \DB::commit();
        return redirect(route('showAll'))->with('success', 'Image Deleted Successfully.');
    }
}