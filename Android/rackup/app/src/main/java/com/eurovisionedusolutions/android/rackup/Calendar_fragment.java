package com.eurovisionedusolutions.android.rackup;

import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.Toolbar;
import android.text.SpannableString;
import android.text.style.RelativeSizeSpan;
import android.view.LayoutInflater;
import android.view.MenuInflater;
import android.view.View;
import android.view.ViewGroup;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import com.desai.vatsal.mydynamiccalendar.AppConstants;
import com.desai.vatsal.mydynamiccalendar.EventModel;
import com.desai.vatsal.mydynamiccalendar.GetEventListListener;
import com.desai.vatsal.mydynamiccalendar.OnDateClickListener;
import com.desai.vatsal.mydynamiccalendar.OnEventClickListener;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;


import org.json.JSONArray;
import org.json.JSONException;



public class Calendar_fragment extends Fragment  implements RemoteCallHandler{
    public static Calendar_fragment newInstance() {
        Calendar_fragment fragment = new Calendar_fragment();
        return fragment;
    }
    private Toolbar toolbar;
    DBHelper mydb;
    public static String token = "there";
    private MyDynamicCalendar_Extended myCalendar;
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    // TODO: Rename and change types of parameters
    private String mParam1;
    private String mParam2;

    private OnFragmentInteractionListener mListener;

    public Calendar_fragment() {
        // Required empty public constructor
    }


    // TODO: Rename and change types and number of parameters
    public Calendar_fragment newInstance(String param1, String param2) {
        Calendar_fragment fragment = new Calendar_fragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
        setHasOptionsMenu(true);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View view= inflater.inflate(R.layout.calendar, container, false);
        toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        toolbar.setTitleTextColor(getResources().getColor(R.color.black));
        toolbar.setTitle("Appointments");
        myCalendar = (MyDynamicCalendar_Extended) view.findViewById(R.id.myCalendar);
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);
        myCalendar.showMonthView();

        myCalendar.setOnDateClickListener(new OnDateClickListener() {
            @Override
            public void onClick(Date date) {
                Log.e("date", String.valueOf(date));
            }

            @Override
            public void onLongClick(Date date) {
                Log.e("date", String.valueOf(date));
            }
        });


        myCalendar.setHeaderBackgroundColor(getResources().getColor(R.color.colorPrimary));


        myCalendar.setHeaderTextColor(getResources().getColor(R.color.black));

        myCalendar.setNextPreviousIndicatorColor(getResources().getColor(R.color.black));
        myCalendar.setWeekDayLayoutTextColor(getResources().getColor(R.color.white));

        myCalendar.setWeekDayLayoutTextColor(getResources().getColor(R.color.black));

        myCalendar.isSundayOff(true, getResources().getColor(R.color.white), getResources().getColor(R.color.red));
//

       myCalendar.setCurrentDateBackgroundColor(getResources().getColor(R.color.colorPrimaryDark));
//
        myCalendar.setCurrentDateTextColor("#ffffff");

        myCalendar.deleteAllEvent();





        myCalendar.getEventList(new GetEventListListener() {
            @Override
            public void eventList(ArrayList<EventModel> eventList) {
                Log.e("tag", "eventList.size():-" + eventList.size());
                for (int i = 0; i < eventList.size(); i++) {
                    Log.e("tag", "eventList.getStrName:-" + eventList.get(i).getStrName());
                }

            }

        });


        myCalendar.setHolidayCellClickable(false);


