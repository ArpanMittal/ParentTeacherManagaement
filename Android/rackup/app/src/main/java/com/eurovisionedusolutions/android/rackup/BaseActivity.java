package com.eurovisionedusolutions.android.rackup;

/**
 * Created by arpan on 5/2/2017.
 */

import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.RectF;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.alamkanak.weekview.DateTimeInterpreter;
import com.alamkanak.weekview.MonthLoader;
import com.alamkanak.weekview.WeekView;
import com.alamkanak.weekview.WeekViewEvent;

import org.json.JSONArray;
import org.json.JSONException;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.List;
import java.util.Locale;

/**
 * This is a base activity which contains week view and all the codes necessary to initialize the
 * week view.
 * Created by Raquib-ul-Alam Kanak on 1/3/2014.
 * Website: http://alamkanak.github.io
 */
public abstract class BaseActivity extends Fragment implements RemoteCallHandler,WeekView.EventClickListener, MonthLoader.MonthChangeListener, WeekView.EventLongPressListener, WeekView.EmptyViewLongPressListener {
    private static final int TYPE_DAY_VIEW = 1;
    private static final int TYPE_THREE_DAY_VIEW = 2;
    private static final int TYPE_WEEK_VIEW = 3;
    private int mWeekViewType = TYPE_THREE_DAY_VIEW;
    private Context context;
    private int count=0;
    private List<WeekViewEvent> events = new ArrayList<WeekViewEvent>();
    DBHelper mydb;
    public static String token = "there";
    private WeekView mWeekView;
    public static int count1 = 0;

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setHasOptionsMenu(true);

    }


    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        this.setHasOptionsMenu(true);

        View view = inflater.inflate(R.layout.activity_base, container, false);
        Toolbar toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        ((AppCompatActivity) getActivity()).setSupportActionBar(toolbar);
        //setHasOptionsMenu(true);
        //setContentView(R.layout.activity_base);

        // Get a reference for the week view in the layout.
        mWeekView = (WeekView) view.findViewById(R.id.weekView);

        // Show a toast message about the touched event.
        mWeekView.setOnEventClickListener(this);

        // The week view has infinite scrolling horizontally. We have to provide the events of a
        // month every time the month changes on the week view.
        mWeekView.setMonthChangeListener(this);

        // Set long press listener for events.
        mWeekView.setEventLongPressListener(this);

        // Set long press listener for empty view
        mWeekView.setEmptyViewLongPressListener(this);

      // token= fetchman();
