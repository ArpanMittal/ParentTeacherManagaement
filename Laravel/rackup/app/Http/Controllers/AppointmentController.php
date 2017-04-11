<?php

namespace App\Http\Controllers;

use App\GradeUser;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Role;
use App\User;
use App\UserDetails;
use App\TeacherAppointmentSlots;
use App\AppointmentRequest;
use Illuminate\Validation\Rules\In;


class AppointmentController extends Controller
{
    public function getAppointmentDetails()
    {
        $teacherUsers = User::all()->where('role_id', 4);
        $i = 0;
        foreach ($teacherUsers as $teacherUser) {
            $teacherId = $teacherUser->id;
            $teacherDetails = UserDetails::where('user_id', $teacherId)->first();
            $teacherName = $teacherDetails->name;
            $teacherData[$i++] = array(
                'id' => $teacherId,
                'name' => $teacherName
            );
        }
        //var_dump($teacherData);
        return view('appointments.teacherAppointmentDetails', compact('teacherData'));
    }


    public function showAppointmentDetails(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of admin is 1
        if ($user->role_id == 1) {
            $rules = array(
                'teacherName' => 'required'
            );
            $this->validate($request, $rules);
            $teacherName = Input::get('teacherName');
            $teacherDetails = UserDetails::where('name', $teacherName)->first();
            $teacherId = $teacherDetails->user_id;
            $teacherAppointmentDetails = TeacherAppointmentSlots::where('teacher_id', $teacherId)->first();
            $teacherAppointmentData = array(
                'id' => $teacherId,
                'name' => $teacherName,
                'date' => $teacherAppointmentDetails->date,
                'day' => $teacherAppointmentDetails->day,
                'duration' => $teacherAppointmentDetails->duration,
                'booked' => $teacherAppointmentDetails->isBooked
            );
            // var_dump($teacherAppointmentData);
            return Response::json([$teacherAppointmentData, HttpResponse::HTTP_OK]);
        } else {
            return Response::json(["Permission Denied", HttpResponse::HTTP_UNAUTHORIZED]);
        }
    }
    
    public function getTeacherAppointments(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of teacher is 4
        if ($user->role_id == 4) {
            $teacherDetails = UserDetails::where('user_id', $id)->first();
            $teacherId = $teacherDetails->user_id;
            $teacherAppointmentDetails = TeacherAppointmentSlots::all()->where('teacher_id', $teacherId);
            $i=0;
            foreach ($teacherAppointmentDetails as $teacherAppointmentDetail){
                $teacherAppointmentData[$i++] = array(
                    'id'=>$teacherAppointmentDetail->id,
                    'date' => $teacherAppointmentDetail->date,
                    'day' => $teacherAppointmentDetail->day,
                    'duration' => $teacherAppointmentDetail->duration,
                    'booked' => $teacherAppointmentDetail->isBooked
                );
            }
            //var_dump($teacherAppointmentData);
           return view('appointments.showAppointments',compact('teacherAppointmentData'));
        }
        else{
              return Response::json(["Permission Denied", HttpResponse::HTTP_UNAUTHORIZED]);
            
        }

    }
    public function confirmAppointments(Request $request){
        $appointmentId = Input::get("appointmentId");
        $appointmentDetails = TeacherAppointmentSlots::where('id',$appointmentId)->first();
        $booked = $appointmentDetails->isBooked;
        if($booked==0){
            \DB::table('teacherAppointmentsSlots')
                ->where('id', $appointmentId)
                ->update(['isBooked' => 1]);
            $appointmentDetails = TeacherAppointmentSlots::where('id',$appointmentId)->first();

            $teacherAppointmentData = array(
                'id'=>$appointmentDetails->id,
                'date' => $appointmentDetails->date,
                'day' => $appointmentDetails->day,
                'duration' => $appointmentDetails->duration,
                'booked' => $appointmentDetails->isBooked
            );

        }
        return Response::json([$teacherAppointmentData, HttpResponse::HTTP_OK]);


    }
    
    public function getAppointmentsSlots(Request $request){
        
        $teacherUsers = User::all()->where('role_id', 4);
        $i = 0;
        foreach ($teacherUsers as $teacherUser) {
            $teacherId = $teacherUser->id;
            $teacherDetails = UserDetails::where('user_id', $teacherId)->first();
            $teacherName = $teacherDetails->name;
            $teacherData[$i++] = array(
                'id' => $teacherId,
                'name' => $teacherName
            );
        }

        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        $durations = array(15,30,45,60);
        //var_dump($teacherData);
        return view('appointments.insertAppointmentsSlots', compact('teacherData','days','durations'));
    }
    
    public function postAppointmentsSlots(Request $request){

        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        //role_id of admin is 1
        if ($user->role_id == 1) {
            $rules = array(
                'teacherId' => 'required',
                'duration' =>'required',
                'day' => 'required',
                'time' =>'required'
            );
            $this->validate($request, $rules);
            $teacherId = Input::get('teacherId');
            $duration = Input::get('duration');
            $day = Input::get('day');
            $time = Input::get('time');
            $userDetails = UserDetails::where('user_id', $teacherId)->first();
            $teacherName = $userDetails->name;
            $appointmentData = array(
                'teacherId' => $teacherId,
                'duration' =>$duration,
                'day' =>$day,
                'time' => $time

            );

            try {
                \DB::beginTransaction();
                \DB::table('teacherAppointmentsSlots')->insert(['teacher_id' => $teacherId, 'duration' => $duration, 'day' => $day,'startTime'=>$time,'isBooked'=>0]);

            }catch (Exception $e){
                \DB::rollBack();
                return Response::json(HttpResponse::HTTP_PARTIAL_CONTENT);
            }
            \DB::commit();
            return redirect('insertAppointmentsSlots')->with('status', 'Slots inserted!');

        } else {
            return Response::json(["Permission Denied", HttpResponse::HTTP_UNAUTHORIZED]);
        }

        
    }

    public function getTeacherDetails(Request $request){
        try {
            $token = $request->get('token');
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $studentDetails= Student::where('parent_id',$userId)->first();
        $gradeId = $studentDetails->grade_id;
        $teachers = GradeUser::all()->where('grade_id',$gradeId);
        $j=0;
        global $appointmentData;
        foreach ($teachers as $teacher){
            $teacherId = $teacher->user_id;
            $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
            $teacherName = $teacherDetails->name;
            $appointmentDetails = \DB::table('teacherAppointmentsSlots')->where([
                    ['teacher_id',$teacherId],
                    ['isBooked',0]
                ])->get();
            $i=0;
            foreach ($appointmentDetails as $appointmentDetail){
                $day = $appointmentDetail->day;
                $time = $appointmentDetail->startTime;
                $duration = $appointmentDetail->duration;
                $appointmentData[$i++] = array(
                    'day' => $day,
                    'time'=>$time,
                    'duration'=>$duration

                );
            }
            $sendData[$j++] = array(
                'teacherId'=>$teacherId,
                'teacherName'=>$teacherName,
                'appointmentData'=>$appointmentData
            );
        }
        return Response::json([$sendData, HttpResponse::HTTP_OK]);
    }

}
