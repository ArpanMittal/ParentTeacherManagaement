<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Http\Request;
use App\UserDetails;
use App\ContentType;
use App\Category;
use App\Student;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\In;
use Mockery\CountValidator\Exception;
use Symfony\Component\HttpFoundation\File\File;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class UploadFileController extends Controller
{
    //Get the specified uploaded file details
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
        // foradmin
        if($user->role_id == 1){
            $Image  = ContentType::where('name','Image')->first();
            $ImageId = $Image->id;

            $fileDetails = Category::where('school_id',$user->school_id)
                ->where(function($query) use($ImageId){
                    $query->where('type',$ImageId);

                })->get();
            $uploadedFiles = array();
            $i = 0;
            foreach ($fileDetails as $fileDetail){
                $uploadedFiles[$i++]= $this->getUploadedFileDetails($fileDetail->id,$id);
            }

            return view('uploadAdminImage.index',compact('uploadedFiles'),$data);

        }else {
            $html = ContentType::where('name', 'html')->first();
            $htmlId = $html->id;
            $grade_html = ContentType::where('name', 'grade_html')->first();
            $grade_html_id = $grade_html->id;
            $school_html = ContentType::where('name', 'school_html')->first();
            $school_html_id = $school_html->id;

            $fileDetails = Category::where('teacherName', $teacherName)
                ->where(function ($query) use ($htmlId, $grade_html_id, $school_html_id) {
                    $query->where('type', $htmlId)
                        ->orwhere('type', $grade_html_id)
                        ->orwhere('type', $school_html_id);
                })->get();
            $uploadedFiles = array();
            $i = 0;
            foreach ($fileDetails as $fileDetail) {
                $uploadedFiles[$i++] = $this->getUploadedFileDetails($fileDetail->id, $id);
            }
            return view('uploadFile.index',compact('uploadedFiles'),$data);
        }

    }

    public function getUploadedFileDetails ($fileId,$userId){
        $user = \DB::table('users')->whereId($userId)->first();
        $token = JWTAuth::fromUser($user);
        $user = JWTAuth::toUser($token);
        $fileDetails = Category::where('id',$fileId)->first();
        $title = $fileDetails->name;
        $filePath = $fileDetails->url;
        $file_token = array("filePath"=>$filePath,"token"=>$token);
        $file_token=encrypt($file_token);
        $description = $fileDetails->description;
        $uploadedFiles=array(
            'fileId'=>$fileId,
            'title'=>$title,
            'filePath'=>$filePath,
            'description'=>$description
        );
        return $uploadedFiles;
    }

    /**
     * Show the form for creating a new school event.
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

        $studentDetails = Student::all();
        $i=0;
        $sudents = array();
        foreach ($studentDetails as $studentDetail){
            $studentName = $studentDetail->name;
            $studentId = $studentDetail->id;
            // restrict student viewing to particular school
            $parent = \DB::table('users')->whereId($studentDetail->parent_id)->first();
            if($user->school_id == $parent->school_id) {
                $students[$i++] = array(
                    'id' => $studentId,
                    'name' => $studentName
                );
            }
        }
        $gradeDetails = Grade::all();
        $j = 0;
        $grades = array();
        foreach ($gradeDetails as $gradeDetail){
            $gradeName = $gradeDetail->grade_name;
            $gradeId = $gradeDetail->id;
            $grades[$j++] = array(
                'gradeId'=>$gradeId,
                'gradeName'=>$gradeName
            );
        }
        if($user->role_id == 1){
            return view('uploadAdminImage.create', compact( 'grades'), $data);
        }else {
            return view('uploadFile.create', compact('students', 'grades'), $data);
        }
    }
    //Store the file
    public function store(Request $request)
    {

        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $userDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $userDetails->name;
        $rules = array(
            'studentId'=>'required',
            'title' => 'required',
            'description'=>'required'
        );
        $this->validate($request,$rules);

        $studentId = Input::get('studentId');
        $title = Input::get('title');
        $description = Input::get('description');
        $fileContents = Input::get('fileEntry');
//        if(($request->hasFile('fileEntries'))&&$request->hasFile('pdfCover')){
//            $file=$request->file('fileEntries');
//            $pdfCover = $request->file('pdfCover');
//            global $file_count;
//            $file_count= count($files);
//            global $uploadcount;
//            $uploadcount = 0;
//            foreach($files as $file) {
//                $fileName = $files->getClientOriginalName();
//            $fileExtension = $file->getClientOriginalExtension();
//            $pdfCoverExtension = $pdfCover->getClientOriginalExtension();
//            if ($fileExtension != 'pdf' && $pdfCoverExtension != 'jpg') {
//
//                return redirect(route('uploadPdf.create'))
//                    ->with('failure', 'Upload files of pdf format only and pdf cover photo of jpg format only');
//            }
//            else {
        // for admin
        if($user->role_id == 1)
        {
            if($request->hasFile('fileEntries')) {
                $file = $request->file('fileEntries');
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
            }

                if ($studentId == "school")
                {
                    try {
                        \DB::beginTransaction();
                        $type = ContentType::where('name', 'Image')->first();
                        $typeId = $type->id;
                        $fileId = \DB::table('categories')
                            ->insertgetId(['name' => $title, 'teacherName' => $teacherName, 'description' => $description, 'type' => $typeId, 'school_id' => $user->school_id]);
                        $students = Student::all();
                        foreach ($students as $student) {
                            $student_id = $student->id;
                            $parent = \DB::table('users')->whereId($student->parent_id)->first();
                            if($user->school_id == $parent->school_id) {
                                \DB::table('image_students')->insert(['image_id' => $fileId, 'student_id' => $student_id, 'is_broadcast' => true]);
                            }
                        }

                        $fileName = $fileId.'_'.$title.'.'.$fileExtension;
                        $filePath = Storage::putFileAs('public/school/'.$user->school_id,$file,$fileName);
                        $file_url = asset('storage/'.$studentId.'/'.$fileId.'_'.$title.'.'.$fileExtension);
                        $url = Storage::url('school/'.$user->school_id.'/'.$fileName);

//                    $fileName = $fileId . '_' . $title . '.jpg';
//                    Storage::put('public/school/'.$user->school_id.'/' . $fileName, $fileContents);
//                    $url = Storage::url('public/school/'.$user->school_id.'/' . $fileName);
                        DB::table('categories')
                            ->where('id', $fileId)
                            ->update([
                                'url' => $url
                            ]);
                        $students = Student::all();
                        $gcmRegistrationId = array();
                        $i = 0;
                        foreach ($students as $student) {
                            $parentId = $student->parent_id;
                            $parent = \DB::table('users')->whereId($student->parent_id)->first();
                            if($user->school_id == $parent->school_id) {
                                $parentDetails = UserDetails::where('user_id', $parentId)->first();
                                $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                            }
                        }
                        $message = array("message" => "Student Activities update", "type" => $type, "imageUrl" => $url);
                        $this->sendPushNotificationToGCM($gcmRegistrationId, $message);
                    } catch (Exception $e) {
                        \DB::rollBack();
//                    return "Unable to upload files";
                        return redirect(route('uploadPdf.create'))->with('failure', 'Unable to upload file')->withInput();
                    }
                    \DB::commit();

//                return $id;
                    return redirect(route('uploadPdf.index'))->with('success', 'Successfully Uploaded file');
                } elseif ($studentId == "grade") {
                    $gradeId = Input::get('gradeId');
                    $grade = Grade::where('id', $gradeId)->first();
                    $gradeName = $grade->grade_name;
                    try {
                        \DB::beginTransaction();
                        $type = ContentType::where('name', 'Image')->first();
                        $typeId = $type->id;
                        $fileId = \DB::table('categories')
                            ->insertgetId(['name' => $title, 'teacherName' => $teacherName, 'description' => $description, 'type' => $typeId, 'school_id' => $user->school_id]);
                        $students = Student::all()->where('grade_id', $gradeId);
                        foreach ($students as $student) {
                            $student_id = $student->id;
                            $parent = \DB::table('users')->whereId($student->parent_id)->first();
                            if($user->school_id == $parent->school_id) {
                                \DB::table('image_students')->insert(['image_id' => $fileId, 'student_id' => $student_id]);
                            }
                        }

                        $fileName = $fileId.'_'.$title.'.'.$fileExtension;
                        $filePath = Storage::putFileAs('public/school/'.$user->school_id.'/'. $gradeName.'/',$file,$fileName);
                        $file_url = asset('storage/'.$studentId.'/'.$fileId.'_'.$title.'.'.$fileExtension);
                        $url = Storage::url('school/'.$user->school_id.'/'. $gradeName.'/'.$fileName);
                        
//                        $fileName = $fileId . '_' . $title . '.html';
//                        Storage::put('public/' . $gradeName . '/' . $fileName, $fileContents);
//                        $url = Storage::url('public/' . $gradeName . '/' . $fileName);
                        DB::table('categories')
                            ->where('id', $fileId)
                            ->update([
                                'url' => $url
                            ]);
                        $studentDetails = Student::all()->where('grade_id', $gradeId);
                        $gcmRegistrationId = array();
                        $i = 0;
                        foreach ($studentDetails as $studentDetail) {
                            $parentId = $studentDetail->parent_id;
                            $parent = \DB::table('users')->whereId($parentId)->first();
                            if($user->school_id == $parent->school_id) {
                                $parentDetails = UserDetails::where('user_id', $parentId)->first();
                                $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                            }
                        }
                        $message = array("message" => "Student Activities update", "type" => $type, "imageUrl" => $url);
                        $this->sendPushNotificationToGCM($gcmRegistrationId, $message);

                    } catch (Exception $e) {
                        \DB::rollBack();
//                    return "Unable to upload files";
                        return redirect(route('uploadPdf.create'))->with('failure', 'Unable to upload file')->withInput();
                    }
                    \DB::commit();
//                return $id;
                    return redirect(route('uploadPdf.index'))->with('success', 'Successfully Uploaded file');
                }
            }
            // for others
            else {
                if ($studentId == "school") {
                    try {
                        \DB::beginTransaction();
                        $type = ContentType::where('name', 'school_html')->first();
                        $typeId = $type->id;
                        $fileId = \DB::table('categories')
                            ->insertgetId(['name' => $title, 'teacherName' => $teacherName, 'description' => $description, 'type' => $typeId, 'school_id' => $user->school_id]);
                        $students = Student::all();
                        foreach ($students as $student) {
                            $student_id = $student->id;
                            \DB::table('image_students')->insert(['image_id' => $fileId, 'student_id' => $student_id]);
                        }
                        $fileName = $fileId . '_' . $title . '.html';
                        Storage::put('public/school/' . $fileName, $fileContents);
                        $url = Storage::url('public/school/' . $fileName);
                        DB::table('categories')
                            ->where('id', $fileId)
                            ->update([
                                'url' => $url
                            ]);
                        $students = Student::all();
                        $gcmRegistrationId = array();
                        $i = 0;
                        foreach ($students as $student) {
                            $parentId = $student->parent_id;
                            $parentDetails = UserDetails::where('user_id', $parentId)->first();
                            $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                        }
                        $message = array("message" => "Student Activities update", "type" => $type, "imageUrl" => $url);
                        $this->sendPushNotificationToGCM($gcmRegistrationId, $message);
                    } catch (Exception $e) {
                        \DB::rollBack();
//                    return "Unable to upload files";
                        return redirect(route('uploadPdf.create'))->with('failure', 'Unable to upload file')->withInput();
                    }
                    \DB::commit();
//                return $id;
                    return redirect(route('uploadPdf.index'))->with('success', 'Successfully Uploaded file');
                } elseif ($studentId == "grade") {
                    $gradeId = Input::get('gradeId');
                    $grade = Grade::where('id', $gradeId)->first();
                    $gradeName = $grade->grade_name;
                    try {
                        \DB::beginTransaction();
                        $type = ContentType::where('name', 'grade_html')->first();
                        $typeId = $type->id;
                        $fileId = \DB::table('categories')
                            ->insertgetId(['name' => $title, 'teacherName' => $teacherName, 'description' => $description, 'type' => $typeId, 'school_id' => $user->school_id]);
                        $students = Student::all()->where('grade_id', $gradeId);
                        foreach ($students as $student) {
                            $student_id = $student->id;
                            \DB::table('image_students')->insert(['image_id' => $fileId, 'student_id' => $student_id]);
                        }
                        $fileName = $fileId . '_' . $title . '.html';
                        Storage::put('public/' . $gradeName . '/' . $fileName, $fileContents);
                        $url = Storage::url('public/' . $gradeName . '/' . $fileName);
                        DB::table('categories')
                            ->where('id', $fileId)
                            ->update([
                                'url' => $url
                            ]);
                        $studentDetails = Student::all()->where('grade_id', $gradeId);
                        $gcmRegistrationId = array();
                        $i = 0;
                        foreach ($studentDetails as $studentDetail) {
                            $parentId = $studentDetail->parent_id;
                            $parentDetails = UserDetails::where('user_id', $parentId)->first();
                            $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                        }
                        $message = array("message" => "Student Activities update", "type" => $type, "imageUrl" => $url);
                        $this->sendPushNotificationToGCM($gcmRegistrationId, $message);

                    } catch (Exception $e) {
                        \DB::rollBack();
//                    return "Unable to upload files";
                        return redirect(route('uploadPdf.create'))->with('failure', 'Unable to upload file')->withInput();
                    }
                    \DB::commit();
//                return $id;
                    return redirect(route('uploadPdf.index'))->with('success', 'Successfully Uploaded file');
                } else {
                    try {
                        \DB::beginTransaction();
                        $type = ContentType::where('name', 'html')->first();
                        $typeId = $type->id;
                        $fileId = \DB::table('categories')
                            ->insertgetId(['name' => $title, 'teacherName' => $teacherName, 'description' => $description, 'type' => $typeId, 'school_id' => $user->school_id]);
                        \DB::table('image_students')->insert(['image_id' => $fileId, 'student_id' => $studentId]);
                        $fileName = $fileId . '_' . $title . '.html';
                        Storage::put('public/' . $studentId . '/' . $fileName, $fileContents);
                        $url = Storage::url('public/' . $studentId . '/' . $fileName);
                        DB::table('categories')
                            ->where('id', $fileId)
                            ->update([
                                'url' => $url
                            ]);
                        $student = Student::where('id', $studentId)->first();
                        $parentId = $student->parent_id;
                        $parentDetails = UserDetails::where('user_id', $parentId)->first();
                        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
                        $message = array("message" => "Student Activities update", "type" => $type, "imageUrl" => $url);
                        $this->sendPushNotificationToGCM($gcmRegistrationId, $message);
//                    $coverName = $fileId.'_'.$title.'.'.$pdfCoverExtension;
//                    $coverPath = Storage::putFileAs('public/pdf',$file,$coverName);
//                    $coverUrl = Storage::url('pdf',$fileId.'_'.$title.'.'.$pdfCoverExtension);
//                    \DB::table('pdf_covers')->insert(['pdf_id'=>$fileId,'cover_url'=>$coverUrl]);

                    } catch (Exception $e) {
                        \DB::rollBack();
//                    return "Unable to upload files";
                        return redirect(route('uploadPdf.create'))->with('failure', 'Unable to upload file')->withInput();
                    }
                }
            }
            \DB::commit();
//                return $id;
            return redirect(route('uploadPdf.index'))->with('success','Successfully Uploaded file');
//            }


//        }
//        else{
////            return "No files selected";
//            return redirect(route('uploadPdf.create'))->with('failure','No files selected');
//        }
        }

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

        /**
         * Display the specified uploaded file.
         *
         * @param  int $id
         * @return Response
         */
        public function show(Request $request,$id){
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $userDetails = UserDetails::where('id', $user_id)->first();
        $data['profilePath'] = $userDetails->profilePhotoPath;
        $data['name'] = $userDetails->name;

        $uploadedFiles = $this->getUploadedFileDetails($id,$user_id);
        return view('uploadFile.show',compact('uploadedFiles'),$data);
    }

        //generic php function to send GCM push notification

        /**
         * Remove the specified uploaded file.
         *
         * @param  int $id
         * @return Response
         */
        public function destroy($id){
        try {
            \DB::beginTransaction();
            $fileDetails = Category::where('id',$id)->first();
            $fileUrl = $fileDetails->url;
            $path = 'public/'.substr($fileUrl,9);
            Storage::delete($path);
            $fileDetails->delete();
        }
        catch (Exception $e){
            \DB::rollback();
            return redirect(route('uploadPdf.index'))->with('failure', 'Cannot delete file.');
        }
        \DB::commit();
        return redirect(route('uploadPdf.index'))->with('success', 'File Deleted Successfully.');
    }

    }
