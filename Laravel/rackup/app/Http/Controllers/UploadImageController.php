<?php

namespace App\Http\Controllers;

use App\ImageStudent;
use App\Student;
use Illuminate\Http\Request;
use App\UserDetails;
use App\ContentType;
use App\Category;

class UploadImageController extends Controller
{
    //Get the specified uploaded image details
    public function getUploadedImageDetails ($id){
        $imageDetails = Category::where('id',$id)->first();
        $title = $imageDetails->name;
        $filePath = $imageDetails->url;
        $description = $imageDetails->description;
        $studentImage = ImageStudent::where('image_id',$id)->first();
        $studentId = $studentImage->student_id;
        $studentDetails = Student::where('id',$studentId)->first();
        $studentName = $studentDetails->name;
        $uploadedImages=array(
            'imageId'=>$id,
            'studentName'=>$studentName,
            'title'=>$title,
            'filePath'=>$filePath,
            'description'=>$description,
        );
        return $uploadedImages;
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
            $uploadedImages[$i++]= $this->getUploadedImageDetails($imageDetail->id);
        }
        return view('uploadImage.index',compact('uploadedImages'),$data);
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
            $imageDetails->delete();
            $studentImages = ImageStudent::where('image_id',$id)->first();
            $studentImages->delete();
        }
        catch (Exception $e){
            \DB::rollback();
            return redirect(route('uploadImage.index'))->with('failure', 'Cannot delete image.');
        }
        \DB::commit();
        return redirect(route('uploadImage.index'))->with('success', 'Image Deleted Successfully.');
    }
}
