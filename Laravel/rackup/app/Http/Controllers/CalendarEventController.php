<?php

namespace App\Http\Controllers;

use App\AppointmentRequest;
use App\CalendarEvent;
use App\User;
use App\UserDetails;
use App\TeacherAppointmentSlots;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rules\In;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;

class CalendarEventController extends Controller
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
            $calendarEvents = CalendarEvent::whereDATE(('start'),$weekday)->get();
            foreach ($calendarEvents as $calendarEvent)
            {
                $calendarEventId = $calendarEvent->id;
                $title = $calendarEvent->title;
                $start = $calendarEvent->start;
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
                $end = $calendarEvent->end;
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
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
        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        return view('calendar_events.create',compact('teacherData','days'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $startDate=Carbon::today();
        $endDate=Carbon::today();
        $dayofweek = date('w', strtotime($startDate));
        $slotDay = Input::get('day');
        $slotDayNo= date('w', strtotime($slotDay));
        $diffDays = $slotDayNo-$dayofweek;
        if ($diffDays<0){
            if ($diffDays==-1){
                $addDay = 6;
            }
            elseif ($diffDays==-2){
                $addDay = 5;
            }
            elseif ($diffDays==-3){
                $addDay= 4;
            }
            elseif ($diffDays==-4){
                $addDay =3;
            }
            elseif ($diffDays==-5){
                $addDay = 2;
            }
            else{
                $addDay = 1;
            }
        }
        else
            $addDay=$diffDays;
//        $slotDate = $startDate->addDays($addDay);
        $startTime = Input::get('start');
        $startDateTime = $startDate
            ->addDays($addDay)
            ->addHours($startTime);
        $endTime = Input::get('end');
        //return $endTime;
        $endDateTime = $endDate
            ->addDays($addDay)
            ->addHours($endTime);
        $i = 0;
       while ($i!=52){
            try{
                \DB::beginTransaction();
                $calendar_event = new CalendarEvent();
                $calendar_event->title= $request->input("title");
                $calendar_event->start= $startDateTime;
                $calendar_event->end= $endDateTime;
                $calendar_event->is_all_day = 0;
                $calendar_event->background_color = "blue";
                $calendar_event->eventType = "Appointment";
                $calendar_event->save();
                $teacherSlots = new TeacherAppointmentSlots();
                $teacherSlots->teacher_id = $request->input("teacherId");
                $teacherSlots->isBooked = 0;
                $teacherSlots->calendarEventsId = $calendar_event->getId();
                $teacherSlots->save();
            }catch (Exception $e){
                \DB::rollBack();
            }
            \DB::commit();
           $i++;
           $startDateTime->addDays(7);
           $endDateTime->addDays(7);
        }
        return redirect(route('calendar_events.index'))
            ->with('message', 'Slot added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
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

        return view('calendar_events.show', compact('calendar_event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
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
        return view('calendar_events.edit', compact('calendar_event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int    $id
     * @param Request $request
     * @return Response
     */
    public function edit1($teacherId,$id)
    {
        $calendarEvent = CalendarEvent::where('id',$id)->first();
        $startDateTime = $calendarEvent->start;
        $editDay = date('w',strtotime($startDateTime));
        $startDateTime = date_time_set($startDateTime,0,0,0);
        $startTime =Input::get("start");
        $startTime=Carbon::parse($startTime);
        $hours = (double)$startTime->format('H');
        $minutes = (double)$startTime->format('i');
        $seconds = (double)$startTime->format('s');
        $startDateTime = date_time_set($startDateTime,$hours,$minutes,$seconds);
        $endDateTime = $calendarEvent->end;
        $endDateTime = date_time_set($endDateTime,0,0,0);
        $endTime =Input::get("end");
        $endTime=Carbon::parse($endTime);
        $hours = (double)$endTime->format('H');
        $minutes = (double)$endTime->format('i');
        $seconds = (double)$endTime->format('s');
        $endDateTime = date_time_set($endDateTime,$hours,$minutes,$seconds);
        $teacherSlots = TeacherAppointmentSlots::all()
            ->where('teacher_id','=',$teacherId)
            ->where('calendarEventsId','>=',$id);
        try {
            \DB::beginTransaction();
            foreach ($teacherSlots as $teacherSlot) {
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
                        return redirect(route('calendar_events.index'))->with('message1', 'Cannot Update Slot due to appointment request.');
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
        return redirect(route('calendar_events.index'))->with('message', 'Slot updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
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
                        return redirect(route('calendar_events.index'))->with('message1', 'Cannot Delete Slot due to appointment request.');
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
        return redirect(route('calendar_events.index'))->with('message', 'Slot deleted successfully.');
    }

}
