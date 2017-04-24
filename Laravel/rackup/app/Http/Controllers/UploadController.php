<?php

namespace App\Http\Controllers;

use App\Grade;
use App\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Student;
use App\ContentGrade;
use App\Content;
use App\Category;


class UploadController extends Controller
{
    public function showUpload(Request $request){
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        return view('upload.upload',$data);
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
                'categoryUrl'=>'required|url'
            );

            $this->validate($request,$rules);

            $gradeId = Input::get('gradeId');
            $contentName = Input::get('contentName');
            $categoryName = Input::get('categoryName');
            $categoryUrl = Input::get('categoryUrl');

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
                        \DB::table('categories')->insert(['name' => $categoryName, 'url'=>$categoryUrl,'contentGradeId'=>$contentGradeId,'teacherName'=>$teacherName]);

                    } catch (Exception $e) {
                        \DB::rollBack();
                        echo "insertion failed";
                    };
                    \DB::commit();
                    return redirect('uploadLink')->with('status1','Successfully Uploaded Video');
                }

            }//youtu.be and v=

            else{
                return redirect('uploadLink')->with('status','Incorrect URL type');
            }

        }
        else{
            echo "Permission Denied";
        }
    }
    
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




    public function store(Request $request)
    {
        $request->file('fileEntry');
        if($request->hasFile('fileEntry')){
        $request->fileEntry->path();
        return $request->word->store('contents');
        return Storage::putFile('contents',$request->file('fileEntry'));
        }
        else{
         return 'No file selected';
        }
    }
    /*public function show()
    {
        Storage::makeDirectory('public/make');
        return Storage::allFiles('public');
    }*/
    

}
