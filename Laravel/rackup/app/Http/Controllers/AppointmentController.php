<?php

namespace App\Http\Controllers;

use App\Grade;
use App\GradeUser;
use App\Student;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Mockery\CountValidator\Exception;
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
use App\CalendarEvent;
use Tymon\JWTAuth\Providers\Storage\StorageInterface;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $appointmentRequests = AppointmentRequest::all()->where('teacher_id',$id);
        $i=0;
        $appointmentDetails = array();
        foreach ($appointmentRequests as $appointmentRequest){
            $appointmentRequestId=$appointmentRequest->id;
            $slotId=$appointmentRequest->teacherAppointmentsSlot_id;
            $parentId = $appointmentRequest->parent_id;
            $parentDetails = UserDetails::where('user_id',$parentId)->first();
            $parentName = $parentDetails->name;
            $studentDetails = Student::where('parent_id',$parentId)->first();
            $studentId = $studentDetails->id;
            $studentName = $studentDetails->name;
            $gradeId = $studentDetails->grade_id;
            $gradeDetails = Grade::where('id',$gradeId)->first();
            $gradeName = $gradeDetails->grade_name;
            $reasonOfAppointment = $appointmentRequest->reasonOfAppointment;
            $cancellationReason = $appointmentRequest->cancellationReason;
            $slot = TeacherAppointmentSlots::where('id',$slotId)->first();
            $booked= $slot->isBooked;
            $awaited = $appointmentRequest->isAwaited;
            $confirmed = $appointmentRequest->isApproved;
            $cancelled = $appointmentRequest->isCancel;
            if ($booked==0 && $awaited==1 && $confirmed==0 && $cancelled==0){
                $status = "Awaited";
            }
            elseif ($booked==1 && $awaited==0 && $confirmed==1 && $cancelled==0){
                $status = "Confirmed";
            }
            elseif($booked==0 && $awaited==0 && $confirmed==0 && $cancelled==1) {
                $status="Cancelled";
            }
            else{
                $status = "Invalid Status";
            }
            $eventId = $slot->calendarEventsId;
            $event = CalendarEvent::where('id',$eventId)->first();
            $appointmentDetails[$i++] = array(
                'requestId' => $appointmentRequestId,
                'parentName'=>$parentName,
                'studentId'=>$studentId,
                'studentName'=>$studentName,
                'grade'=>$gradeName,
                'eventId'=>$eventId,
                'title'=>$event->title,
                'reasonOfAppointment'=>$reasonOfAppointment,
                'cancellationReason'=>$cancellationReason,
                'start'=>$event->start,
                'end'=>$event->end,
                'status'=>$status
            );
        }

        return view('appointments.index', compact('appointmentDetails'),$data);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id,Request $request)
    {

        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentRequest = AppointmentRequest::where('id',$id)->first();
        $appointmentRequestId=$appointmentRequest->id;
        $parentId = $appointmentRequest->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $parentName = $parentDetails->name;
        $studentDetails = Student::where('parent_id',$parentId)->first();
        $studentId = $studentDetails->id;
        $studentName = $studentDetails->name;
        $gradeId = $studentDetails->grade_id;
        $gradeDetails = Grade::where('id',$gradeId)->first();
        $gradeName = $gradeDetails->grade_name;
        $reasonOfAppointment = $appointmentRequest->reasonOfAppointment;
        $cancellationReason = $appointmentRequest->cancellationReason;
        $slotId=$appointmentRequest->teacherAppointmentsSlot_id;
        $slot = TeacherAppointmentSlots::where('id',$slotId)->first();
        $booked= $slot->isBooked;
        $awaited = $appointmentRequest->isAwaited;
        $confirmed = $appointmentRequest->isApproved;
        $cancelled = $appointmentRequest->isCancel;
        if ($booked==0 && $awaited==1 && $confirmed==0 && $cancelled==0){
            $status = "Awaited";
        }
        elseif ($booked==1 && $awaited==0 && $confirmed==1 && $cancelled==0){
            $status = "Confirmed";
        }
        elseif($booked==0 && $awaited==0 && $confirmed==0 && $cancelled==1) {
            $status="Cancelled";
        }
        else{
            $status = "Invalid Status";
        }
        $eventId = $slot->calendarEventsId;
        $event = CalendarEvent::where('id',$eventId)->first();
        $title=$event->title;
        $start=$event->start;
        $end=$event->end;
        $appointmentDetails= array(
            'requestId' => $appointmentRequestId,
            'parentName'=>$parentName,
            'studentId'=>$studentId,
            'studentName'=>$studentName,
            'grade'=>$gradeName,
            'eventId'=>$eventId,
            'title'=>$title,
            'reasonOfAppointment'=>$reasonOfAppointment,
            'cancellationReason'=>$cancellationReason,
            'start'=>$start,
            'end'=>$end,
            'status'=>$status
        );

        return view('appointments.show', compact('appointmentDetails'),$data);
    }
    /*Confirm appointments*/
    public function getConfirm($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentRequest = AppointmentRequest::where('id',$id)->first();
        $appointmentRequestId=$appointmentRequest->id;
        $parentId = $appointmentRequest->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $parentName = $parentDetails->name;
        $studentDetails = Student::where('parent_id',$parentId)->first();
        $studentId = $studentDetails->id;
        $studentName = $studentDetails->name;
        $gradeId = $studentDetails->grade_id;
        $gradeDetails = Grade::where('id',$gradeId)->first();
        $gradeName = $gradeDetails->grade_name;
        $reasonOfAppointment = $appointmentRequest->reasonOfAppointment;
        $slotId=$appointmentRequest->teacherAppointmentsSlot_id;
        $slot = TeacherAppointmentSlots::where('id',$slotId)->first();
        $eventId = $slot->calendarEventsId;
        $event = CalendarEvent::where('id',$eventId)->first();
        $title=$event->title;
        $start=$event->start;
        $end=$event->end;
        $teacherId = $appointmentRequest->teacher_id;
        $teacherDetail = UserDetails::where('user_id',$teacherId)->first();
        $contactNo = $teacherDetail->contact;
        $message = "A whatsapp video call has been scheduled on the number ".$contactNo;
        $appointmentDetails= array(
            'requestId' => $appointmentRequestId,
            'parentName'=>$parentName,
            'studentId'=>$studentId,
            'studentName'=>$studentName,
            'grade'=>$gradeName,
            'eventId'=>$eventId,
            'title'=>$title,
            'reasonOfAppointment'=>$reasonOfAppointment,
            'start'=>$start,
            'end'=>$end,
            'contact'=>$contactNo,
            'message'=>$message,
            'teacherId'=>$teacherId
        );
        //return var_export($appointmentDetails);
        return view('appointments.confirm',compact('appointmentDetails'),$data);

    }

    public function changeContactNumber($id){
        $contactNo = Input::get('contact');
        try {
            DB::beginTransaction();
           $request= DB::table('appointmentRequests')
                ->where('id', $id)
                ->update(['contactNo' => $contactNo]);
        }catch (Exception $e){
            DB::rollBack();
            return Response::json("Insertion failed",HttpResponse::HTTP_PARTIAL_CONTENT);
        }
        DB::commit();
        return Response::json($contactNo,HttpResponse::HTTP_OK);
    }

    public function postConfirm($id){
        $appointmentDetail = AppointmentRequest::where('id',$id)->first();
        $slotId = $appointmentDetail->teacherAppointmentsSlot_id;
        try {
            DB::beginTransaction();
            DB::table('appointmentRequests')
                ->where('id', $id)
                ->update(
                    ['isApproved' => 1,
                    'isCancel' => 0,
                    'isAwaited' => 0]);
            DB::table('teacherAppointmentsSlots')
                ->where('id', $slotId)
                ->update(['isBooked' => 1]);
        }catch (Exception $e){
            DB::rollback();
            return Response::json("Insertion failed",HttpResponse::HTTP_PARTIAL_CONTENT);
        }
        DB::commit();
        return redirect(route('appointments.index'));

    }
    /*Cancel appointments*/
    public function getCancel($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentRequest = AppointmentRequest::where('id',$id)->first();
        $appointmentRequestId=$appointmentRequest->id;
        $parentId = $appointmentRequest->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $parentName = $parentDetails->name;
        $studentDetails = Student::where('parent_id',$parentId)->first();
        $studentId = $studentDetails->id;
        $studentName = $studentDetails->name;
        $gradeId = $studentDetails->grade_id;
        $gradeDetails = Grade::where('id',$gradeId)->first();
        $gradeName = $gradeDetails->grade_name;
        $slotId=$appointmentRequest->teacherAppointmentsSlot_id;
        $slot = TeacherAppointmentSlots::where('id',$slotId)->first();
        $eventId = $slot->calendarEventsId;
        $event = CalendarEvent::where('id',$eventId)->first();
        $title=$event->title;
        $start=$event->start;
        $end=$event->end;
        $appointmentDetails= array(
            'requestId' => $appointmentRequestId,
            'parentName'=>$parentName,
            'studentId'=>$studentId,
            'studentName'=>$studentName,
            'grade'=>$gradeName,
            'eventId'=>$eventId,
            'title'=>$title,
            'start'=>$start,
            'end'=>$end,
        );
        //return var_export($appointmentDetails);
        return view('appointments.cancel',compact('appointmentDetails'),$data);
    }
    public function postCancel($id){
        $appointmentDetail = AppointmentRequest::where('id',$id)->first();
        $slotId = $appointmentDetail->teacherAppointmentsSlot_id;
        $cancellationReason = Input::get('cancellationReason');
        try {
            DB::beginTransaction();
            DB::table('appointmentRequests')
                ->where('id', $id)
                ->update([
                        'cancellationReason'=>$cancellationReason,
                        'isApproved' => 0,
                        'isCancel' => 1,
                        'isAwaited' => 0
                ]);
            DB::table('teacherAppointmentsSlots')
                ->where('id', $slotId)
                ->update(['isBooked' => 0]);
        }catch (Exception $e){
            DB::rollback();
            return Response::json("Insertion failed",HttpResponse::HTTP_PARTIAL_CONTENT);
        }
        DB::commit();
        return redirect(route('appointments.index'));

    }
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return Response
//     */
//    public function create()
//    {
//        return view('appointments.create');
//    }
//
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param Request $request
//     * @return Response
//     */
//    public function store(Request $request)
//    {
//        $calendar_event = new CalendarEvent();
//
//        $calendar_event->title            = $request->input("title");
//        $calendar_event->start            = $request->input("start");
//        $calendar_event->end              = $request->input("end");
//
//        $calendar_event->save();
//
////        \DB::table('calendar_events')->insert(
////            ['title' => $calendar_event->title,
////                'start' => $calendar_event->start,
////                'end'=>$calendar_event->end,
////                'is_all_day'=>$calendar_event->is_all_day,
////                'background_color'=>$calendar_event->background_color
////            ]
////        );
//
//        return redirect(route('appointments.index'))->with('message', 'Item created successfully.');
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int $id
//     * @return Response
//     */
//    public function edit($id)
//    {
//        $calendar_event = CalendarEvent::findOrFail($id);
//
//        return view('calendar_events.edit', compact('calendar_event'));
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  int    $id
//     * @param Request $request
//     * @return Response
//     */
//    public function update(Request $request, $id)
//    {
//        $calendar_event = CalendarEvent::findOrFail($id);
//
//        $calendar_event->title            = $request->input("title");
//        $calendar_event->start            = $request->input("start");
//        $calendar_event->end              = $request->input("end");
//        $calendar_event->save();
//
////        \DB::table('calendar_events')
////            ->where('id',$id)
////            ->update(
////            ['title' => $calendar_event->title,
////                'start' => $calendar_event->start,
////                'end'=>$calendar_event->end,
////                'is_all_day'=>$calendar_event->is_all_day,
////                'background_color'=>$calendar_event->background_color
////            ]
////        );
//
//        return redirect(route('calendar_events.index'))->with('message', 'Item updated successfully.');
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int $id
//     * @return Response
//     */
//    public function destroy($id)
//    {
//        $calendar_event = CalendarEvent::findOrFail($id);
//        $calendar_event->delete();
//
//        return redirect(route('calendar_events.index'))->with('message', 'Item deleted successfully.');
//    }
//
//    public function showAppointmentDetails(Request $request)
//    {
//        $id = $request->session()->get('id');
//        $user = \DB::table('users')->whereId($id)->first();
//        //role_id of admin is 1
//        if ($user->role_id == 1) {
//            $rules = array(
//                'teacherName' => 'required'
//            );
//            $this->validate($request, $rules);
//            $teacherName = Input::get('teacherName');
//            $teacherDetails = UserDetails::where('name', $teacherName)->first();
//            $teacherId = $teacherDetails->user_id;
//            $teacherAppointmentDetails = TeacherAppointmentSlots::where('teacher_id', $teacherId)->first();
//            $teacherAppointmentData = array(
//                'id' => $teacherId,
//                'name' => $teacherName,
//                'date' => $teacherAppointmentDetails->dates,
//                'day' => $teacherAppointmentDetails->day,
//                'duration' => $teacherAppointmentDetails->duration,
//                'booked' => $teacherAppointmentDetails->isBooked
//            );
//            // var_dump($teacherAppointmentData);
//            return Response::json([$teacherAppointmentData, HttpResponse::HTTP_OK]);
//        } else {
//            return Response::json(["Permission Denied", HttpResponse::HTTP_UNAUTHORIZED]);
//        }
//    }
    

    //API for sending free slot details of teachers for 7 days (after today) in reference to a particular grade
    public function sendAppointmentSlotDetails(Request $request){
        try {
            $token = $request->get('token');;
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $today = Carbon::today()->addDay();
        $date = $today->toDateString();
        $dateCount=0;
        $calendar_events=array();
        $i=0;
        while ($dateCount!=7){
            $studentDetails= Student::where('parent_id',$userId)->first();
            $gradeId = $studentDetails->grade_id;
            $calendarEvents = \DB::table('grade_user')
                ->join('teacherAppointmentsSlots','grade_user.user_id','=','teacherAppointmentsSlots.teacher_id')
                ->join('calendar_events','teacherAppointmentsSlots.calendarEventsId','calendar_events.id')
                ->where('grade_user.grade_id',$gradeId)
                ->whereDate('calendar_events.start',$date)
                ->get();
            foreach ($calendarEvents as $calendarEvent)
            {
                $calendarEventId = $calendarEvent->id;
                $title = $calendarEvent->title;
                $start = $calendarEvent->start;
                $startDateTime = Carbon::parse($start);
                $startDate = $startDateTime->toDateString();
                $startTime = $startDateTime->toTimeString();
                $end = $calendarEvent->end;
                $endDateTime = Carbon::parse($end);
                $endDate = $startDateTime->toDateString();
                $endTime = $endDateTime->toTimeString();
                $teacherId = $calendarEvent->teacher_id;
                $teacherDetails = UserDetails::where('user_id', $teacherId)->first();
                $teacherName = $teacherDetails->name;
                $calendar_events[$i++] = array(
                    'id' => $calendarEventId,
                    'teacherId' => $teacherId,
                    'teacherName' => $teacherName,
                    'title' => $title,
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                    'startTime' => $startTime,
                    'endTime' => $endTime
                );
            }
            $dateCount++;
            $date++;
        }
        return Response::json([$calendar_events, HttpResponse::HTTP_OK]);
    }

    public function scheduleAppointments(Request $request){
        try {
            $token = $request->get('token');
            $parent = JWTAuth::toUser($token);
            $parentId= $parent->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }
        catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $teacherId = $request->get('teacherId');
        $eventId = $request->get('eventId');
        $reasonOfAppointment = $request->get('reasonOfAppointment');
        $teacherSlots = \DB::table('teacherAppointmentsSlots')
            ->where('teacher_id',$teacherId)
            ->where('calendarEventsId',$eventId)
            ->first();
        $slotId = $teacherSlots->id;
        try {
            \DB::beginTransaction();
            \DB::table('appointmentRequests')->insert([
                'parent_id' => $parentId,
                'teacher_id' => $teacherId,
                'teacherAppointmentsSlot_id'=>$slotId,
                'reasonOfAppointment'=>$reasonOfAppointment,
                'isAwaited'=>1, 
                'isApproved' => 0,
                'isCancel'=>0]);
        }catch (Exception $e){
            \DB::rollBack();
            return Response::json(HttpResponse::HTTP_PARTIAL_CONTENT);
        }
        \DB::commit();
    }
}
