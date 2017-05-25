<?php

namespace App\Http\Controllers;

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
    public function getUploadedFileDetails ($id){
        $fileDetails = Category::where('id',$id)->first();
        $title = $fileDetails->name;
        $filePath = $fileDetails->url;
        $uploadedFiles=array(
            'fileId'=>$id,
            'title'=>$title,
            'url'=>$filePath
        );
        return $uploadedFiles;
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
        $type = ContentType::where('name','pdf')->first();
        $typeId = $type->id;
        $fileDetails = Category::all()->where('teacherName',$teacherName)->where('type',$typeId);
        $uploadedFiles = array();
        $i = 0;
        foreach ($fileDetails as $fileDetail){
            $uploadedFiles[$i++]= $this->getUploadedFileDetails($fileDetail->id);
        }
        return view('uploadFile.index',compact('uploadedFiles'),$data);
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
            $students[$i++]=array(
                'id'=>$studentId,
                'name'=>$studentName
            );
        }

        return view('uploadFile.create',compact('students'),$data);
    }
    //Store the file
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
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

        if(($request->hasFile('fileEntries'))&&$request->hasFile('pdfCover')){
            $file=$request->file('fileEntries');
            $pdfCover = $request->file('pdfCover');
//            global $file_count;
//            $file_count= count($files);
//            global $uploadcount;
//            $uploadcount = 0;
//            foreach($files as $file) {
//                $fileName = $files->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            $pdfCoverExtension = $pdfCover->getClientOriginalExtension();
            if ($fileExtension != 'pdf' && $pdfCoverExtension != 'jpg') {

                return redirect(route('uploadPdf.create'))
                    ->with('failure', 'Upload files of pdf format only and pdf cover photo of jpg format only');
            }
            else {
                try {
                    \DB::beginTransaction();
                    $type = ContentType::where('name','pdf')->first();
                    $typeId = $type->id;
                    $fileId = \DB::table('categories')->insertgetId(['name' => $title,'teacherName'=>$teacherName,'description'=>$description,'type'=>$typeId]);
                    \DB::table('image_students')->insert(['image_id'=>$fileId,'student_id'=>$studentId]);
                    $fileName = $fileId.'_'.$title.'.'.$fileExtension;
                    $filePath = Storage::putFileAs('public/pdf',$file,$fileName);
                    $url = Storage::url('pdf/'.$fileId.'_'.$title.'.'.$fileExtension);
                    DB::table('categories')
                        ->where('id', $fileId)
                        ->update([
                            'url'=>$url
                        ]);
                    $coverName = $fileId.'_'.$title.'.'.$pdfCoverExtension;
                    $coverPath = Storage::putFileAs('public/pdf',$file,$coverName);
                    $coverUrl = Storage::url('pdf',$fileId.'_'.$title.'.'.$pdfCoverExtension);
                    \DB::table('pdf_covers')->insert(['pdf_id'=>$fileId,'cover_url'=>$coverUrl]);
                    
                } catch (Exception $e) {
                    \DB::rollBack();
//                    return "Unable to upload files";
                    return redirect(route('uploadPdf.create'))->with('failure','Unable to upload file')->withInput();
                }
                \DB::commit();
//                return $id;
                return redirect(route('uploadPdf.index'))->with('success','Successfully Uploaded file');
            }
        }
        else{
//            return "No files selected";
            return redirect(route('uploadPdf.create'))->with('failure','No files selected');
        }
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
        
        $uploadedFiles = $this->getUploadedFileDetails($id);
        return view('uploadFile.show',compact('uploadedFiles'),$data);
    }
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
