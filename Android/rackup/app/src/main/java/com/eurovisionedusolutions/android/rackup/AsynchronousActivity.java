package com.eurovisionedusolutions.android.rackup;

/**
 * Created by arpan on 5/2/2017.
 */

import android.graphics.Color;
import android.support.v4.app.Fragment;
import android.content.Context;
import android.database.Cursor;

import android.support.v4.app.ShareCompat;
import android.support.v7.app.AppCompatActivity;
import android.widget.Toast;

import com.alamkanak.weekview.WeekViewEvent;
import com.eurovisionedusolutions.android.rackup.apiclient.MyJsonService;

import org.json.JSONArray;
import org.json.JSONException;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import retrofit.Callback;
import retrofit.RestAdapter;
import retrofit.RetrofitError;
import retrofit.client.Response;


public class AsynchronousActivity extends BaseActivity  {
    public static AsynchronousActivity newInstance() {
        AsynchronousActivity fragment = new AsynchronousActivity();
        return fragment;
    }

    private Context context;
    DBHelper mydb;
    String token="";
    private List<WeekViewEvent> events = new ArrayList<WeekViewEvent>();
    boolean calledNetwork = false;


    @Override
    public List<? extends WeekViewEvent> onMonthChange(int newYear, int newMonth) {

       /* Calendar startTime = Calendar.getInstance();
        startTime.set(Calendar.HOUR_OF_DAY, 4);
        startTime.set(Calendar.MINUTE, 30);
        startTime.set(Calendar.MONTH, newMonth - 1);
        startTime.set(Calendar.YEAR, newYear);
        Calendar endTime = (Calendar) startTime.clone();
        endTime.add(Calendar.MINUTE, 100);
        endTime.set(Calendar.MONTH, newMonth - 1);
        WeekViewEvent event = new WeekViewEvent(1, getEventTitle(startTime), startTime, endTime);
        event.setColor(getResources().getColor(R.color.colorAccent));
        events.add(event);*/



        // Download events from network if it hasn't been done already. To understand how events are
        // downloaded using retrofit, visit http://square.github.io/retrofit
        /*if (!calledNetwork) {
            RestAdapter retrofit = new RestAdapter.Builder()
                    .setEndpoint("https://api.myjson.com/bins")
                    .build();
            MyJsonService service = retrofit.create(MyJsonService.class);
            service.listEvents(this);
            calledNetwork = true;
        }*/

        // Return only the events that matches newYear and newMonth.
        /*List<WeekViewEvent> matchedEvents = new ArrayList<WeekViewEvent>();
        for (WeekViewEvent event : events) {
            matchedEvents.add(event);
            if (eventMatches(event, newYear, newMonth)) {
                matchedEvents.add(event);
            }
        }*/
        return events;
    }

    /**
     * Checks if an event falls into a specific year and month.
     * @param event The event to check for.
     * @param year The year.
     * @param month The month.
     * @return True if the event matches the year and month.
     */
    private boolean eventMatches(WeekViewEvent event, int year, int month) {
        return (event.getStartTime().get(Calendar.YEAR) == year && (int)event.getStartTime().get(Calendar.MONTH)==  month - 1) || ((int)event.getEndTime().get(Calendar.YEAR) == year &&(int) event.getEndTime().get(Calendar.MONTH)==month-1);
    }
    @Override


  /*  public void success(List<Event> events, Response response) {
        this.events.clear();

       // onMonthChange(Calendar.YEAR,Calendar.MONTH);
        for (Event event : events) {

            this.events.add(event.toWeekViewEvent());

        }
        getWeekView().notifyDatasetChanged();
    }*/








    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        String msg = "";

