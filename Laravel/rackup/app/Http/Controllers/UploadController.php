<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Student;
use App\ContentGrade;
use App\Content;
use App\Category;


class UploadController extends Controller
{
    public function showUpload(){
        return view('upload.upload');
    }
    public function doUpload(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of teacher is 4
        if ($user->role_id == 4) {
            $files=$request->file('fileEntry');
            //$files = Input::file('fileEntry');
            $file_count = count($files);
            //echo $file_count;
            $uploadcount = 0;
            foreach($files as $file){
                $destinationPath = '';
                $filename = $file->getClientOriginalName();
                $fileExtension= $file->getClientOriginalExtension();
                //echo $fileExtension;
                if ($fileExtension !='pdf') {
                    echo "PDF files only";
                    //$request->session()->flash('status', 'PDF files only');
                    //return redirect('upload');
                }
                else{
                    $upload_success = $file->move($destinationPath, $filename);
                    $uploadcount++;
                    //echo $filename;
                    //echo $uploadcount;
                    if ($uploadcount == $file_count) {
                        Session::flash('success', 'Upload successfully');
                        return redirect('upload');
                    } else {
                        Session::flash('failure', 'Upload failed');
                        return redirect('upload');
                    }
                }
            }
        }
    }

    public function showUploadLink(){
        return view('upload.uploadLink');
    }

    public function doUploadLink(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of teacher is 4
        if ($user->role_id == 4) {

            $rules = array(
                'gradeId' =>'required',
                'contentName'=>'required',
                'categoryName'=>'required',
                'categoryUrl'=>'required|url'
            );

            $this->validate($request,$rules);

            $gradeId = Input::get('gradeId');
            $contentName = Input::get('contentName');
            $categoryName = Input::get('categoryName');
            $categoryUrl = Input::get('categoryUrl');

            try {
                \DB::beginTransaction();
                $contentId = \DB::table('contents')->insertgetId(['name' => $contentName]);
                \DB::table('categories')->insert(['name' => $categoryName, 'url'=>$categoryUrl,'content_id'=>$contentId]);
                \DB::table('content_grade')->insert(['grade_id' => $gradeId, 'content_id'=> $contentId]);

            } catch (Exception $e) {
                \DB::rollBack();
                echo "insertion failed";
            }
            \DB::commit();
            $STATUS_CODE =Response::json(HttpResponse::HTTP_OK);
            return response()->json([$categoryUrl,$STATUS_CODE]);
        }
        else{
            echo "Permission Denied";
        }
    }
    
    public function getContent(Request $request){
        //$id = $request->session()->get('id');
       $token = $request->get('token');
         //$token = Input::get('token');
        $user=JWTAuth::toUser($token);
        $userId = $user->id ;

        if ($user->role_id == 2) {
            $studentDetails= Student::where('parent_id',$userId)->first();
            $gradeId = $studentDetails->grade_id;
            $contentGradeDetails = ContentGrade::all()->where('grade_id',$gradeId);
            $contentGradeDetails_count = count($contentGradeDetails);
            $flag=0;
            $i=0;
            $j=0;
            foreach ($contentGradeDetails as $contentGradeDetail) {
                $contentId = $contentGradeDetail->content_id;
                $contentDetails = Content::where('id', $contentId)->first();
                $contentName = $contentDetails->name;
                $categoryDetails = Category::all()->where('content_id',$contentId);

                foreach ($categoryDetails as $categoryDetail){
                   $categoryName = $categoryDetail->name;
                    $url = $categoryDetail->url;
                    $categoryData[$i] = array(
                        'categoryName'=> $categoryName,
                        'url'=> $url
                    );
                    $i++;
                }
                $contentData[$j]=array(
                    'gradeId'=>$gradeId,
                    'contentId'=>$contentId,
                    'contentName'=>$contentName,
                    'categoryData'=>$categoryData[($i-1)]
                );
                $j++;
                $flag++;
                if($flag==$contentGradeDetails_count) {
                    $STATUS_CODE = Response::json(HttpResponse::HTTP_OK);
                    return response()->json([$contentData,$STATUS_CODE]);
                }

            }
        }
        else{
            echo "Permission Denied";
        }
    }




   /* public function store(Request $request)
    {
        $request->file('fileEntry');
        if($request->hasFile('fileEntry')){

        $request->fileEntry->path();
        return $request->word->store('public');
        return Storage::putFile('public',$request->file('fileEntry'));
        }
        else{
         return 'No file selected';
        }
        }
    }
    public function show()
    {
        Storage::makeDirectory('public/make');
        return Storage::allFiles('public');
    }*/
    

}
