<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use App\User;
use App\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Input;

class SchoolEventController extends Controller
{
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

        $school_events = \DB::table('calendar_events')
            ->where('eventType',"Parent Function")
            ->orWhere('eventType',"Teacher Function")
            ->orderBy('eventType')
            ->orderBy('id','desc')
            ->get();

        return view('school_events.index', compact('school_events'),$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($id)->first();
        $data['user'] = $user;

        $eventTypes = array(
            'Parent Function',
            'Teacher Function'
        );
        return view('school_events.create',compact('eventTypes'),$data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'eventType'=>'required',
            'title' => 'required',
            'startDate' => 'required|date|after:today',
            'startTime'=>'required|date_format:H:i',
            'endTime'=>'required|after:startTime|date_format:H:i'
        );
        $this->validate($request,$rules);
        $eventType = Input::get('eventType');
        $title=$request->input("title");
        $startDate = Input::get('startDate');
        $startTime =Input::get('startTime');
        $endTime =Input::get("endTime");
       try{
           \DB::beginTransaction();
           $startDate = Carbon::parse($startDate);
           $endDate = $startDate;
           $endDate = Carbon::parse($endDate);
           $startTime = Carbon::parse($startTime);
           $hours = (double)$startTime->format('H');
           $minutes = (double)$startTime->format('i');
           $seconds = (double)$startTime->format('s');
           $startDateTime = date_time_set($startDate,$hours,$minutes,$seconds);
           $endTime = Carbon::parse($endTime);
           $hours = (double)$endTime->format('H');
           $minutes = (double)$endTime->format('i');
           $seconds = (double)$endTime->format('s');
           $endDateTime = date_time_set($endDate,$hours,$minutes,$seconds);
           $school_event = new CalendarEvent();

           $school_event->title            = $title;
           $school_event->start            = $startDateTime;
           $school_event->end              = $endDateTime;
           $school_event->is_all_day       = 0;
           $school_event->eventType = $eventType;

           $school_event->save();
           
           if ($eventType == "Parent Function"){
               $eventId = $school_event->getId();
               $parents = User::all()->where('role_id',2);
               $i = 0;
               $gcmRegistrationId = array();
               foreach ($parents as $parent){
                   $parentId = $parent->id;
                   $parentDetails = UserDetails::where('user_id',$parentId)->first();
                   $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                   $message = array("message"=>"Upcoming Event: $title on $startDate from $startTime to $endTime","eventId"=>$eventId);
                   sendPushNotificationToGCM($gcmRegistrationId,$message);
               }
           }
           
       }catch (Exception $e){
           \DB::rollback();
           return redirect(route('school_events.create'))->with('failure', 'Adding Event failed');
       }
        \DB::commit();
        return redirect(route('school_events.index'))->with('success', 'Event added successfully.');
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

        $school_event = CalendarEvent::findOrFail($id);

        return view('school_events.show', compact('school_event'),$data);
    }

    public function showEvents($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $school_event = CalendarEvent::findOrFail($id);

        return view('.showEvents', compact('school_event'),$data);
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;
        $schoolEvent = CalendarEvent::findOrFail($id);
        $eventType = $schoolEvent->eventType;
        $title = $schoolEvent->title;
        $startDateTime = $schoolEvent->start;
        $startDateTime = Carbon::parse($startDateTime);
        $startDate = $startDateTime->toDateString();
        $startTime = $startDateTime->toTimeString();
        $endDateTime = $schoolEvent->end;
        $endDateTime = Carbon::parse($endDateTime);
        $endTime = $endDateTime->toTimeString();

        $school_event = array(
            'id'=>$id,
            'eventType'=>$eventType,
            'title'=>$title,
            'startDate'=>$startDate,
            'startTime'=>$startTime,
            'endTime'=>$endTime
        );

        return view('school_events.edit', compact('school_event'),$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int    $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'startDate' => 'date|after:today',
            'startTime'=>'date_format:H:i',
            'endTime'=>'after:startTime|date_format:H:i'
        );
        $this->validate($request,$rules);
        $startDate = Input::get('startDate');
        $startTime =Input::get('startTime');
        $endTime =Input::get("endTime");
        try{
            \DB::beginTransaction();
            $school_event = CalendarEvent::findOrFail($id);
            $startDate = Carbon::parse($startDate);
            $endDate = $startDate;
            $endDate = Carbon::parse($endDate);
            $startTime = Carbon::parse($startTime);
            $hours = (double)$startTime->format('H');
            $minutes = (double)$startTime->format('i');
            $seconds = (double)$startTime->format('s');
            $startDateTime = date_time_set($startDate,$hours,$minutes,$seconds);
            $endTime = Carbon::parse($endTime);
            $hours = (double)$endTime->format('H');
            $minutes = (double)$endTime->format('i');
            $seconds = (double)$endTime->format('s');
            $endDateTime = date_time_set($endDate,$hours,$minutes,$seconds);
            
            $school_event->start            = $startDateTime;
            $school_event->end              = $endDateTime;
            $school_event->save();
            
            $eventType = $school_event->eventType;
            $title=$school_event->title;
            if ($eventType == "Parent Function"){
                $eventId = $school_event->getId();
                $parents = User::all()->where('role_id',2);
                $i=0;
                $gcmRegistrationId=array();
                foreach ($parents as $parent){
                    $parentId = $parent->id;
                    $parentDetails = UserDetails::where('user_id',$parentId)->first();
                    $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                    $message = array("message"=>"Event Update: $title rescheduled to $startDate from $startTime to $endTime","eventId"=>$eventId);
                    sendPushNotificationToGCM($gcmRegistrationId,$message);
                }
            }
            
        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('school_events.edit'))->with('failure', 'Event Update Failed')->withInput();
        }
        \DB::commit();
        return redirect(route('school_events.index'))->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
       try{
           \DB::beginTransaction();
           $school_event = CalendarEvent::findOrFail($id);
           $school_event->delete();
       }catch (Exception $e){
           \DB::rollback();
           return redirect(route('school_events.index'))->with('failure', 'Unable to delete the event');
       }
        \DB::commit();
        return redirect(route('school_events.index'))->with('success', 'Event deleted successfully.');
    }

}