        if (isSuccessful) {
            try {
                this.events.clear();
               for(int j=0;j<=1;j++){
                for (int i = 0; i < response.getJSONArray(j).length(); i++) {
                    String api_id = response.getJSONArray(j).getJSONObject(i).getString("id");
                    String api_teacherid = response.getJSONArray(j).getJSONObject(i).getString("teacherId");
                    int api_teacherid_int=Integer.valueOf(api_teacherid);
                    String api_teacherName = response.getJSONArray(j).getJSONObject(i).getString("teacherName");
                    String api_title = response.getJSONArray(j).getJSONObject(i).getString("title");
                    String api_startDate = response.getJSONArray(j).getJSONObject(i).getString("startDate");
                    String api_endDate = response.getJSONArray(j).getJSONObject(i).getString("endDate");
                    String api_startTime = response.getJSONArray(j).getJSONObject(i).getString("startTime");
                    String api_endTime = response.getJSONArray(j).getJSONObject(i).getString("endTime");
                    Long api_id_long = Long.valueOf(api_id);
                    int api_startTime_HOUR = Integer.valueOf(api_startTime.substring(0, 2));
                    int api_endTime_HOUR = Integer.valueOf(api_endTime.substring(0, 2));
                    int api_startDate_Year = Integer.valueOf(api_startDate.substring(0, 4));
                    int api_startDate_Month = Integer.valueOf(api_endDate.substring(5, 7));
                    int api_startDate_Day = Integer.valueOf(api_startDate.substring(8));
                    int api_endDate_Month = Integer.valueOf(api_endDate.substring(5,7));

                    int api_startTime_MINUTE = Integer.valueOf(api_startTime.substring(3, 5));
                    int api_endTime_MINUTE = Integer.valueOf(api_endTime.substring(3, 5));
                    int duration = 0;

                    if (api_endTime_HOUR >= api_startTime_HOUR) {
                        duration = getDuration(api_startTime, api_endTime);
                    } else {
                        Toast.makeText(getActivity(), "End time can't be less than start time", Toast.LENGTH_SHORT).show();
                    }
                    Calendar startTime = Calendar.getInstance();
                    startTime.set(Calendar.HOUR_OF_DAY, api_startTime_HOUR);
                    startTime.set(Calendar.DAY_OF_MONTH, api_startDate_Day);
                    startTime.set(Calendar.MINUTE, api_startTime_MINUTE);
                    startTime.set(Calendar.MONTH, api_startDate_Month - 1);
                    startTime.set(Calendar.YEAR, api_startDate_Year);
                    Calendar endTime = (Calendar) startTime.clone();
                    endTime.add(Calendar.MINUTE, duration);
                    endTime.set(Calendar.MONTH, api_startDate_Month - 1);
                    WeekViewEvent event1 = new WeekViewEvent(api_id_long, getEventTitle(api_teacherName), startTime, endTime);

                    int square=api_teacherid_int*api_teacherid_int;
                    int sum=api_teacherid_int+api_teacherid_int;
                    String temp_square=String.valueOf(square);
                    String temp_sum=String.valueOf(sum);

                    if(temp_square.length()<3){temp_square=temp_square+"00";}
                    if(temp_sum.length()<3){temp_sum=temp_sum+"00";}
                    temp_square=temp_square.substring(0,3);
                    temp_sum=temp_sum.substring(0,3);


                    event1.setColor(Color.rgb(Integer.valueOf(temp_square),Integer.valueOf(temp_sum),50));
                    event1.setName(api_teacherName+"/"+api_teacherid+"@"+api_title);

                    // events.add(event1);

                    this.events.add(event1);


                }} getWeekView().notifyDatasetChanged();
                //Toast.makeText(getActivity(), "and here we go ", Toast.LENGTH_SHORT).show();}


                //Toast.makeText(context, "remote call "+status1+" length: "+str.get(3), Toast.LENGTH_LONG).show();
            } catch (JSONException e) {
                e.printStackTrace();
                msg = "error";
            }
            // Toast.makeText(context, msg, Toast.LENGTH_LONG).show();
        } else {
            Toast.makeText(getActivity(), "can't connect to server", Toast.LENGTH_SHORT).show();
        }
        return;
    }
    private int getDuration(String time1, String time2) {


        SimpleDateFormat format = new SimpleDateFormat("HH:mm:ss");
        Date date1 = null;
        try {
            date1 = format.parse(time1);
        } catch (ParseException e) {
            e.printStackTrace();
        }
        Date date2 = null;
        try {
            date2 = format.parse(time2);
        } catch (ParseException e) {
            e.printStackTrace();
        }
        Long difference = date2.getTime() - date1.getTime();
        Long seconds = difference / 1000;
        seconds = seconds / 60;
        int minutes = Integer.valueOf(seconds.intValue());
        return minutes;
    }
}
