<?php

namespace App\Http\Controllers;

use App\AppointmentRequest;
use App\CalendarEvent;
use App\Grade;
use App\Student;
use App\User;
use App\UserDetails;
use App\TeacherAppointmentSlots;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;

class CalendarEventController extends Controller
{
    //Get the week day from the slot day
    function getDay($slotDay){
        $startDate = Carbon::today();
        $endDate = Carbon::today();
        $dayofweek = date('w', strtotime($startDate));
        $slotDayNo = date('w', strtotime($slotDay));
        $diffDays = $slotDayNo - $dayofweek;
        global $addDay;
        if ($diffDays < 0) {
            switch ($diffDays) {
                case -1 :
                    $addDay = 6;
                    break;
                case -2 :
                    $addDay = 5;
                    break;
                case -3 :
                    $addDay = 4;
                    break;
                case -4 :
                    $addDay = 3;
                    break;
                case -5 :
                    $addDay = 2;
                    break;
            }
        }
        else {
            switch ($diffDays) {
                case 0 :
                    $addDay = 7;
                    break;
                case 1 :
                    $addDay = 1;
                    break;
                case 2 :
                    $addDay = 2;
                    break;
                case 3 :
                    $addDay = 3;
                    break;
                case 4 :
                    $addDay = 4;
                    break;
                case 5 :
                    $addDay = 5;
                    break;
                case 6 :
                    $addDay = 6;
                    break;

            }
        }
        return array('addDay'=>$addDay,'startDate'=>$startDate,'endDate'=>$endDate);
    }
    /**
 * Display a listing of the free slots.
 *
 * @return Response
 */
    public function index(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        $today =Carbon::today();
        $dayNo=date('w', strtotime($today));
        if($dayNo == 0){
            $addDay =1;
        }
        elseif ($dayNo == 1){
            $addDay = 0;
        }
        elseif ($dayNo == 2){
            $addDay = 6;
        }
        elseif($dayNo== 3){
            $addDay = 5;
        }
        elseif ($dayNo == 4){
            $addDay =4;
        }
        elseif ($dayNo == 5){
            $addDay =3;
        }
        else{
            $addDay =2;
        }

        $weekday = $today->addDays($addDay);
        $weekday = $weekday->toDateString();
        $dayCount = 0;
        $i = 0;
        $calendar_events = array();
        while($dayCount!=6) {
            $calendarEvents = \DB::table('calendar_events')
                ->whereDATE('start',$weekday)
                ->where('eventType','Teacher Appointment')
                ->get();
            foreach ($calendarEvents as $calendarEvent)
            {
                $calendarEventId = $calendarEvent->id;
                $title = $calendarEvent->title;
                $start = Carbon::parse($calendarEvent->start);
                $startDay = date('w', strtotime($start));
                global $day;
                switch ($startDay) {
                    case 0:
                        $day = "Sunday";
                        break;
                    case 1:
                        $day = "Monday";
                        break;
                    case 2:
                        $day = "Tuesday";
                        break;
                    case 3:
                        $day = "Wednesday";
                        break;
                    case 4:
                        $day = "Thursday";
                        break;
                    case 5:
                        $day = "Friday";
                        break;
                    case 6:
                        $day = "Saturday";
                        break;
                }
                $startTime = $start->toTimeString();
                $end = Carbon::parse($calendarEvent->end);
                $endTime = $end->toTimeString();
                $teacherSlot = TeacherAppointmentSlots::where('calendarEventsId', $calendarEventId)->first();
                $teacherId = $teacherSlot->teacher_id;
                $teacherDetails = UserDetails::where('user_id', $teacherId)->first();
                $teacherName = $teacherDetails->name;
                $calendar_events[$i++] = array(
                    'id' => $calendarEventId,
                    'teacherId' => $teacherId,
                    'teacherName' => $teacherName,
                    'title' => $title,
                    'day' => $day,
                    'startTime' => $startTime,
                    'endTime' => $endTime
                );
            }
            $weekday++;
            $dayCount++;
        }
        return view('calendar_events.index', compact('calendar_events'),$data);
    }
    /**
     * Show the form for creating a new free slot.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

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
        return view('calendar_events.create',compact('teacherData','days'),$data);
    }
    
    /**
     * Store a newly created free slot.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $rules = array(
            'teacherId' => 'required',
            'day' => 'required',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
        );
        $this->validate($request, $rules);

        $teacherId =Input::get("teacherId");
        $slotDay = Input::get('day');
        $startTime = Input::get('start');
        $endTime = Input::get('end');

        $arr = $this->getDay($slotDay);
        $addDay = $arr['addDay'];
        $startDate = $arr['startDate'];
        $endDate = $arr['endDate'];

        $startTime = Carbon::parse($startTime);
        $hours = (double)$startTime->format('H');
        $minutes = (double)$startTime->format('i');
        $seconds = (double)$startTime->format('s');
        $startDateTime = $startDate->addDays($addDay);
        $startDateTime = date_time_set($startDateTime, $hours, $minutes,$seconds);
        $startDate = Carbon::parse($startDateTime)->toDateString();

        $endTime = Carbon::parse($endTime);
        $hours = (double)$endTime->format('H');
        $minutes = (double)$endTime->format('i');
        $seconds = (double)$endTime->format('s');
        $endDateTime = $endDate->addDays($addDay);
        $endDateTime = date_time_set($endDateTime, $hours, $minutes, $seconds);

        $calendarEvent1 = \DB::table('calendar_events')
            ->join('teacherAppointmentsSlots','teacherAppointmentsSlots.calendarEventsId','calendar_events.id')
            ->where('teacherAppointmentsSlots.teacher_id',$teacherId)
            ->whereDate('calendar_events.start',$startDate)
            ->get();

        $calendarEvent2 = \DB::table('calendar_events')
            ->join('teacherAppointmentsSlots','teacherAppointmentsSlots.calendarEventsId','calendar_events.id')
            ->where('teacherAppointmentsSlots.teacher_id',$teacherId)
            ->whereDate('calendar_events.start',$startDate)
            ->where(function($query) use($endDateTime,$startDateTime){
                $query->where('calendar_events.start','>',$endDateTime)
                    ->orwhere('calendar_events.end','<',$startDateTime);
            })->get();

        //Insert if calendarEvents2 not empty
        if ($calendarEvent1->isEmpty() || $calendarEvent2->isNotEmpty())
        {
            $i = 0;
            try {
                \DB::beginTransaction();
                while ($i != 52) {
                    $calendar_event = new CalendarEvent();
                    $calendar_event->title = 5;
                    $calendar_event->start = $startDateTime;
                    $calendar_event->end = $endDateTime;
                    $calendar_event->is_all_day = 0;
                    $calendar_event->background_color = "blue";
                    $calendar_event->eventType = "Teacher Appointment";
                    $calendar_event->save();
                    $teacherSlots = new TeacherAppointmentSlots();
                    $teacherSlots->teacher_id = $teacherId;
                    $teacherSlots->isBooked = 0;
                    $teacherSlots->calendarEventsId = $calendar_event->getId();
                    $teacherSlots->save();
                    $i++;
                    $startDateTime->addDays(7);
                    $endDateTime->addDays(7);
                }
            } catch (Exception $e) {
                \DB::rollBack();
            }
            \DB::commit();
            return redirect(route('calendar_events.index'))
                ->with('success', 'Slot added successfully');
        }
        else{
            return redirect(route('calendar_events.create'))
                ->with('failure', 'Slot already exists')->withInput();
        }


    }

    /**
     * Display the specified free slot.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentSlot = TeacherAppointmentSlots::where('calendarEventsId',$id)->first();
        $i = 0;
        $teacherId = $appointmentSlot->teacher_id;
        $teacher = UserDetails::where('user_id',$teacherId)->first();
        $teacherName = $teacher->name;
        $calendarEvent = CalendarEvent::where('id',$id)->first();
        $title = $calendarEvent->title;
        $startDateTime = $calendarEvent->start;
        $endDateTime = $calendarEvent->end;
        $startTime = $startDateTime->toTimeString();
        $endTime = $endDateTime->toTimeString();
        $dayNo= date('w', strtotime($startDateTime));
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
            'teacherId'=>$teacherId,
            'teacherName'=>$teacherName,
            'title'=>$title,
            'day'=>$day,
            'startTime'=>$startTime,
            'endTime'=>$endTime
            );

        return view('calendar_events.show', compact('calendar_event'),$data);
    }

    /**
     * Show the form for editing the specified free slot.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentSlot = TeacherAppointmentSlots::where('calendarEventsId',$id)->first();
        $i = 0;
        $teacherId = $appointmentSlot->teacher_id;
        $teacher = UserDetails::where('user_id',$teacherId)->first();
        $teacherName = $teacher->name;
        $calendarEvent = CalendarEvent::where('id',$id)->first();
        $title = $calendarEvent->title;
        $startDateTime = Carbon::parse($calendarEvent->start);
        $endDateTime = Carbon::parse($calendarEvent->end);
        $startTime = $startDateTime->toTimeString();
        $endTime = $endDateTime->toTimeString();
        $dayNo= date('w', strtotime($startDateTime));
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
            'teacherId'=>$teacherId,
            'teacherName'=>$teacherName,
            'title'=>$title,
            'day'=>$day,
            'startTime'=>$startTime,
            'endTime'=>$endTime
        );
        return view('calendar_events.edit', compact('calendar_event'),$data);
    }

    /**
     * Update the specified free slot.
     *
     * @param  int    $id
     * @param Request $request
     * @return Response
     */
    public function edit1(Request $request,$teacherId,$id)
    {
        $rules = array(
            'start'=>'date_format:H:i',
            'end'=>'date_format:H:i|after:start',
        );
        $this->validate($request,$rules);

        $calendarEvent = CalendarEvent::where('id',$id)->first();
        $startDateTime = $calendarEvent->start;
        $startDateTime = Carbon::parse($startDateTime);
        $startDate = $startDateTime->toDateString();
        $editDay = date('w',strtotime($startDateTime));
        $startDateTime = date_time_set($startDateTime,0,0,0);
        $startTime =Input::get("start");
        $startTime=Carbon::parse($startTime);
        $hours = (double)$startTime->format('H');
        $minutes = (double)$startTime->format('i');
        $seconds = (double)$startTime->format('s');
        $startDateTime = date_time_set($startDateTime,$hours,$minutes,$seconds);
        $startDateTime = Carbon::parse($startDateTime);
        $startDate = $startDateTime->toDateString();
        $endDateTime = $calendarEvent->end;
        $endDateTime = date_time_set($endDateTime,0,0,0);
        $endTime =Input::get("end");
        $endTime=Carbon::parse($endTime);
        $hours = (double)$endTime->format('H');
        $minutes = (double)$endTime->format('i');
        $seconds = (double)$endTime->format('s');
        $endDateTime = date_time_set($endDateTime,$hours,$minutes,$seconds);
        $endDateTime = Carbon::parse($endDateTime);
        $endDate = $endDateTime->toDateString();
        $teacherSlots = TeacherAppointmentSlots::all()
            ->where('teacher_id','=',$teacherId)
            ->where('calendarEventsId','>=',$id);

//        $calendarEvent1 = \DB::table('calendar_events')
//            ->join('teacherAppointmentsSlots','teacherAppointmentsSlots.calendarEventsId','calendar_events.id')
//            ->where('teacherAppointmentsSlots.teacher_id',$teacherId)
//            ->whereDate('calendar_events.start',$startDate)
//            ->get();

        foreach ($teacherSlots as $teacherSlot) {
                try {
                    \DB::beginTransaction();
                    $teacherSlotId = $teacherSlot->id;
                    $calendarEventId = $teacherSlot->calendarEventsId;
                    $calendarEvent = CalendarEvent::where('id', $calendarEventId)->first();
                    $start= $calendarEvent->start;
                    $day = date('w',strtotime($start));
                    if ($day == $editDay){
                        $appointmentRequest = AppointmentRequest::where('teacherAppointmentsSlot_id', $teacherSlotId)->first();
                        if (is_null($appointmentRequest)) {
                            $calendarEvent->start = $startDateTime;
                            $calendarEvent->end = $endDateTime;
                            $calendarEvent->save();
                            $startDateTime->addDays(7);
                            $endDateTime->addDays(7);
                        }
                        else{
                            \DB::rollback();
                            return redirect(route('calendar_events.edit'))
                                ->with('failure', 'Cannot Update Slot due to appointment request.')
                                ->withInput();
                        }
                    }
                    else{
                        $startDateTime->addDays(7);
                        $endDateTime->addDays(7);
                    }
                }catch (Exception $e){
                    \DB::rollback();
                }
                \DB::commit();
                return redirect(route('calendar_events.index'))->with('success', 'Slot updated successfully.');
        }
    }

