<?php

namespace App\Http\Controllers;

use App\ContentType;
use App\Grade;
use App\ImageStudent;
use App\User;
use App\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\In;
use Mockery\CountValidator\Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Student;
use App\ContentGrade;
use App\Content;
use App\Category;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    //Get the specified uploaded content details
    public function getUploadedContentDetails ($id){
        $contentDetails = Category::where('id',$id)->first();
        $contentName = $contentDetails->name;
        $url = $contentDetails->url;
        $contentGradeId = $contentDetails->contentGradeId;
        $contentGrade = ContentGrade::where('id',$contentGradeId)->first();
        $categoryId = $contentGrade->content_id;
        $categoryDetails = Content::where('id',$categoryId)->first();
        $categoryName = $categoryDetails->name;
        $gradeId = $contentGrade->grade_id;
        $grade = Grade::where('id',$gradeId)->first();
        $gradeName = $grade->grade_name;
        $uploadedContentDetails=array(
            'contentId'=>$id,
            'categoryName'=>$categoryName,
            'contentName'=>$contentName,
            'url'=>$url,
            'gradeName'=>$gradeName
        );
        return $uploadedContentDetails;
    }
    /**
     * Display a listing of the uploaded content.
     *
     * @return Response
     */
    public function index(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        $teacherDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $teacherDetails->name;
        $type = ContentType::where('name','Video')->first();
        $typeId = $type->id;
        $contentDetails = Category::all()->where('teacherName',$teacherName)->where('type',$typeId);
        $uploadedContentDetails = array();
        $i = 0;
        foreach ($contentDetails as $contentDetail){
            $uploadedContentDetails[$i++]= $this->getUploadedContentDetails($contentDetail->id);
        }
        return view('upload.index',compact('uploadedContentDetails'),$data);
    }

    /**
     * Display the specified uploaded content.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request,$id){
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $uploadedContentDetails = $this->getUploadedContentDetails($id);
        return view('upload.show',compact('uploadedContentDetails'),$data);
    }
    /**
     * Show the form for editing the specified uploaded content.
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request,$id){
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        $uploadedContentDetails=$this->getUploadedContentDetails($id);

        return view('upload.edit',compact('uploadedContentDetails'),$data);
    }
    /**
     * Update the specified uploaded content.
     *
     * @param  int    $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id){
        try{
            \DB::beginTransaction();

            $contentName = $request->input("contentName");
            $url = $request->input("url");
            if(str_contains($url,"youtu.be")||str_contains($url,"v=")){
                DB::table('categories')
                    ->where('id', $id)
                    ->update([
                        'name'=>$contentName,
                        'url' => $url
                    ]);
            }
            else{
                return redirect(route('upload.edit',$id))->with('failure','Incorrect URL type')->withInput();
            }

        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('upload.edit'))->with('failure', 'Cannot update content');
        }
        \DB::commit();
        return redirect(route('upload.index'))->with('success', 'Content Updated Successfully.');
    }

    /**
     * Remove the specified uploaded content.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id){
        try {
            \DB::beginTransaction();
            $contentDetails = Category::where('id',$id)->first();
            $contentDetails->delete();
        }
        catch (Exception $e){
            \DB::rollback();
            return redirect(route('upload.index'))->with('failure', 'Cannot delete content.');
        }
        \DB::commit();
        return redirect(route('upload.index'))->with('success', 'Content Deleted Successfully.');
    }
    /**
     * Show the form for creating a new content.
     *
     * @return Response
     */
    public function showUploadLink(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $gradeDetails = Grade::all();
        $i=0;
        $grades =  array();
        foreach ($gradeDetails as $gradeDetail){
            $gradeId = $gradeDetail->id;
            $gradeName = $gradeDetail->grade_name;
            $grades[$i++] = array(
                'id'=>$gradeId,
                'name'=>$gradeName
            );
        }
        return view('upload.uploadLink',compact('grades'),$data);
    }
    //Create a new category for selected grade
    public function createCategory(Request $request)
    {
        $gradeId = $request->get('grade');
        $contentName =$request->get('contentName');
        try {
            \DB::beginTransaction();
            $content = Content::where('name', '=',$contentName)->first();
            if ($content == null) {
                $contentId = \DB::table('contents')->insertgetId(['name' => $contentName]);
                $contentGradeId = \DB::table('content_grade')->insertgetId(['grade_id' => $gradeId, 'content_id' => $contentId]);
            }
            else{
                $contentId=$content->id;
                $contentGrade = \DB::table('content_grade')
                    ->where('grade_id', '=',$gradeId)
                    ->where('content_id','=',$contentId)
                    ->first();
                if($contentGrade==null){
                    \DB::table('content_grade')->insert(['grade_id' => $gradeId,'content_id'=>$contentId]);
                }
                else{
                    return redirect('uploadLink')->with('status','Category already exists');
                }
            }
       } catch (Exception $e) {
                \DB::rollBack();
                echo "insertion failed";
        };
        \DB::commit();
        return Response::json(HttpResponse::HTTP_OK);
    }
    //Get category for selected grade
    public function getDropdownContent($id){
        $contentGrades = ContentGrade::all()->where('grade_id',$id);
        $i=0;
        $contents = array();
        foreach ($contentGrades as $contentGrade){
            $contentId = $contentGrade->content_id;
            $contentDetail = Content::where('id',$contentId)->first();
            $contentName = $contentDetail->name;
            $contents[$i++]=array(
                'id'=>$contentId,
                'name'=>$contentName
            );
        }
        return Response::json($contents);
    }
    /**
     * Store a newly created content.
     *
     * @param Request $request
     * @return Response
     */
    public function doUploadLink(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of teacher is 4
        if ($user->role_id == 4) {

            $teacherId = $user->id;
            $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
            $teacherName = $teacherDetails->name;
            $rules = array(
                'gradeId' =>'required',
                'categoryName'=>'required',
                'categoryUrl'=>'required|url',
                'description'=>'required'
            );

            $this->validate($request,$rules);

            $gradeId = Input::get('gradeId');
            $contentName = Input::get('contentName');
            $categoryName = Input::get('categoryName');
            $categoryUrl = Input::get('categoryUrl');
            $description = Input::get('description');

            if(str_contains($categoryUrl,"youtu.be")||str_contains($categoryUrl,"v=")){
                $content = Content::where ('name',$contentName)->first();
                $contentId = $content->id;
                $contentGrade = \DB::table('content_grade')
                    ->where('grade_id', '=',$gradeId)
                    ->where('content_id','=',$contentId)
                    ->first();
                if ($contentGrade == null){
                    return redirect('uploadLink')->with('status', 'Category for this class does not exist. Please create new Category');
                }
                else{
                    $contentGradeId = $contentGrade->id;
                    try {
                        \DB::beginTransaction();
                        $type = ContentType::where('name','Video')->first();
                        $typeId = $type->id;
                        \DB::table('categories')->insert(['name' => $categoryName, 'url'=>$categoryUrl,'contentGradeId'=>$contentGradeId,'teacherName'=>$teacherName,'description'=>$description,'type'=>$typeId]);

                    } catch (Exception $e) {
                        \DB::rollBack();
                        echo "insertion failed";
                    };
                    \DB::commit();
                    return redirect(route('upload.index'))->with('success','Successfully Uploaded Video');
                }

            }

            else{
                return redirect(route('uploadLink'))->with('failure','Incorrect URL type')->withInput();
            }

        }
        else{
            echo "Permission Denied";
        }
    }
    //API to get content corresponding to the grade of logged in user
    public function getContent(Request $request){
        try {
            $token = $request->get('token');
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        if ($user->role_id == 2) {
            $studentDetails = Student::where('parent_id', $userId)->first();
            $gradeId = $studentDetails->grade_id;
            $contentGrades = ContentGrade::all()->where('grade_id', $gradeId);
            $i=0;
            $contentDetails = array();
            foreach ($contentGrades as $contentGrade){
                $contentGradeId = $contentGrade->id;
                $contentId = $contentGrade->content_id;
                $content = Content::where('id',$contentId)->first();
                $contentGradeName = $content->name;
                $contentDetails[$i++] = array(
                    'contentGradeId'=>$contentGradeId,
                    'contentName'=>$contentGradeName,
                    );
            }
            $flag = 0;
            $j = 0;
            global $categoryData;
            foreach ($contentDetails as $contentDetail) {
                $contentGradeId = $contentDetail['contentGradeId'];
                $contentName = $contentDetail['contentName'];
                $categoryDetails = Category::all()->where('contentGradeId', $contentGradeId);
                //$categoryDetails_count = count($categoryDetails);
                //echo $categoryDetails_count;
                $k = 0;
                $categoryData=array();
                foreach ($categoryDetails as $categoryDetail) {
                    $categoryName = $categoryDetail->name;
                    $url = $categoryDetail->url;
                    $teacherName = $categoryDetail->teacherName;
                    $categoryData[$k++] = array(
                        'categoryName' => $categoryName,
                        'url' => $url,
                        'teacherName'=>$teacherName
                    );
                  //  $sendContent[$j++] = array('gradeId' => $gradeId, 'contentId' => $contentId, 'contentName' => $contentName, 'categoryData' => $categoryData[$i-1]);
                }
                $flag++;
               $sendContent[$j++] = array(
                   'gradeId' => $gradeId,
                   'contentName' => $contentName,
                   'categoryData' => $categoryData);

            }
            return Response::json([$sendContent, HttpResponse::HTTP_OK]);
        } else {
            return Response::json(['Unauthorized User', HttpResponse::HTTP_UNAUTHORIZED]);
            //echo "Permission Denied";
        }
    }
    //For uploading images
    public function showUpload(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $studentDetails = Student::all();
        $i=0;
        $sudents = array();
        foreach ($studentDetails as $studentDetail){
            $studentName = $studentDetail->name;
            $studentId = $studentDetail->id;
            $students[$i++]=array(
                'id'=>$studentId,
                'name'=>$studentName
            );
        }
        return view('upload.upload',compact('students'),$data);
    }
    //Store the image
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $userDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $userDetails->name;
        $rules = array(
            'title' => 'required',
            'message' =>'required',
            'studentId'=>'required'
        );
        $this->validate($request,$rules);

        $title = Input::get('title');
        $description = Input::get('message');
        $studentId = Input::get('studentId');

        if($request->hasFile('fileEntries')){
            $file=$request->file('fileEntries');
//            global $file_count;
//            $file_count= count($files);
//            global $uploadcount;
//            $uploadcount = 0;
//            foreach($files as $file) {
//                $fileName = $files->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            if ($fileExtension != 'jpg') {
                return "Invalid format. JPG files only";
//                return redirect(route('uploadFile'))->with('failure', 'Upload images of jpg only');
            }
            else {
                try {
                    \DB::beginTransaction();
                    $type = ContentType::where('name','Image')->first();
                    $typeId = $type->id;
                    $imageId = \DB::table('categories')->insertgetId(['name' => $title,'teacherName'=>$teacherName,'description'=>$description,'type'=>$typeId]);
                    \DB::table('image_students')->insert(['image_id'=>$imageId,'student_id'=>$studentId]);
                    $fileName = $imageId.'_'.$title.'.'.$fileExtension;
                    $filePath = Storage::putFileAs('public/'.$studentId,$file,$fileName);
                    $file_url = asset('storage/'.$studentId.'/'.$imageId.'_'.$title.'.'.$fileExtension);
                    $url = Storage::url($studentId.'/'.$imageId.'_'.$title.'.'.$fileExtension);
                    DB::table('categories')
                        ->where('id', $imageId)
                        ->update([
                            'url'=>$url
                        ]);
                    } catch (Exception $e) {
                        \DB::rollBack();
                    return "Unable to upload files";
//                    return redirect(route('uploadFile'))->with('failure','Unable to upload image')->withInput();
                    };
                \DB::commit();
                return $studentId;
//                return redirect(route('uploadFile'))->with('success','Successfully Uploaded image');
            }
        }
        else{
            return "No files selected";
//            return redirect(route('uploadFile'))->with('failure','No files selected');
        }
    }
    public function sendNotification($studentId){
        $studentDetails = Student::where('id',$studentId)->first();
        $parentId = $studentDetails->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
        //Child activities notification type
        $type = 2;
        $message = array("message"=>"Student Activities updated","type"=>$type);
        $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
        return 1;
    }
    //API to send daily activity images of specified parent
    public function sendActivity(Request $request){
        try {
            $token = $request->get('token');
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $lastImageId = $request->get('lastImageId');
        if (is_null($lastImageId)){
            $studentDetails = Student::where('parent_id', $userId)->first();
            $studentId = $studentDetails->id;
            $student_images = ImageStudent::orderBy('created_at', 'desc')
                ->take(10)
                ->where('student_id',$studentId)
                ->get();
            $images = $this->getImages($student_images);
            return Response::json([$images,HttpResponse::HTTP_OK]);
        }
        else{
            $student_image = ImageStudent::where('image_id',$lastImageId)->first();
            $student_id = $student_image->student_id;
            $imageDetail = Category::where('id',$lastImageId)->first();
            $createdAt = $imageDetail->created_at;
            $student_images = ImageStudent::orderBy('created_at', 'desc')
                ->take(10)
                ->where('student_id',$student_id)
                ->where('created_at','<',$createdAt)
                ->get();

            $images = $this->getImages($student_images);
            return Response::json([$images,HttpResponse::HTTP_OK]);
        }
    }
    public function getImages($student_images){
        $i = 0;
        $images = array();
        foreach ($student_images as $student_image) {
            $imageId = $student_image->image_id;
            $image = Category::where('id', $imageId)->first();
            $filePath = $image->url;
            $title = $image->name;
            $description = $image->description;
            $type = $image->type;
            $createdAt = $image->created_at;
            $images[$i++] = array(
                'id' => $imageId,
                'filePath' => $filePath,
                'title' => $title,
                'description' => $description,
                'type' => $type,
                'created_at' => $createdAt
            );
        }
        return $images;
    }
    //generic php function to send GCM push notification
    function sendPushNotificationToGCM($registration_ids, $message) {
        //Google cloud messaging GCM-API url
        $url='https://gcm-http.googleapis.com/gcm/send';

        //$url = 'fcm.googleapis.com/fcm/';
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
        if (!defined('GOOGLE_API_KEY'))
            define("GOOGLE_API_KEY","AIzaSyBhekmES_sNi2T2YK2O7ovo9lyRor7UXJI");

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    
//    public function doUpload(Request $request)
//    {
//        $id = $request->session()->get('id');
//        $user = \DB::table('users')->whereId($id)->first();
//        //role_id of teacher is 4
//        if ($user->role_id == 4) {
//            $files=$request->file('fileEntry');
//            //$files = Input::file('fileEntry');
//            $file_count = count($files);
//            //echo $file_count;
//            $uploadcount = 0;
//            foreach($files as $file){
//                $destinationPath = '';
//                $filename = $file->getClientOriginalName();
//                $fileExtension= $file->getClientOriginalExtension();
//                //echo $fileExtension;
//                if ($fileExtension !='pdf') {
//                    echo "PDF files only";
//                    //$request->session()->flash('status', 'PDF files only');
//                    //return redirect('upload');
//                }
//                else{
//                    $upload_success = $file->move($destinationPath, $filename);
//                    $uploadcount++;
//                    //echo $filename;
//                    //echo $uploadcount;
//                    if ($uploadcount == $file_count) {
//                        Session::flash('success', 'Upload successfully');
//                        return redirect('upload');
//                    } else {
//                        Session::flash('failure', 'Upload failed');
//                        return redirect('upload');
//                    }
//                }
//            }
//        }
//    }


   
    /*public function show()
    {
        Storage::makeDirectory('public/make');
        return Storage::allFiles('public');
    }*/


}

