<?php

namespace App\Http\Controllers;

use Illuminate\Console\Scheduling\ScheduleRunCommand;
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

        return view('uploadFile.create',$data);
    }
    //Store the file
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $userDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $userDetails->name;
        $rules = array(
            'title' => 'required'
        );
        $this->validate($request,$rules);

        $title = Input::get('title');

        if($request->hasFile('fileEntries')){
            $file=$request->file('fileEntries');
//            global $file_count;
//            $file_count= count($files);
//            global $uploadcount;
//            $uploadcount = 0;
//            foreach($files as $file) {
//                $fileName = $files->getClientOriginalName();
            $fileExtension = $file->getClientOriginalExtension();
            if ($fileExtension != 'pdf') {

                return redirect(route('uploadPdf.create'))->with('failure', 'Upload files of pdf format only');
            }
            else {
                try {
                    \DB::beginTransaction();
                    $type = ContentType::where('name','pdf')->first();
                    $typeId = $type->id;
                    $fileId = \DB::table('categories')->insertgetId(['name' => $title,'teacherName'=>$teacherName,'type'=>$typeId]);
                    $fileName = $fileId.'_'.$title.'.'.$fileExtension;
                    $filePath = Storage::putFileAs('public/'.$id,$file,$fileName);
                    $file_url = asset('storage/'.$id.'/'.$fileId.'_'.$title.'.'.$fileExtension);
                    $url = Storage::url($id.'/'.$fileId.'_'.$title.'.'.$fileExtension);
                    DB::table('categories')
                        ->where('id', $fileId)
                        ->update([
                            'url'=>$url
                        ]);
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
