<?php

namespace App\Http\Controllers;

use App\Grade;
use App\GradeUser;
use App\Student;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Mockery\CountValidator\Exception;
use Psy\Test\Exception\RuntimeExceptionTest;
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
     * Display a listing of appointments.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        //Retrieve all the appointment requests of the logged in teacher
        $appointmentRequests = AppointmentRequest::where('teacher_id',$id)
            ->orderBy('requestType')
            ->orderBy('id','desc')
            ->get();
        $i=0;
        $appointmentDetails = array();
        foreach ($appointmentRequests as $appointmentRequest){
            $appointmentRequestId=$appointmentRequest->id;
            $appointmentDetails[$i++]=$this->getAppointments($appointmentRequestId);
        }
        return view('appointments.index', compact('appointmentDetails'),$data);
    }

    //Retrieve appointment details corresponding to an appointment request id
    public function getAppointments ($id){
        $appointmentRequest = AppointmentRequest::where('id',$id)->first();
        $appointmentRequestId=$id;
        $parentId = $appointmentRequest->parent_id;
        //Retrieve parent and student details  corresponding to the appointment request
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $parentName = $parentDetails->name;
        $parentContact = $appointmentRequest->parentContact;
        $studentDetails = Student::where('parent_id',$parentId)->first();
        $studentId = $studentDetails->id;
        $studentName = $studentDetails->name;
        $gradeId = $studentDetails->grade_id;
        $gradeDetails = Grade::where('id',$gradeId)->first();
        $gradeName = $gradeDetails->grade_name;
        $reasonOfAppointment = $appointmentRequest->reasonOfAppointment;
        $cancellationReason = $appointmentRequest->cancellationReason;
        $requestType = $appointmentRequest->requestType;
        if($requestType == "Parent Request")
            $requestedBy = "Parent";
        else
            $requestedBy = "You";
        //Retrieve appointment slot corresponding to the appointment request
        $slotId=$appointmentRequest->teacherAppointmentsSlot_id;
        $slot = TeacherAppointmentSlots::where('id',$slotId)->first();
        $booked= $slot->isBooked;
        $awaited = $appointmentRequest->isAwaited;
        $confirmed = $appointmentRequest->isApproved;
        $cancelled = $appointmentRequest->isCancel;
        if ($awaited==1 && $confirmed==0 && $cancelled==0){
            $status = "Awaited";
        }
        elseif ($awaited==0 && $confirmed==1 && $cancelled==0){
            $status = "Confirmed";
        }
        elseif($awaited==0 && $confirmed==0 && $cancelled==1) {
            $status="Cancelled";
        }
        else{
            $status = "Invalid Status";
        }
        //Retrieve appointment event corresponding to the appointment slot
        $eventId = $slot->calendarEventsId;
        $event = CalendarEvent::where('id',$eventId)->first();
        $title=$event->title;
        $start=$event->start;
        $end=$event->end;
        $teacherId = $appointmentRequest->teacher_id;
        $contactNo = $appointmentRequest->contactNo;
        $appointmentDetails= array(
            'requestId' => $appointmentRequestId,
            'parentName'=>$parentName,
            'parentContact'=>$parentContact,
            'studentId'=>$studentId,
            'studentName'=>$studentName,
            'grade'=>$gradeName,
            'eventId'=>$eventId,
            'title'=>$title,
            'reasonOfAppointment'=>$reasonOfAppointment,
            'cancellationReason'=>$cancellationReason,
            'start'=>$start,
            'end'=>$end,
            'requestedBy'=>$requestedBy,
            'status'=>$status,
            'contact'=>$contactNo,
            'teacherId'=>$teacherId
        );
        return $appointmentDetails;
    }
    /**
     * Display a specific appointment
     *
     * @param  int $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentDetails = $this->getAppointments($id);

        return view('appointments.show', compact('appointmentDetails'),$data);
    }
    /*Confirm appointments*/
    public function getConfirm($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentDetails = $this->getAppointments($id);
        return view('appointments.confirm',compact('appointmentDetails'),$data);

    }
    public function postConfirm($id){
        $appointmentDetail = AppointmentRequest::where('id',$id)->first();
        $contactNo = $appointmentDetail->contactNo;
        $parentId = $appointmentDetail->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
        $slotId = $appointmentDetail->teacherAppointmentsSlot_id;
        $slot = TeacherAppointmentSlots::where('id',$slotId)->first();
        $teacherId = $slot->teacher_id;
        $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
        $teacherName = $teacherDetails->name;
        $eventId = $slot->calendarEventsId;
        $event = CalendarEvent::where('id',$eventId)->first();
        $start = Carbon::parse($event->start);
        $startDate = $start->toDateString();
        $startTime = $start->toTimeString();
        $end = Carbon::parse($event->end);
        $endTime = $end->toTimeString();
        $message = array("message"=>"Your appointment with $teacherName on $startDate from $startTime to $endTime has been confirmed.
        Whatsapp Video Call Number : $contactNo","eventId"=>$eventId);
        $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
        try {
            DB::beginTransaction();
            DB::table('appointmentRequests')
                ->where('id', $id)
                ->update(
                    ['isApproved' => 1,
                    'isCancel' => 0,
                    'isAwaited' => 0]);
        }catch (Exception $e){
            DB::rollback();
            return Response::json("Insertion failed",HttpResponse::HTTP_PARTIAL_CONTENT);
        }
        DB::commit();
        return redirect(route('appointments.index'));

    }
    //Change contact no of teacher in case of different whatsapp number
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

    /*Cancel appointments*/
    public function getCancel($id, Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentDetails = $this->getAppointments($id);
        return view('appointments.cancel',compact('appointmentDetails'),$data);
    }

    public function postCancel(Request $request,$id){
        $appointmentDetail = AppointmentRequest::where('id',$id)->first();
        $slotId = $appointmentDetail->teacherAppointmentsSlot_id;
        $parentId = $appointmentDetail->parent_id;
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
        $teacherId = $appointmentDetail->teacher_id;
        $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
        $teacherName = $teacherDetails->name;
        $rules = array(
            'cancellationReason' => 'required'
        );
        $this->validate($request,$rules);
        $cancellationReason = Input::get('cancellationReason');
        $slotDetails = TeacherAppointmentSlots::where('id',$slotId)->first();
        $calendarEventId = $slotDetails->calendarEventsId;
        $calendarEventDetails = CalendarEvent::where('id',$calendarEventId)->first();
        $start = $calendarEventDetails->start;
        $start=Carbon::parse($start);
        $startDate = $start->toDateString();
        $startTime = $start->toTimeString();
        $end = $calendarEventDetails->end;
        $end = Carbon::parse($end);
        $endTime = $end->toTimeString();
        $message = array("message"=>"Your appointment with $teacherName on $startDate from $startTime to $endTime
         has been cancelled due to $cancellationReason","eventId"=>$calendarEventId);
        $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
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
    /**
     * Show the form for requesting appointment from teacher side
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        $userDetails = UserDetails::where('user_id',$id)->first();
        $contactNo = $userDetails->contact;

        $parentUsers = User::all()->where('role_id', 2);
        $i = 0;
        foreach ($parentUsers as $parentUser) {
            $parentId = $parentUser->id;
            $parentDetails = UserDetails::where('user_id', $parentId)->first();
            $parentName = $parentDetails->name;
            $parentData[$i++] = array(
                'id' => $parentId,
                'name' => $parentName
            );
        }
        return view('appointments.create',compact('parentData','contactNo'),$data);
    }


    /**
     * Store the appointment request by teacher
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $rules = array(
            'parentId' => 'required',
            'startDate' => 'required|date|after:today',
            'startTime'=>'required|date_format:H:i',
            //'endDate'=>'required|date|after_or_equal:startDate',
            'endTime'=>'required|after:startTime|date_format:H:i',
            'appointmentReason'=>'required',
            'contactNo'=>'required|digits:10',
        );
        $this->validate($request,$rules);
        $teacherDetails = UserDetails::where('user_id',$id)->first();
        $teacherName = $teacherDetails->name;
        $parentId = Input::get('parentId');
        $parentDetails = UserDetails::where('user_id',$parentId)->first();
        $gcmRegistrationId[0] = $parentDetails->gcmRegistrationId;
//        $title = Input::get('title');
        $appointmentReason = Input::get('appointmentReason');
        $contactNo = Input::get("contactNo");
        $startDate = Input::get('startDate');
        $startDate = Carbon::parse($startDate);
        $endDate = $startDate;
        $endDate = Carbon::parse($endDate);
        $startTime =Input::get('startTime');
        $startTime = Carbon::parse($startTime);
        $hours = (double)$startTime->format('H');
        $minutes = (double)$startTime->format('i');
        $seconds = (double)$startTime->format('s');
        $startDateTime = date_time_set($startDate,$hours,$minutes,$seconds);
        //$endDate = Carbon::parse(Input::get('endDate'));
        $endTime =Input::get("endTime");
        $endTime = Carbon::parse($endTime);
        $hours = (double)$endTime->format('H');
        $minutes = (double)$endTime->format('i');
        $seconds = (double)$endTime->format('s');
        $endDateTime = date_time_set($endDate,$hours,$minutes,$seconds);
        $teacherSlots = TeacherAppointmentSlots::all()->where('teacher_id',$id);
        $flag = 0;
        foreach ($teacherSlots as $teacherSlot){
            $slotId = $teacherSlot->id;
            $calendarEventId = $teacherSlot->calendarEventsId;
            $calendarEvent = CalendarEvent::where('id',$calendarEventId)->first();
            $slotStart = $calendarEvent->start;
            $slotEnd = $calendarEvent->end;
            //If requested time is clashing with the existing slots
            if(($startDateTime==$slotStart)|| ($endDateTime==$slotEnd)||
               ($startDateTime>$slotStart && $startDateTime<$slotEnd)||
               ($endDateTime<$slotEnd && $endDateTime>$slotStart)){
                //If the existing slot is not booked
                if($teacherSlot->isBooked == 0){
                    $flag=1;
                    try{
                        \DB::beginTransaction();
                        \DB::table('teacherAppointmentsSlots')
                            ->where('id', $slotId)
                            ->update(['isBooked' => 1]);
                        $calendarEvent = new CalendarEvent();
                        $calendarEvent->title = "Parent Appointment";
                        $calendarEvent->start = $startDateTime;
                        $calendarEvent->end = $endDateTime;
                        $calendarEvent->eventType = "Parent Appointment";
                        $calendarEvent->save();
                        $slot = new TeacherAppointmentSlots();
                        $slot->teacher_id = $id;
                        $slot->isBooked = 1;
                        $slot->calendarEventsId=$calendarEvent->id;
                        $slot->save();
                        $apppointmentRequest = new AppointmentRequest();
                        $apppointmentRequest->parent_id = $parentId;
                        $apppointmentRequest->teacher_id = $id;
                        $apppointmentRequest->teacherAppointmentsSlot_id = $slot->id;
                        $apppointmentRequest->reasonOfAppointment = $appointmentReason;
                        $apppointmentRequest->contactNo = $contactNo;
                        $apppointmentRequest->isAwaited = 1;
                        $apppointmentRequest->isCancel = 0;
                        $apppointmentRequest->isApproved = 0;
                        $apppointmentRequest->requestType = "Teacher Request";
                        $apppointmentRequest->save();
                        $message = array("message"=>"Request of Appointment by $teacherName on $startDate /n from $startTime to $endTime.
                        Whatsapp Video Call Number : $contactNo","eventId"=> $calendarEvent->id);
                        $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
                    }catch (Exception $e){
                        \DB::rollback();
                        return redirect(route('appointments.index'))->with('failure', 'Could not send appointment request. Please try again');
                    }
                    \DB::commit();
                }
                //If existing slot is booked
                else{
                    return redirect(route('appointments.index'))->with('failure','Slot is already booked');
                }
            }
        }
        if ($flag == 1){
            return redirect(route('appointments.index'))->with('success', 'Appointment Request Sent Successfully');
        }
        //If requested time is not clashing with the existing slots
        else{
            try{
                \DB::beginTransaction();
                $calendarEvent = new CalendarEvent();
                $calendarEvent->title = "Parent Appointment";
                $calendarEvent->start = $startDateTime;
                $calendarEvent->end = $endDateTime;
                $calendarEvent->eventType = "Parent Appointment";
                $calendarEvent->save();
                $slot = new TeacherAppointmentSlots();
                $slot->teacher_id = $id;
                $slot->isBooked = 1;
                $slot->calendarEventsId=$calendarEvent->id;
                $slot->save();
                $apppointmentRequest = new AppointmentRequest();
                $apppointmentRequest->parent_id = $parentId;
                $apppointmentRequest->teacher_id = $id;
                $apppointmentRequest->teacherAppointmentsSlot_id = $slot->id;
                $apppointmentRequest->reasonOfAppointment = $appointmentReason;
                $apppointmentRequest->contactNo = $contactNo;
                $apppointmentRequest->isAwaited = 1;
                $apppointmentRequest->isCancel = 0;
                $apppointmentRequest->isApproved = 0;
                $apppointmentRequest->requestType = "Teacher Request";
                $apppointmentRequest->save();
                $message =array("message"=> "Request of Appointment by $teacherName on $startDate from $startTime to $endTime.
                     Whatsapp Video Call Number : $contactNo","eventId"=> $calendarEvent->id);
                $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
            }catch (Exception $e){
                \DB::rollback();
                return redirect(route('appointments.index'))->with('failure', 'Could not send appointment request. Please try again');
            }
            \DB::commit();
            return redirect(route('appointments.index'))->with('success', 'Appointment Request Sent Successfully');
        }

    }
    //Show free slots of the logged in teacher
    public function showFreeSlots($id,Request $request){

        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $i = 0;
        $calendarEvent = CalendarEvent::where('id',$id)->first();
        $title = $calendarEvent->title;
        $startDateTime = $calendarEvent->start;
        $startDateTime= Carbon::parse($startDateTime);
        $endDateTime = $calendarEvent->end;
        $endDateTime = Carbon::parse($endDateTime);
        $startTime = $startDateTime->toTimeString();
        $endTime = $endDateTime->toTimeString();
        $dayNo= date('w', strtotime($startDateTime));
        global $day;
        switch ($dayNo){
            case 0: $day ="Sunday";
                break;
            case 1: $day = "Monday";
                break;
            case 2: $day = "Tuesday";
                break;
            case 3: $day ="Wednesday";
                break;
            case 4: $day = "Thursday";
                break;
            case 5: $day = "Friday";
                break;
            case 6: $day = "Saturday";
                break;
        }
        $calendar_event=array(
            'id'=> $id,
            'title'=>$title,
            'day'=>$day,
            'startTime'=>$startTime,
            'endTime'=>$endTime
        );

        return view('appointments.showFreeSlots', compact('calendar_event'),$data);
    }
    /*API for sending free slot details of teachers for 7 days (after today) in reference to a particular grade and
    details of all the appointments requested by particular parent*/
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
        $freeSlots=array();
        $i=0;
        //Send free slots
        while ($dateCount!=7){
            $studentDetails= Student::where('parent_id',$userId)->first();
            $gradeId = $studentDetails->grade_id;
            $calendarEvents = \DB::table('grade_user')
                ->join('teacherAppointmentsSlots','grade_user.user_id','=','teacherAppointmentsSlots.teacher_id')
                ->join('calendar_events','teacherAppointmentsSlots.calendarEventsId','calendar_events.id')
                ->where('teacherAppointmentsSlots.isBooked',0)
                ->where('grade_user.grade_id',$gradeId)
                ->where('calendar_events.eventType','Teacher Appointment')
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
                $freeSlots[$i++] = array(
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
        $appointmentData=array();
        $j=0;
        $appointmentRequests = AppointmentRequest::all()
            ->where('parent_id',$userId);
        //Send details of appointments
        foreach ($appointmentRequests as $appointmentRequest){
            $slotId = $appointmentRequest->teacherAppointmentsSlot_id;
            $teacher_id = $appointmentRequest->teacher_id;
            $teacherDetails = UserDetails::where('user_id',$teacher_id)->first();
            $teacher_name = $teacherDetails->name;
            $slotDetails = TeacherAppointmentSlots::where('id',$slotId)->first();
            $event_id = $slotDetails->calendarEventsId;
            $calendar_event = CalendarEvent::where('id',$event_id)->first();
            $start = $calendar_event->start;
            $startDateTime = Carbon::parse($start);
            $startDate = $startDateTime->toDateString();
            $startTime = $startDateTime->toTimeString();
            $end = $calendar_event->end;
            $endDateTime = Carbon::parse($end);
            $endDate = $startDateTime->toDateString();
            $endTime = $endDateTime->toTimeString();
            $booked = $slotDetails->isBooked;
            $cancelled = $appointmentRequest->isCancel;
            $awaited = $appointmentRequest->isAwaited;
            $confirmed = $appointmentRequest->isApproved;
            $requestType = $appointmentRequest->requestType;
            if ($awaited==1 && $confirmed==0 && $cancelled==0){
                $status = 1;
            }
            elseif ($awaited==0 && $confirmed==1 && $cancelled==0){
                $status = 2;
            }
            elseif($awaited==0 && $confirmed==0 && $cancelled==1) {
                $status=3;
            }
            else{
                $status = 4;
            }
            $appointmentData[$j++]=array(
                'id' => $event_id,
                'teacherId' => $teacher_id,
                'teacherName' => $teacher_name,
                'title' => $status,
                'startDate'=>$startDate,
                'endDate'=>$endDate,
                'startTime' => $startTime,
                'endTime' => $endTime,
                'requestType'=>$requestType
            );
        }
        return Response::json([$freeSlots,$appointmentData, HttpResponse::HTTP_OK]);
    }

    //API to book an appointment
    public function bookAppointments(Request $request){
        try {
            $token = $request->get('token');
            $teacherId = $request->get('teacherId');
            $eventId = $request->get('eventId');
            $reasonOfAppointment = $request->get('reasonOfAppointment');
            $parentContact = $request->get('parentContact');

            $parent = JWTAuth::toUser($token);
            $parentId= $parent->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }
        catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }

        $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
        $contactNo = $teacherDetails->contact;
        $teacherSlot = \DB::table('teacherAppointmentsSlots')
            ->where('teacher_id',$teacherId)
            ->where('calendarEventsId',$eventId)
            ->first();
        $slotId = $teacherSlot->id;
        try {
            \DB::beginTransaction();
            \DB::table('teacherAppointmentsSlots')
                ->where('id', $slotId)
                ->update(['isBooked' => 1]);
            \DB::table('calendar_events')
                ->where('id', $eventId)
                ->update(['eventType' => 'Teacher Appointment']);
            $appointmentRequest = new AppointmentRequest();
            $appointmentRequest->parent_id = $parentId;
            $appointmentRequest->teacher_id = $teacherId;
            $appointmentRequest->teacherAppointmentsSlot_id = $slotId;
            $appointmentRequest->reasonOfAppointment= $reasonOfAppointment;
            $appointmentRequest->isAwaited=1;
            $appointmentRequest->isApproved=0;
            $appointmentRequest->isCancel=0;
            $appointmentRequest->contactNo=$contactNo;
            $appointmentRequest->parentContact = $parentContact;
            $appointmentRequest->requestType ="Parent Request";
            $appointmentRequest->save();
        }catch (Exception $e){
            \DB::rollBack();
            $httpStatus = HttpResponse::HTTP_PARTIAL_CONTENT;
            return Response::json(["Partial Content",$httpStatus]);
        }
        \DB::commit();
        $httpStatus = HttpResponse::HTTP_OK;
        return Response::json(["Success",$httpStatus]);
    }
    //API to update the status of appointment event : confirmed or cancelled
    public function updateEvent(Request $request){
        try {
            $token = $request->get('token');
            $user = JWTAuth::toUser($token);
            $userId = $user->id;
        }catch (TokenExpiredException $e){
            return Response::json (['Token expired'],498);
        }catch (TokenInvalidException $e){
            return Response::json (['Token invalid']);
        }
        $eventId = $request->get('eventId');
        $status = $request->get("status");

        $appointmentSlot = TeacherAppointmentSlots::where('calendarEventsId',$eventId)->first();
        $appointmentSlotId = $appointmentSlot->id;
        $appointmentRequest = AppointmentRequest::where('teacherAppointmentsSlot_id',$appointmentSlotId)->first();
        $appointmentRequestId = $appointmentRequest->id;
        //confirmed
        if($status ==2){
            try {
                DB::beginTransaction();
                DB::table('appointmentRequests')
                    ->where('id', $appointmentRequestId)
                    ->update(
                        ['isApproved' => 1,
                            'isCancel' => 0,
                            'isAwaited' => 0]);
            }catch (Exception $e){
                DB::rollback();
                $httpStatus = HttpResponse::HTTP_CONFLICT;
                return Response::json([$httpStatus]);
            }
            DB::commit();
            $httpStatus = HttpResponse::HTTP_OK;
            return Response::json([$httpStatus]);
        }
        //cancelled
        elseif ($status==3){
            $cancellationReason = $request->get('cancellationReason');
            try {
                DB::beginTransaction();
                DB::table('appointmentRequests')
                    ->where('id', $appointmentRequestId)
                    ->update([
                        'isApproved' => 0,
                        'isCancel' => 1,
                        'isAwaited' => 0,
                        'cancellationReason'=>$cancellationReason
                    ]);
                DB::table('teacherAppointmentsSlots')
                    ->where('id', $appointmentSlotId)
                    ->update(['isBooked' => 0]);

            }catch (Exception $e){
                DB::rollback();
                $httpStatus = HttpResponse::HTTP_CONFLICT;
                return Response::json(["Conflict",$httpStatus]);
            }
            DB::commit();
            $httpStatus = HttpResponse::HTTP_OK;
            return Response::json(["Success",$httpStatus]);
        }
        else{
            return Response::json(["invalid status"]);
        }
    }


//    public function sendEvent(Request $request){
//        try {
//            $token = $request->get('token');;
//            $user = JWTAuth::toUser($token);
//            $userId = $user->id;
//        }catch (TokenExpiredException $e){
//            return Response::json (['Token expired'],498);
//        }catch (TokenInvalidException $e){
//            return Response::json (['Token invalid']);
//        }
//        $eventId = $request->get('eventId');
//        $appointmentSlot = TeacherAppointmentSlots::where('calendarEventsId',$eventId)->first();
//        $appointmentSlotId = $appointmentSlot->id;
//        $appointmentRequest = AppointmentRequest::where('teacherAppointmentsSlot_id',$appointmentSlotId)->first();
//        $teacher_id = $appointmentRequest->teacher_id;
//        $teacherDetails = UserDetails::where('user_id',$teacher_id)->first();
//        $teacher_name = $teacherDetails->name();
//        $teacherContact = $appointmentRequest->contactNo;
//        $booked = $appointmentSlot->isBooked;
//        $cancelled = $appointmentRequest->isCancel;
//        $awaited = $appointmentRequest->isAwaited;
//        $confirmed = $appointmentRequest->isApproved;
//        if ($awaited==1 && $confirmed==0 && $cancelled==0){
//           //awaited
//            $status = 1;
//        }
//        elseif ($awaited==0 && $confirmed==1 && $cancelled==0){
//            //confirmed
//            $status = 2;
//        }
//        elseif($awaited==0 && $confirmed==0 && $cancelled==1) {
//            //cancelled
//            $status=3;
//        }
//        else{
//            //invalid
//            $status = 4;
//        }
//        $event = CalendarEvent::where('id',$eventId)->first();
//        $start = $event->start;
//        $start = Carbon::parse($start);
//        $startDate = $start->toDateString();
//        $startTime = $start->toTimeString();
//        $end = $event->end;
//        $end=Carbon::parse($end);
//        $endDate = $end->toDateString();
//        $endTime = $end->toTimeString();
//        $requestType = $appointmentRequest->requestType;
//        if($status == 3)
//            $reason = $appointmentRequest->cancellationReason;
//        else
//            $reason = $appointmentRequest->reasonOfAppointment;
//        $appointmentDetails = array(
//            'id' => $eventId,
//            'teacherId' => $teacher_id,
//            'teacherName' => $teacher_name,
//            'title' => $status,
//            'startDate'=>$startDate,
//            'endDate'=>$endDate,
//            'startTime' => $startTime,
//            'endTime' => $endTime,
//            'requestType'=>$requestType,
//            'contact'=>$teacherContact,
//            'reason'=>$reason
//        );
//        return Response::json([$appointmentDetails,HttpResponse::HTTP_OK]);
//    }



}