//token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiaXNzIjoiaHR0cDpcL1wvd2ViLnJhY2t1cGNhbWJyaWRnZS5jb21cL2FwaVwvbG9naW4iLCJpYXQiOjE0OTQzMTg5NDcsImV4cCI6MTQ5NDMyMjU0NywibmJmIjoxNDk0MzE4OTQ3LCJqdGkiOiJPa1RjUFhYWkZPYldLTUhnIn0.Ju-4nH0oGFjiDjsziq0EpRGYonqZwYfS6ya3uiD2g3M";
       remotecall();
       // new RemoteHelper(getContext().getApplicationContext()).Slot_Details(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token);

        // Set up a date time interpreter to interpret how the date and time will be formatted in
        // the week view. This is optional.
        setupDateTimeInterpreter(false);
        setHasOptionsMenu(true);
        return view;
    }
    @Override
    public void onResume(){
        super.onResume();
        //OnResume Fragment
        remotecall();
      //  Toast.makeText(getContext(), "resumed", Toast.LENGTH_SHORT).show();

    }

    public void remotecall() {
        token=fetchman();
        new RemoteHelper(getContext().getApplicationContext()).Slot_Details(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token);
    }


    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.main, menu);
        super.onCreateOptionsMenu(menu, inflater);
        //  inflater.inflate(R.menu.main,menu);
       /* getMenuInflater().inflate(R.menu.main, menu);*/

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        setupDateTimeInterpreter(id == R.id.action_week_view);
        switch (id) {
            case R.id.action_today:
                mWeekView.goToToday();
                return true;
            case R.id.action_day_view:
                if (mWeekViewType != TYPE_DAY_VIEW) {
                    item.setChecked(!item.isChecked());
                    mWeekViewType = TYPE_DAY_VIEW;
                    mWeekView.setNumberOfVisibleDays(1);

                    // Lets change some dimensions to best fit the view.
                    mWeekView.setColumnGap((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 8, getResources().getDisplayMetrics()));
                    mWeekView.setTextSize((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, 12, getResources().getDisplayMetrics()));
                    mWeekView.setEventTextSize((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, 12, getResources().getDisplayMetrics()));
                }
                return true;
            case R.id.action_three_day_view:
                if (mWeekViewType != TYPE_THREE_DAY_VIEW) {
                    item.setChecked(!item.isChecked());
                    mWeekViewType = TYPE_THREE_DAY_VIEW;
                    mWeekView.setNumberOfVisibleDays(3);

                    // Lets change some dimensions to best fit the view.
                    mWeekView.setColumnGap((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 8, getResources().getDisplayMetrics()));
                    mWeekView.setTextSize((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, 12, getResources().getDisplayMetrics()));
                    mWeekView.setEventTextSize((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, 12, getResources().getDisplayMetrics()));
                }
                return true;
            case R.id.action_week_view:
                if (mWeekViewType != TYPE_WEEK_VIEW) {
                    item.setChecked(!item.isChecked());
                    mWeekViewType = TYPE_WEEK_VIEW;
                    mWeekView.setNumberOfVisibleDays(7);

                    // Lets change some dimensions to best fit the view.
                    mWeekView.setColumnGap((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, 2, getResources().getDisplayMetrics()));
                    mWeekView.setTextSize((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, 10, getResources().getDisplayMetrics()));
                    mWeekView.setEventTextSize((int) TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_SP, 10, getResources().getDisplayMetrics()));
                }
                return true;
        }

        // return super.onOptionsItemSelected(item);
        return false;
    }

    /**
     * Set up a date time interpreter which will show short date values when in week view and long
     * date values otherwise.
     *
     * @param shortDate True if the date values should be short.
     */
    private void setupDateTimeInterpreter(final boolean shortDate) {
        mWeekView.setDateTimeInterpreter(new DateTimeInterpreter() {
            @Override
            public String interpretDate(Calendar date) {
                SimpleDateFormat weekdayNameFormat = new SimpleDateFormat("EEE", Locale.getDefault());
                String weekday = weekdayNameFormat.format(date.getTime());
                SimpleDateFormat format = new SimpleDateFormat(" M/d", Locale.getDefault());

                // All android api level do not have a standard way of getting the first letter of
                // the week day name. Hence we get the first char programmatically.
                // Details: http://stackoverflow.com/questions/16959502/get-one-letter-abbreviation-of-week-day-of-a-date-in-java#answer-16959657
                if (shortDate)
                    weekday = String.valueOf(weekday.charAt(0));
                return weekday.toUpperCase() + format.format(date.getTime());
            }

            @Override
            public String interpretTime(int hour) {
                return hour > 11 ? (hour - 12) + " PM" : (hour == 0 ? "12 AM" : hour + " AM");
            }
        });
    }

    protected String getEventTitle(String Teacher_Name) {
        return String.format(Teacher_Name);
    }

    @Override
    public void onEventClick(WeekViewEvent event, RectF eventRect) {

        String startTime, endTime, Id, Name, Date;


        Calendar calendar1 = event.getStartTime();
        Calendar calendar2 = event.getEndTime();

        startTime = String.valueOf(calendar1.get(calendar1.HOUR_OF_DAY) + ":" + calendar1.get(calendar1.MINUTE));
        endTime = String.valueOf(calendar2.get(calendar2.HOUR_OF_DAY) + ":" + calendar2.get(calendar2.MINUTE));
        Date = format(event.getStartTime());
        Id = String.valueOf(event.getId());
        Name = event.getName();


        Intent i = new Intent(getContext(), book_appointment.class);

        i.putExtra("startTime", startTime);
        i.putExtra("endTime", endTime);
        i.putExtra("Id", Id);
        i.putExtra("Name", Name);
        i.putExtra("Date", Date);
        startActivity(i);


       // Toast.makeText(getContext(), "Appointment Detail for Mr." + event.getName(), Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onEventLongPress(WeekViewEvent event, RectF eventRect) {


        Toast.makeText(getContext(), "Long pressed event: " + event.getName(), Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onEmptyViewLongPress(Calendar time) {
        Toast.makeText(getContext(), "Please select from the colored slots", Toast.LENGTH_SHORT).show();
    }


    public WeekView getWeekView() {
        return mWeekView;
    }

    public static String format(Calendar calendar) {
        SimpleDateFormat fmt = new SimpleDateFormat("dd-MMM-yyyy");
        fmt.setCalendar(calendar);
        String dateFormatted = fmt.format(calendar.getTime());
        return dateFormatted;
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

 /*   public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        String msg = "";
        getWeekView().notifyDatasetChanged();

        *//*Calendar startTime = Calendar.getInstance();
        startTime.set(Calendar.HOUR_OF_DAY, 6);
        startTime.set(Calendar.MINUTE, 30);
        startTime.set(Calendar.MONTH, 4);
        startTime.set(Calendar.YEAR, 2017);
        Calendar endTime = (Calendar) startTime.clone();
        endTime.add(Calendar.MINUTE, 180);
        endTime.set(Calendar.MONTH, 4);
        WeekViewEvent event1 = new WeekViewEvent(1, getEventTitle(startTime), startTime, endTime);
        event1.setColor(getResources().getColor(R.color.gray));
        // events.add(event1);
        this.events.add(event1);*//*

        if (isSuccessful) {
            try {

                for (int i = 0; i < response.getJSONArray(0).length(); i++) {
                    String api_id = response.getJSONArray(0).getJSONObject(i).getString("id");
                    String api_teacherid = response.getJSONArray(0).getJSONObject(i).getString("teacherId");
                    String api_teacherName = response.getJSONArray(0).getJSONObject(i).getString("teacherName");
                    String api_title = response.getJSONArray(0).getJSONObject(i).getString("title");
                    String api_startDate = response.getJSONArray(0).getJSONObject(i).getString("startDate");
                    String api_endDate = response.getJSONArray(0).getJSONObject(i).getString("endDate");
                    String api_startTime = response.getJSONArray(0).getJSONObject(i).getString("startTime");
                    String api_endTime = response.getJSONArray(0).getJSONObject(i).getString("endTime");
                    Long api_id_long = Long.valueOf(api_id);
                    int api_startTime_HOUR = Integer.valueOf(api_startTime.substring(0, 2));
                    int api_endTime_HOUR = Integer.valueOf(api_endTime.substring(0, 2));
                    int api_startDate_Year = Integer.valueOf(api_startDate.substring(0, 4));
                    int api_startDate_Month = Integer.valueOf(api_endDate.substring(5, 7));
                    int api_startDate_Day = Integer.valueOf(api_startDate.substring(9));
                    int api_endDate_Month = Integer.valueOf(api_endDate.substring(5, 7));

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
                    WeekViewEvent event1 = new WeekViewEvent(api_id_long, getEventTitle(startTime), startTime, endTime);
                    event1.setColor(getResources().getColor(R.color.gray));
                    // events.add(event1);
                    this.events.clear();
                    this.events.add(event1);
                    getWeekView().notifyDatasetChanged();

                }
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
    }*/


    public void onBackPressed() {

        if(count==1)
        { Toast.makeText(getContext(), "Press Again to exit", Toast.LENGTH_LONG).show();}

        if(count>=2){
            getActivity().finish();}
    }
}