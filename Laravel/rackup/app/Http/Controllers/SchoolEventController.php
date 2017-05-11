<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Input;

class SchoolEventController extends Controller
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

        $school_events = CalendarEvent::all()->where('eventType',"School Function");

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

        return view('school_events.create',$data);
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
            'title' => 'required',
            'startDate' => 'required|date|after:today',
            'startTime'=>'required|date_format:H:i',
//            'endDate'=>'required|date|after_or_equal:startDate',
            'endTime'=>'required|after:startTime|date_format:H:i'
        );
        $this->validate($request,$rules);
       try{
           \DB::beginTransaction();
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
           $school_event = new CalendarEvent();

           $school_event->title            = $request->input("title");
           $school_event->start            = $startDateTime;
           $school_event->end              = $endDateTime;
           $school_event->is_all_day       = 0;
           $school_event->background_color = "Pink";
           $school_event->eventType = "School Function";

           $school_event->save();
       }catch (Exception $e){
           \DB::rollback();
           return redirect(route('school_events.index'))->with('failure', 'Adding Event failed');
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
        $title = $schoolEvent->title;
        $startDateTime = $schoolEvent->start;
        $startDate = $startDateTime->toDateString();
        $startTime = $startDateTime->toTimeString();
        $endDateTime = $schoolEvent->end;
        $endDate = $endDateTime->toDateString();
        $endTime = $endDateTime->toTimeString();

        $school_event = array(
            'id'=>$id,
            'title'=>$title,
            'startDate'=>$startDate,
            'startTime'=>$startTime,
            'endDate'=>$endDate,
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
//            'endDate'=>'date|after_or_equal:startDate',
            'endTime'=>'after:startTime|date_format:H:i'
        );
        try{
            \DB::beginTransaction();
            $school_event = CalendarEvent::findOrFail($id);

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

            $school_event->title            = $request->input("title");
            $school_event->start            = $startDateTime;
            $school_event->end              = $endDateTime;

            $school_event->save();
        }catch (Exception $e){
            \DB::rollback();
            return redirect(route('school_events.index'))->with('failure', 'Event Update Failed');
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