    /**
     * Remove the specified free slot.
     *
     * @param  int $idk
     * @return Response
     */
    public function destroy($id)
    {
        $calendarEvent = CalendarEvent::where('id',$id)->first();
        $startDateTime = $calendarEvent->start;
        $deleteDay = date('w',strtotime($startDateTime));
        $endDateTime = $calendarEvent->end;
        $teacherSlots = TeacherAppointmentSlots::where('calendarEventsId','>=',$id)->get();
        try {
            \DB::beginTransaction();
            foreach ($teacherSlots as $teacherSlot) {
                $teacherSlotId = $teacherSlot->id;
                $calendarEventId = $teacherSlot->calendarEventsId;
                $calendarEvent = CalendarEvent::where('id', $calendarEventId)->first();
                $start = $calendarEvent->start;
                $day = date('w',strtotime($start));
                if ($deleteDay == $day){
                    $appointmentRequest = AppointmentRequest::where('teacherAppointmentsSlot_id', $teacherSlotId)->first();
                    if (is_null($appointmentRequest)) {
                        $teacherSlot->delete();
                        $calendarEvent->delete();
                        $startDateTime->addDays(7);
                        $endDateTime->addDays(7);
                    }
                    else{
                        \DB::rollback();
                        return redirect(route('calendar_events.index'))->with('failure', 'Cannot Delete Slot due to appointment request.');
                    }
                }
                else{
                    $startDateTime->addDays(7);
                    $endDateTime->addDays(7);
                }
            }
        }catch (Exception $e){
            \DB::rollback();
        }
        \DB::commit();
        return redirect(route('calendar_events.index'))->with('success', 'Slot deleted successfully.');
    }
    
    //Show appointments of all the teachers
    public function showAppointments($id,Request $request){
        
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $appointmentRequest = AppointmentRequest::where('id',$id)->first();
        $appointmentRequestId=$appointmentRequest->id;
        $teacherId = $appointmentRequest->teacher_id;
        $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
        $teacherName = $teacherDetails->name;
        $parentId = $appointmentRequest->parent_id;
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
            $requestedBy = "Teacher";
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
        $eventId = $slot->calendarEventsId;
        $event = CalendarEvent::where('id',$eventId)->first();
        $title=$event->title;
        $start=$event->start;
        $end=$event->end;
        $appointmentDetails= array(
            'requestId' => $appointmentRequestId,
            'teacherId' =>$teacherId,
            'teacherName' =>$teacherName,
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
            'status'=>$status
        );

        return view('calendar_events.showAppointments', compact('appointmentDetails'),$data);
    }

}