        setHasOptionsMenu(true);
        return view;
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.menu_main, menu);

    }
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        switch (item.getItemId()) {
            case R.id.action_month_with_below_events:
                showMonthViewWithBelowEvents();
//                return super.onOptionsItemSelected(item);
                return true;

            case R.id.action_agenda:
                showAgendaView();
//                return super.onOptionsItemSelected(item);
                return true;

            case R.id.action_today:
                myCalendar.goToCurrentDate();
//                return super.onOptionsItemSelected(item);
                return true;
            default:
                myCalendar.showMonthViewWithBelowEvents();
//                return super.onOptionsItemSelected(item);
                return true;
            //
        }

    }





    private void showMonthViewWithBelowEvents() {

        myCalendar.showMonthViewWithBelowEvents();


        myCalendar.setOnDateClickListener(new OnDateClickListener() {
            @Override
            public void onClick(Date date) {
                Log.e("date", String.valueOf(date));


                String date1=new SimpleDateFormat("dd-MM-yyyy").format(new Date());
                Toast.makeText(getActivity(),String.valueOf(date),Toast.LENGTH_SHORT).show();
            }

            @Override
            public void onLongClick(Date date) {
                Log.e("date", String.valueOf(date));
            }
        });

    }




    private void showAgendaView() {

        myCalendar.showAgendaView();

        myCalendar.setOnDateClickListener(new OnDateClickListener() {
            @Override
            public void onClick(Date date) {
                Log.e("date", String.valueOf(date));
            }

            @Override
            public void onLongClick(Date date) {
                Log.e("date", String.valueOf(date));
            }
        });

    }



    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception){
        String msg = "";

        if (isSuccessful) {
            try {
               myCalendar.deleteAllEvent();
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
                        int api_title_int=Integer.parseInt(api_title);
                        int api_startTime_HOUR = Integer.valueOf(api_startTime.substring(0, 2));
                        int api_endTime_HOUR = Integer.valueOf(api_endTime.substring(0, 2));
                        int api_startDate_Year = Integer.valueOf(api_startDate.substring(0, 4));
                        int api_startDate_Month = Integer.valueOf(api_endDate.substring(5, 7));
                        int api_startDate_Day = Integer.valueOf(api_startDate.substring(8));
                        int api_endDate_Month = Integer.valueOf(api_endDate.substring(5,7));

                        int api_startTime_MINUTE = Integer.valueOf(api_startTime.substring(3, 5));
                        int api_endTime_MINUTE = Integer.valueOf(api_endTime.substring(3, 5));
                        int duration = 0;
                        int api_id_int=Integer.parseInt(api_id);

                        if (api_endTime_HOUR >= api_startTime_HOUR) {
                            duration = getDuration(api_startTime, api_endTime);
                        } else {
                            Toast.makeText(getActivity(), "End time can't be less than start time", Toast.LENGTH_SHORT).show();
                        }


                        int square=api_teacherid_int*api_teacherid_int;
                        int sum=api_teacherid_int+api_teacherid_int;
                        String temp_square=String.valueOf(square);
                        String temp_sum=String.valueOf(sum);

                        if(temp_square.length()<3){temp_square=temp_square+"00";}
                        if(temp_sum.length()<3){temp_sum=temp_sum+"00";}
                        temp_square=temp_square.substring(0,3);
                        temp_sum=temp_sum.substring(0,3);



                        myCalendar.addEvent(date_format(api_startDate),api_startTime_HOUR+":"+api_startTime_MINUTE,
                                api_endTime_HOUR+":"+api_endTime_MINUTE,
                                api_teacherName+"/"+api_teacherid+"@"+api_title+"#"+api_id ,R.drawable.golden_circle);





                       // myCalendar.addEvent("14-05-2017", "8:30", "8:45", "Today Event 3",R.mipmap.ic_launcher);



                        /*this.events.add(event1);*/
//                        myCalendar.addEvent(api_startDate,api_startTime,api_endTime,api_teacherName,R.mipmap.ic_launcher);


                    }}
                for (int k=0;k<response.getJSONArray(2).length();k++){
                    String name=response.getJSONArray(2).getJSONObject(k).getString("name");
                    String imageURL=response.getJSONArray(2).getJSONObject(k).getString("imageUrl");
                    String api_title1 = response.getJSONArray(2).getJSONObject(k).getString("title");
                    String api_startDate1 = response.getJSONArray(2).getJSONObject(k).getString("startDate");
                    String api_startTime1 = response.getJSONArray(2).getJSONObject(k).getString("startTime");
                    String api_endTime1 = response.getJSONArray(2).getJSONObject(k).getString("endTime");
                    String api_eventId1 = response.getJSONArray(2).getJSONObject(k).getString("eventId");
                    int api_startTime_HOUR1 = Integer.valueOf(api_startTime1.substring(0, 2));
                    int api_endTime_HOUR1 = Integer.valueOf(api_endTime1.substring(0, 2));

                    int api_startTime_MINUTE1 = Integer.valueOf(api_startTime1.substring(3, 5));
                    int api_endTime_MINUTE1 = Integer.valueOf(api_endTime1.substring(3, 5));
                    myCalendar.addEvent(date_format(api_startDate1),api_startTime_HOUR1+":"+api_startTime_MINUTE1,
                            api_endTime_HOUR1+":"+api_endTime_MINUTE1,
                            name+"/"+imageURL+"@"+api_title1+"#"+api_eventId1 ,R.drawable.golden_circle);
                }

            } catch (JSONException e) {
                e.printStackTrace();
                msg = "error";
                Toast.makeText(getActivity(), "error", Toast.LENGTH_SHORT).show();
            }

        } else {
            Toast.makeText(getActivity(), "can't connect to server", Toast.LENGTH_SHORT).show();
        }
        if(AppConstants.isShowMonthWithBellowEvents){
        showMonthViewWithBelowEvents();}
        else if(AppConstants.isAgenda){
            showAgendaView();}
        else {showMonthViewWithBelowEvents();
        }
        return;
    }


    public interface OnFragmentInteractionListener {
        // TODO: Update argument type and name
        void onFragmentInteraction(Uri uri);
    }
    @Override
    public void onResume(){
        super.onResume();
        AppConstants.isShowMonthWithBellowEvents = false;
        //OnResume Fragment

        remotecall();
        //showMonthViewWithBelowEvents();
        //  Toast.makeText(getContext(), "resumed", Toast.LENGTH_SHORT).show();

    }
    public void remotecall() {
        token=fetchman();
        new RemoteHelper(getContext().getApplicationContext()).Slot_Details(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token);
        return;
    }
    public String fetchman() {

        mydb = new DBHelper(getActivity());
        //To retrive information on opening the edit profile page
        String[] mProjection =
                {
                        UserContract.UserDetailEntry.COLUMN_ID,    // Contract class constant for the _ID column name
                        UserContract.UserDetailEntry.CoLUMN_TOKEN, // Contract class constant for the locale column name

                };
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        String[] mSelectionArgs = {"1"};
        Cursor mCursor;
        String mSortOrder = null;

        mCursor = getActivity().getContentResolver().query(
                UserContract.BASE_CONTENT_URI_Full,  // The content URI of the words table
                mProjection,                       // The columns to return for each row
                mSelectionClause,                   // Either null, or the word the user entered
                mSelectionArgs,                    // Either empty, or the string the user entered
                mSortOrder);
        if (mCursor.getCount() > 0) {
            //Search is successful
            // Insert code here to do something with the results
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex_token = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TOKEN);

            while (mCursor.moveToNext()) {
                // Insert code here to process the retrieved word.
                token = mCursor.getString(mCursorColumnIndex_token);

                // end of while loop
            }
        }
        mCursor.close();
        mydb.close();
        return token;
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
    private String date_format(String start_date){
        String date1="";

          //start_date="2017-06-21"
        SimpleDateFormat input = new SimpleDateFormat("yy-mm-dd");
        SimpleDateFormat output = new SimpleDateFormat("dd-mm-yyyy");
        try {
            Date date= input.parse(start_date);                 // parse input
             date1=output.format(date);    // format output
        } catch (ParseException e) {
            e.printStackTrace();
        }

        return date1;
    }
}
