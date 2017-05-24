<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use App\ContentType;
use App\User;
use App\UserDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

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
        if (!defined('GOOGLE_API_KEY'))
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
     * Display a listing of the school events.
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
            ->orWhere('eventType',"Both")
            ->orderBy('eventType')
            ->orderBy('id','desc')
            ->get();

        return view('school_events.index', compact('school_events'),$data);
    }
    /**
     * Show the form for creating a new school event.
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
            'Teacher Function',
            'Both'
        );
        return view('school_events.create',compact('eventTypes'),$data);
    }


    /**
     * Store a newly created school event.
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
        
        if($request->hasFile('fileEntries')) {
            $file = $request->file('fileEntries');
            $fileExtension = $file->getClientOriginalExtension();
            if ($fileExtension != 'jpg') {
                return redirect(route('school_events.create'))->with('failure', 'Upload images of jpg only');
            } else {
                try {
                    
                    \DB::beginTransaction();
                    $startDate = Carbon::parse($startDate);
                    $endDate = $startDate;
                    $endDate = Carbon::parse($endDate);
                    $startTime = Carbon::parse($startTime);
                    $hours = (double)$startTime->format('H');
                    $minutes = (double)$startTime->format('i');
                    $seconds = (double)$startTime->format('s');
                    $startDateTime = date_time_set($startDate, $hours, $minutes, $seconds);
                    $endTime = Carbon::parse($endTime);
                    $hours = (double)$endTime->format('H');
                    $minutes = (double)$endTime->format('i');
                    $seconds = (double)$endTime->format('s');
                    $endDateTime = date_time_set($endDate, $hours, $minutes, $seconds);
                    $eventId = \DB::table('calendar_events')
                        ->insertgetId(['title' => $title, 'start' => $startDateTime,'end' => $endDateTime,'is_all_day'=>0,'eventType'=>$eventType]);
                    
                    $fileName = $eventId . '_' . $title . '.' . $fileExtension;
                    $filePath = Storage::putFileAs('public/events', $file, $fileName);
                    $imageUrl = Storage::url('events/' . $eventId . '_' . $title . '.' . $fileExtension);
                    \DB::table('calendar_events')
                        ->where('id', $eventId)
                        ->update([
                            'imageUrl' => $imageUrl
                        ]);
                    
                    if ($eventType == "Parent Function" || $eventType == "Both") {
                        $parents = User::all()->where('role_id', 2);
                        $i = 0;
                        $gcmRegistrationId = array();
                        foreach ($parents as $parent) {
                            $parentId = $parent->id;
                            $parentDetails = UserDetails::where('user_id', $parentId)->first();
                            $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                            $message = array("message" => "Upcoming Event: $title on $startDate from $startTime to $endTime", 
                                "eventId" => $eventId,"imageUrl"=>$imageUrl,"type"=>5);
                            $this->sendPushNotificationToGCM($gcmRegistrationId, $message);
                        }
                    }

                } catch (Exception $e) {
                    \DB::rollback();
                    return redirect(route('school_events.create'))->with('failure', 'Adding Event failed');
                }
                \DB::commit();
                return redirect(route('school_events.index'))->with('success', 'Event added successfully.');
            }
        }

    }

    /**
     * Display the specified school event.
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

    //Display specified school event to teacher
    public function showEvents($id,Request $request)
    {
        $user_id = $request->session()->get('id');
        $user = \DB::table('users')->whereId($user_id)->first();
        $data['user'] = $user;

        $school_event = CalendarEvent::findOrFail($id);

        return view('.showEvents', compact('school_event'),$data);
    }

    
    /**
     * Show the form for editing the specified school event.
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
     * Update the specified school event.
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
            if ($eventType == "Parent Function" || $eventType =="Both"){
                $eventId = $school_event->getId();
                $parents = User::all()->where('role_id',2);
                $i=0;
                $gcmRegistrationId=array();
                foreach ($parents as $parent){
                    $parentId = $parent->id;
                    $parentDetails = UserDetails::where('user_id',$parentId)->first();
                    $gcmRegistrationId[$i++] = $parentDetails->gcmRegistrationId;
                    $imageUrl = Storage::url('public/default/eventUpdate.jpg');
                    $message = array("message"=>"Event Update: $title rescheduled to $startDate from $startTime to $endTime","eventId"=>$eventId,"imageUrl"=>$imageUrl);
                    $this->sendPushNotificationToGCM($gcmRegistrationId,$message);
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
     * Remove the specified school event.
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
