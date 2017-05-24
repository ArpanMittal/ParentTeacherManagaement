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
            'filePath'=>$file_token,
            'description'=>$description,
        );
        return $uploadedImages;
    }
    //To secure url
    public function getImage($fileToken,Request $request){
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
        return view('uploadImage.upload',compact('students'),$data);
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
    //Send notification to parents after uploading images
    public function sendNotification($studentId){
        $studentDetails = Student::where('id',$studentId)->first();
        $parentId = $studentDetails->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
        //Child activities notification type
        $type = 2;
        $imageUrl = Storage::url('public/default/activity.jpg');
        $message = array("message"=>"Student Activities updated","type"=>$type,"imageUrl"=>$imageUrl);
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
    
    public function getImages($student_files){
        $i = 0;
        $files = array();
        foreach ($student_files as $student_file) {
            $fileId = $student_file->image_id;
            $file = Category::where('id', $fileId)->first();
            $type = $file->type;
            $typeDetails = ContentType::where('id',$type)->first();
            if ($typeDetails->name == 'pdf'){
                $filePath = $file->url;
                $title = $file->name;
                $description = $file->description;
                $createdAt = $file->created_at;
                $coverDetails = PdfCover::where('pdf_id',$fileId)->first();
                $cover_url = $coverDetails->cover_url;
                $files[$i++] = array(
                    'id' => $fileId,
                    'filePath' => $filePath,
                    'title' => $title,
                    'description' => $description,
                    'pdfCover'=>$cover_url,
                    'type' => $type,
                    'created_at' => $createdAt
                );
            }
            elseif ($typeDetails->name =='jpg'){
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
                    'created_at' => $createdAt
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
        $uploadedImages = $this->getUploadedImageDetails($id);
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
            Storage::delete($path);
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
}
