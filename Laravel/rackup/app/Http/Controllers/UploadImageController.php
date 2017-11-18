<?php

namespace App\Http\Controllers;

use App\ImageStudent;
use App\PdfCover;
use App\Student;
use Illuminate\Http\Request;
use App\UserDetails;
use App\ContentType;
use App\Category;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;
use Mockery\Matcher\Type;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class UploadImageController extends Controller
{
    //Get the specified uploaded image details
    public function getUploadedImageDetails ($imageId,$userId){
        $user = \DB::table('users')->whereId($userId)->first();
        $token = JWTAuth::fromUser($user);
        $user = JWTAuth::toUser($token);
        $imageDetails = Category::where('id',$imageId)->first();
        $title = $imageDetails->name;
        $filePath = $imageDetails->url;
        $file_token = array("filePath"=>$filePath,"token"=>$token);
        $file_token=encrypt($file_token);
        $description = $imageDetails->description;
        $studentImage = ImageStudent::where('image_id',$imageId)->first();
        $studentId = $studentImage->student_id;
        $studentDetails = Student::where('id',$studentId)->first();
        $studentName = $studentDetails->name;
        $uploadedImages=array(
            'imageId'=>$imageId,
            'studentName'=>$studentName,
            'title'=>$title,
            'filePath'=>$filePath,
            'description'=>$description,
        );
        return $uploadedImages;
    }
    //To secure url
    public function getFile($fileToken,Request $request){
        $fileToken = decrypt($fileToken);
        try {
            $token = $fileToken['token'];
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return redirect(route('uploadImage.index'))->with('failure','Token expired');
        }catch (TokenInvalidException $e){
            return redirect(route('uploadImage.index'))->with('failure','Invalid Token');
        }
        $filePath = $fileToken['filePath'];
        return redirect($filePath);
    }
    /**
     * Display a listing of the uploaded images.
     *
     * @return Response
     */
    public function index(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;

        $teacherDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $teacherDetails->name;
        $type = ContentType::where('name','Image')->first();
        $typeId = $type->id;
        $imageDetails = Category::all()->where('teacherName',$teacherName)->where('type',$typeId);
        $uploadedImages = array();
        $i = 0;
        foreach ($imageDetails as $imageDetail){
            $uploadedImages[$i++]= $this->getUploadedImageDetails($imageDetail->id,$id);
        }
        return view('uploadImage.index',compact('uploadedImages'),$data);
    }
    //For uploading images
    public function showUpload(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;

        $studentDetails = Student::all();
        $grades = \DB::table('grade_user')->where('user_id',$id)->get();
        $i=0;
        $students = array();
        // for teacher point of view
        if($user->role_id == 4) {
            foreach ($studentDetails as $studentDetail) {
                $studentName = $studentDetail->name;
                $studentId = $studentDetail->id;
                $flag = false;
                foreach ($grades as $grade) {
                    if ($grade->grade_id == $studentDetail->grade_id) {
                        $flag = true;
                        break;
                    }
                }
                if ($flag) {

                    $parent = \DB::table('users')->where('id',$studentDetail->parent_id)->first();
                    if($parent->school_id==$user->school_id) {
                        $students[$i++] = array(
                            'id' => $studentId,
                            'name' => $studentName
                        );
                    }
                }
            }
        }
        //TODO:: change it for now
        // for admin point of view
        else{
            foreach ($studentDetails as $studentDetail) {
                $studentName = $studentDetail->name;
                $studentId = $studentDetail->id;

                    $students[$i++] = array(
                        'id' => $studentId,
                        'name' => $studentName
                    );
            }
        }



        return view('uploadImage.upload',compact('students'),$data);
    }
    //Store the image
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $userDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $userDetails->name;
        $rules = array(
            'title' => 'required',
            'message' =>'required',
            'studentId'=>'required',
            'circle_time'=>'required',
            'activities' => 'required',
            'first_meal' => 'required',
            'curriculum' => 'required',
            'second_meal' => 'required',
            'third_meal' => 'required',
            'evening_activity' => 'required',
            'other' => 'required',
        );
        $this->validate($request,$rules);

        $title = Input::get('title');
        $description = Input::get('message');
        $studentId = Input::get('studentId');
        $circle_time = Input::get('circle_time');
        $activities = Input::get('activities');
        $first_meal = Input::get('first_meal');
        $curriculum = Input::get('curriculum');
        $second_meal = Input::get('second_meal');
        $third_meal = Input::get('third_meal');
        $evening_activity = Input::get('evening_activity');
        $other = Input::get('other');

        

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
                    $imageId = \DB::table('categories')->insertgetId(['name' => $title,'teacherName'=>$teacherName,'description'=>$description,'type'=>$typeId , 'school_id'=> $user->school_id]);
                    $image_student_id = \DB::table('image_students')->insertGetId(['image_id'=>$imageId,'student_id'=>$studentId , 'is_broadcast'=>false]);
                    \DB::table('image_details')->insert(['circle_time'=>$circle_time, '1st meal'=>$first_meal, '2nd meal' => $second_meal, '3rd meal' => $third_meal, 'activities'=> $activities, 'evening activities' => $evening_activity, 'others'=> $other, 'image_student_id' => $image_student_id]);
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
    //Send notification to parents after uploading images
    public function sendNotification($studentId){
        $studentDetails = Student::where('id',$studentId)->first();
        $parentId = $studentDetails->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
        //Child activities notification type
        $type = 2;
        $imageUrl = Storage::url('public/default/activity.jpg');
        $message = array("message"=>"Student Activities update","type"=>$type,"imageUrl"=>$imageUrl);
        $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
        return 1;
    }
    //API to send daily activity images and files of a specified student
    public function sendActivity(Request $request){
        try {
            $token = $request->get('token');
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
//            $chool_id = $user->School_id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $lastImageId = $request->get('lastImageId');
        if (is_null($lastImageId)){
            $studentDetails = Student::where('parent_id', $userId)->first();
            $studentId = $studentDetails->id;
            $student_files = ImageStudent::orderBy('created_at', 'desc')
                ->take(10)
                ->where('student_id',$studentId)
                ->get();
            $files = $this->getFiles($student_files);
            return Response::json([$files,HttpResponse::HTTP_OK]);
        }
        else{
            $student_file = ImageStudent::where('image_id',$lastImageId)->first();
            $student_id = $student_file->student_id;
            $fileDetail = Category::where('id',$lastImageId)->first();
            $createdAt = $fileDetail->created_at;
            $student_files = ImageStudent::orderBy('created_at', 'desc')
                ->take(10)
                ->where('student_id',$student_id)
                ->where('created_at','<',$createdAt)
                ->get();


            $images = $this->getFiles($student_files);
            return Response::json([$images,HttpResponse::HTTP_OK]);
        }
    }
    //Get the details of files for the timeline feed
    public function getFiles($student_files){
        $i = 0;
        $files = array();
        foreach ($student_files as $student_file) {
            $is_broadcast = $student_file->is_broadcast;
            $image_student_id = $student_file->id;
            $fileId = $student_file->image_id;
            $file = Category::where('id', $fileId)->first();
            $type = $file->type;
            $typeDetails = ContentType::where('id',$type)->first();
            $typeName = $typeDetails->name;
           $image_details = null;
            if ($typeName == 'html' || $typeName =="grade_html" || $typeName =="school_html" || $typeName == "Image" ) {
                if(!$is_broadcast){
                    $image_details= \DB::table('image_details')->where('image_student_id',$image_student_id)->first();
                }
                $filePath = $file->url;
                $title = $file->name;
                $description = $file->description;
                $createdAt = $file->created_at;
                
                $files[$i++] = array(
                    'id' => $fileId,
                    'filePath' => $filePath,
                    'title' => $title,
                    'description' => $description,
                    'type' => $type,
                    'typeName' => $typeName,
                    'created_at' => $createdAt,
                    'is_broadcast' => $is_broadcast,
                    'image_student_id'  => $image_student_id,
                    'image_details' => $image_details,
                );
            }
        }
        return $files;
    }

    /**
     * Display the specified uploaded content.
     *
     * @param  int $id
     * @return Response
     */
    public function show(Request $request,$id){
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $uploadedImages = $this->getUploadedImageDetails($id,$user_id);
        return view('uploadImage.show',compact('uploadedImages'),$data);
    }

    /**
     * Remove the specified uploaded image.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id){
        try {
            \DB::beginTransaction();
            $imageDetails = Category::where('id',$id)->first();
            $fileUrl = $imageDetails->url;
            $path = 'public/'.substr($fileUrl,9);
            DB::table('categories_delete')->insert(
                ['id' => $imageDetails->id, 'url' => $fileUrl, 'name'=> $imageDetails->name, 'contentGradeId' => $imageDetails->contentGradeId, 'teacherName' => $imageDetails->teacherName, 'type'=>$imageDetails->type, 'description'=>$imageDetails->description]
            );
//            Storage::delete($path);
           $imageDetails->delete();
//            $studentImages = ImageStudent::where('image_id',$id)->first();
//            $studentImages->delete();
        }
        catch (Exception $e){
            \DB::rollback();
            return redirect(route('uploadImage.index'))->with('failure', 'Cannot delete image.');
        }
        \DB::commit();
        return redirect(route('uploadImage.index'))->with('success', 'Image Deleted Successfully.');
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
        // Google Cloud Messaging GCM API Key
        define("GOOGLE_API_KEY","AIzaSyA7L7TUfGzpFtIBGLvxA8YAB4gcSCeiJII");

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
}
