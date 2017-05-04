<?php

namespace App\Http\Controllers;

use App\AppointmentRequest;
use App\TeacherAppointmentSlots;
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
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        $databaseEvents = $this->calendarEvent->all();
        $events = array();
        $i = 0;
        foreach ($databaseEvents as $databaseEvent){
            $title = $databaseEvent->getTitle();
            $isallDay = $databaseEvent->isAllDay();
            $start = $databaseEvent->getStart();
            $end = $databaseEvent->getEnd();
            $id = $databaseEvent->getId();
            $color=$databaseEvent->background_color;
            $events[$i++] = Calendar::event(
                $title,
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
        //return var_export($events);
        $calendar = Calendar::addEvents($events);
        return view('calendar_events.calendar', compact('calendar'),$data);
        }


    //Teacher Calendar
    public function teacherCalendar(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;
        //$databaseEvents = $this->calendarEvent->all();
        $appointmentRequests = AppointmentRequest::all()->where('teacher_id',$id);
        $events = array();
        $i = 0;
        foreach ($appointmentRequests as $appointmentRequest){
            $appointmentRequestId = $appointmentRequest->id;
            $teacherSlotId = $appointmentRequest->teacherAppointmentsSlot_id;
            $teacherSlot = TeacherAppointmentSlots::where('id',$teacherSlotId)->first();
            $calendarEventId = $teacherSlot->calendarEventsId;
            $databaseEvent = CalendarEvent::where('id',$calendarEventId)->first();
            $title = $databaseEvent->getTitle();
            $isallDay = $databaseEvent->isAllDay();
            $start = $databaseEvent->getStart();
            $end = $databaseEvent->getEnd();
            $id = $databaseEvent->getId();
            $booked= $teacherSlot->isBooked;
            $awaited = $appointmentRequest->isAwaited;
            $confirmed = $appointmentRequest->isApproved;
            $cancelled = $appointmentRequest->isCancel;
            if ($booked==0 && $awaited==1 && $confirmed==0 && $cancelled==0){
                $color = "Yellow";
            }
            elseif ($booked==1 && $awaited==0 && $confirmed==1 && $cancelled==0){
                $color = "Green";
            }
            elseif($booked==0 && $awaited==0 && $confirmed==0 && $cancelled==1) {
                $color="Red";
            }
            else{
                $color= "Blue";
            }
            $events[$i++] = Calendar::event(
                $title,
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
        $calendar = Calendar::addEvents($events);
        return view('appointments.calendar', compact('calendar'),$data);
    }

//        $staticEvent = Calendar::event(
//            'Today\'s Sample',
//            true,
//            Carbon::today()->setTime(0, 0),
//            Carbon::today()->setTime(23, 59),
//            null,
//            [
//                'color' => '#0F0',
//                'url' => 'http://google.com',
//            ]);
//        $databaseEvents = $this->calendarEvent->all();
//        $calendar = Calendar::addEvent($staticEvent)->addEvents($databaseEvents);
//        return view('calendar_events/calendar', compact('calendar'));
}
