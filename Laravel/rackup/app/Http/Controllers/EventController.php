<?php

namespace App\Http\Controllers;

use App\AppointmentRequest;
use App\TeacherAppointmentSlots;
use App\UserDetails;
use Illuminate\Http\Request;
use App\CalendarEvent;
use Carbon\Carbon;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class EventController extends Controller
{
    /**
     * @var CalendarEvent
     */
    private $calendarEvent;

    /**
     * @param CalendarEvent $calendarEvent
     */
    public function __construct(CalendarEvent $calendarEvent)
    {
        $this->calendarEvent = $calendarEvent;
    }

    //Admin Calendar
    public function calendar(Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $databaseEvents = $this->calendarEvent->all();
        $slots = array();
        $schoolEvents = array();
        $appointments = array();
        $i = 0;
        $j=0;
        $k = 0;
        $today = Carbon::today();
        foreach ($databaseEvents as $databaseEvent){
            $id = $databaseEvent->getId();
            $title = $databaseEvent->getTitle();
            $isallDay = $databaseEvent->isAllDay();
            $start = $databaseEvent->getStart();
            $end = $databaseEvent->getEnd();
            $eventType = $databaseEvent->eventType;
            if($eventType=="School Function"){
                $color=$databaseEvent->background_color;
                $schoolEvents[$j++] = Calendar::event(
                    $title,
                    $isallDay,
                    $start,
                    $end,
                    $id,
                    [
                        'color'=>$color,
                        'url'=>'school_events/'.$id,
                    ]
                );
            }
            else{
                $slot = TeacherAppointmentSlots::where('calendarEventsId',$id)->first();
                $slotId = $slot->id;
                $teacherId = $slot->teacher_id;
                $teacherDetails = UserDetails::where('user_id',$teacherId)->first();
                $teacherName = $teacherDetails->name;
                $booked = $slot->isBooked;
                $appointmentRequest = AppointmentRequest::where('teacherAppointmentsSlot_id',$slotId)->first();
                if (is_null($appointmentRequest)){
                    if($start<$today){
                        $color= "Brown";
                        $status="Past Slot";
                    }
                    else{
                        $color ="Blue";
                        $status="Free Slot";
                    }
                    $slots[$i++] = Calendar::event(
                        $title.$teacherName.$status,
                        $isallDay,
                        $start,
                        $end,
                        $id,
                        [
                            'color'=>$color,
                            'url'=>'calendar_events/'.$id,
                        ]
                    );
                }
                else{
                    $appointmentRequestId = $appointmentRequest->id;
                    $awaited = $appointmentRequest->isAwaited;
                    $confirmed = $appointmentRequest->isApproved;
                    $cancelled = $appointmentRequest->isCancel;
                    if ($booked==1 && $awaited==1 && $confirmed==0 && $cancelled==0){
                        $color = "Yellow";
                        $status = "Awaited";
                    }
                    elseif ($booked==1 && $awaited==0 && $confirmed==1 && $cancelled==0){
                        $color = "Green";
                        $status="Confirmed";
                    }
                    elseif($booked==0 && $awaited==0 && $confirmed==0 && $cancelled==1) {
                        $color="Red";
                        $status = "Cancelled";
                    }
                    elseif($start<$today){
                        $color= "Brown";
                        $status="Past Booking";
                    }
                    else{
                        $color ="Grey";
                        $status="Invalid";
                    }
                    $appointments[$k++] = Calendar::event(
                        $status.$teacherName.$eventType,
                        $isallDay,
                        $start,
                        $end,
                        $id,
                        [
                            'color'=>$color,
                            'url'=>' showAppointments'.$appointmentRequestId,
                        ]
                    );
                }

            }
        }
//        return var_export($schoolEvents);
        //return var_export($events);
        $calendar = Calendar::addEvents($slots);
        $calendar = Calendar::addEvents($schoolEvents);
        $calendar = Calendar::addEvents($appointments);
        return view('calendar_events.calendar', compact('calendar'),$data);
        }


    //Teacher Calendar
    public function teacherCalendar(Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $teacherDetails = UserDetails::where('user_id',$user_id)->first();
        $teacherName = $teacherDetails->name;
        $today = Carbon::today();
        $slots = TeacherAppointmentSlots::all()->where('teacher_id',$user_id);
        $appointments = array();
        $i = 0;
        $freeSlots = array();
        $j = 0;
        foreach ($slots as $slot){
            $slotId = $slot->id;
            $booked= $slot->isBooked;
            $calendarEventId = $slot->calendarEventsId;
            $databaseEvent = CalendarEvent::where('id',$calendarEventId)->first();
            $title = $databaseEvent->getTitle();
            $isallDay = $databaseEvent->isAllDay();
            $start = $databaseEvent->getStart();
            $end = $databaseEvent->getEnd();
            $id = $databaseEvent->getId();
            $eventType = $databaseEvent->eventType;
            $appointmentRequest=AppointmentRequest::where('teacherAppointmentsSlot_id',$slotId)->first();
            if (is_null($appointmentRequest)){
                if($start<$today){
                    $color= "Brown";
                    $status = "Past Slot";
                }
                else{
                    $color ="Blue";
                    $status="Free Slot";
                }
                $freeSlots[$j++] = Calendar::event(
                    $title.$status.$eventType,
                    $isallDay,
                    $start,
                    $end,
                    $id,
                    [
                        'color'=>$color,
                        'url'=>'showFreeSlots'.$id
                    ]
                );
            }
            else{
                $appointmentRequestId = $appointmentRequest->id;
                $parentId = $appointmentRequest->parent_id;
                $parentDetails = UserDetails::where('user_id',$parentId)->first();
                $parentName = $parentDetails->name;
                $awaited = $appointmentRequest->isAwaited;
                $confirmed = $appointmentRequest->isApproved;
                $cancelled = $appointmentRequest->isCancel;
                if ($booked==1 && $awaited==1 && $confirmed==0 && $cancelled==0){
                    $color = "Yellow";
                    $status="Awaited";
                }
                elseif ($booked==1 && $awaited==0 && $confirmed==1 && $cancelled==0){
                    $color = "Green";
                    $status="Confirmed";
                }
                elseif($booked==0 && $awaited==0 && $confirmed==0 && $cancelled==1) {
                    $color="Red";
                    $status="Cancelled";
                }
                elseif($start<$today){
                    $color= "Brown";
                    $status = "Past Booking";
                }
                else{
                    $color= "Grey";
                    $status="Invalid";
                }
                $appointments[$i++] = Calendar::event(
                    $status.$parentName,
                    $isallDay,
                    $start,
                    $end,
                    $id,
                    [
                        'color'=>$color,
                        'url'=>'appointments/'.$appointmentRequestId,
                    ]
                );
            }
        }

        $schoolEvents = CalendarEvent::all()->where('eventType','School Function');
        $school_events = array();
        $k=0;
        foreach ($schoolEvents as $schoolEvent){
            $id = $schoolEvent->getId();
            $title = $schoolEvent->getTitle();
            $isallDay = $schoolEvent->isAllDay();
            $start = $schoolEvent->getStart();
            $end = $schoolEvent->getEnd();
            $color = $schoolEvent->background_color;
            $school_events[$k++] = Calendar::event(
                $title,
                $isallDay,
                $start,
                $end,
                $id,
                [
                    'color'=>$color
                ]
            );
        }
        $calendar = Calendar::addEvents($freeSlots);
        $calendar = Calendar::addEvents($appointments);
        $calendar = Calendar::addEvents($school_events);
        return view('appointments.calendar', compact('calendar'),$data);
    }
}
