package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 5/25/2017.
 */

/*
 * Copyright (C) 2016 Piotr Wittchen
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

import android.database.Cursor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.text.format.DateUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;

import com.github.pwittchen.infinitescroll.library.InfiniteScrollListener;

import org.json.JSONArray;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import android.database.Cursor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.text.format.DateUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.github.pwittchen.infinitescroll.library.InfiniteScrollListener;

import org.json.JSONArray;
import org.json.JSONException;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.LinkedList;
import java.util.List;


public class MyAppointments_CardView extends AppCompatActivity implements RemoteCallHandler{
    private static final int MAX_ITEMS_PER_REQUEST = 10;
    private static final int NUMBER_OF_ITEMS = 1;
    private static final int SIMULATED_LOADING_TIME_IN_MS = 1500;
    private static String prv_ID="";
    public Toolbar toolbar;
    public RecyclerView recyclerView;
    public ProgressBar progressBar;
    DBHelper mydb;
    SwipeRefreshLayout mSwipeRefreshLayout;
    private int position_to_scroll=0;
    private String token="temp";
    private String lastId="";
    private LinearLayoutManager layoutManager;
    private ArrayList<Event_Model_appointment> ModelList;
    private int page;

    public static Feed_Activity newInstance() {
        Feed_Activity fragment = new Feed_Activity();
        return fragment;
    }

    private static ArrayList<Event_Model_appointment> createItems() {
        ArrayList<Event_Model_appointment> itemsLocal1 = new ArrayList<>();
        for (int i = 0; i < NUMBER_OF_ITEMS; i++) {
            String prefix = i < 10 ? "0" : "";
            String title="111";
            itemsLocal1.add(new Event_Model_appointment(title,title,"11:00:00",title,"2017-05-25",title,title,title,title,title));

            // itemsLocal.add
        }
        return itemsLocal1;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_appointment_cardview);
        this.ModelList = createItems();
        initViews();
        initRecyclerView();
        toolbar.setTitle("Appointments");
        toolbar.setTitleTextColor(getResources().getColor(R.color.white));
//        toolbar.setNavigationIcon(getResources().getDrawable(R.drawable.ic_arrow_back_white_24dp));
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);
        remotecall();
        mSwipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                ModelList.clear();
                remotecall();
            }
        });

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                this.finish();
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }


    private void remotecall() {
        token=fetchman();
        
        new RemoteHelper(MyAppointments_CardView.this).Slot_Details(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token);
    }

    private void initViews() {
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        recyclerView = (RecyclerView) findViewById(R.id.recycler_view);
        progressBar = (ProgressBar) findViewById(R.id.progress_bar);
        mSwipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.activity_main_swipe_refresh_layout);
    }

    private void initRecyclerView() {
        layoutManager = new LinearLayoutManager(getApplicationContext());
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.setAdapter(new MyAdapter_appointment(getApplicationContext(),ModelList));
        recyclerView.addOnScrollListener(createInfiniteScrollListener());
    }

    @NonNull
    private InfiniteScrollListener createInfiniteScrollListener() {
        return new InfiniteScrollListener(MAX_ITEMS_PER_REQUEST, layoutManager) {
            @Override public void onScrolledToEnd(final int firstVisibleItemPosition) {
               // simulateLoading();
                //progressBar.setVisibility(View.VISIBLE);
                // String ID= ModelList.get(firstVisibleItemPosition).getId();
                // new RemoteHelper(getContext().getApplicationContext()).FeedActivity(Feed_Activity.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token, lastId);
                /*int start = ++page * MAX_ITEMS_PER_REQUEST;
                final boolean allItemsLoaded = start >= ModelList.size();
                if (allItemsLoaded) {
                   progressBar.setVisibility(View.GONE);
                } else {*/
                // int end = start + MAX_ITEMS_PER_REQUEST;
                // final ArrayList<Event_Model_appointment> itemsLocal = getItemsToBeLoaded(start, end);

               /* String ID = ModelList.get(ModelList.size() - 1).getId();
                if(ID==prv_ID ){ progressBar.setVisibility(View.GONE);}
                else {
                    prv_ID=ID;
                    position_to_scroll = firstVisibleItemPosition;
                    new RemoteHelper(getApplicationContext().getApplicationContext()).FeedActivity(MyAppointments_CardView.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token, ID);
                }*/
                //  refreshView(recyclerView, new MyAdapter_appointment(ModelList), firstVisibleItemPosition);


                //}
            }
        };
    }

    @NonNull private ArrayList<Event_Model_appointment> getItemsToBeLoaded(int start, int end) {
        String title="kjfvn";

        final ArrayList<Event_Model_appointment> oldItems = ((MyAdapter_appointment) recyclerView.getAdapter()).getItems();
        final ArrayList<Event_Model_appointment> itemsLocal = new ArrayList<>();
        itemsLocal.addAll(oldItems);
        return itemsLocal;
    }

    /**
     * WARNING! This method is only for demo purposes!
     * Don't do anything like that in your regular project!
     */
    private void simulateLoading() {
        new AsyncTask<Void, Void, Void>() {
            @Override protected void onPreExecute() {
                progressBar.setVisibility(View.VISIBLE);
            }

            @Override protected Void doInBackground(Void... params) {
                try {
                    Thread.sleep(SIMULATED_LOADING_TIME_IN_MS);
                } catch (InterruptedException e) {
                    Log.e("MainActivity", e.getMessage());
                }
                return null;
            }

            @Override protected void onPostExecute(Void param) {
                progressBar.setVisibility(View.GONE);
            }
        }.execute();
    }

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        if(isSuccessful){

            try { mSwipeRefreshLayout.setRefreshing(false);
                ArrayList<Event_Model_appointment> itemsLocal1 = new ArrayList<>();
                for (int i=0;i<response.getJSONArray(1).length();i++){
                    String api_id = response.getJSONArray(1).getJSONObject(i).getString("id");
                    String api_teacherid = response.getJSONArray(1).getJSONObject(i).getString("teacherId");
                    int api_teacherid_int=Integer.valueOf(api_teacherid);
                    String api_teacherName = response.getJSONArray(1).getJSONObject(i).getString("teacherName");
                    String api_title = response.getJSONArray(1).getJSONObject(i).getString("title");
                    String api_startDate = response.getJSONArray(1).getJSONObject(i).getString("startDate");
                    String api_endDate = response.getJSONArray(1).getJSONObject(i).getString("endDate");
                    String api_startTime = response.getJSONArray(1).getJSONObject(i).getString("startTime");
                    String api_endTime = response.getJSONArray(1).getJSONObject(i).getString("endTime");
                    String reason=response.getJSONArray(1).getJSONObject(i).getString("reason");
                    String requestby=response.getJSONArray(1).getJSONObject(i).getString("requestType");
                    String teacherContact=response.getJSONArray(1).getJSONObject(i).getString("teacherContact");
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


                       /* Calendar startTime = Calendar.getInstance();
                        startTime.set(Calendar.HOUR_OF_DAY, api_startTime_HOUR);
                        startTime.set(Calendar.DAY_OF_MONTH, api_startDate_Day);
                        startTime.set(Calendar.MINUTE, api_startTime_MINUTE);
                        startTime.set(Calendar.MONTH, api_startDate_Month - 1);
                        startTime.set(Calendar.YEAR, api_startDate_Year);
                        Calendar endTime = (Calendar) startTime.clone();
                        endTime.add(Calendar.MINUTE, duration);
                        endTime.set(Calendar.MONTH, api_startDate_Month - 1);
                        WeekViewEvent event1 = new WeekViewEvent(api_id_long, getEventTitle(api_teacherName), startTime, endTime);*/

                    int square=api_teacherid_int*api_teacherid_int;
                    int sum=api_teacherid_int+api_teacherid_int;
                    String temp_square=String.valueOf(square);
                    String temp_sum=String.valueOf(sum);

                    if(temp_square.length()<3){temp_square=temp_square+"00";}
                    if(temp_sum.length()<3){temp_sum=temp_sum+"00";}
                    temp_square=temp_square.substring(0,3);
                    temp_sum=temp_sum.substring(0,3);
                    String status="";

                    itemsLocal1.add(new Event_Model_appointment("Appointment",api_id,api_startTime,api_endTime,api_startDate,
                            api_title,api_teacherName,teacherContact,requestby,reason));
                }
                ArrayList<Event_Model_appointment> oldItems = ((MyAdapter_appointment) recyclerView.getAdapter()).getItems();
//            ModelList.clear();
//            ModelList.addAll(oldItems);
                ModelList.addAll(itemsLocal1);
                if (ModelList.get(0).getTitle()=="111"){
                    ModelList.remove(0);
                }
                //progressBar.setVisibility(View.GONE);
                // refreshView(recyclerView, new MyAdapter_appointment(ModelList), firstVisibleItemPosition);
                recyclerView.setAdapter(new MyAdapter_appointment(this,ModelList));
                recyclerView.invalidate();
                recyclerView.scrollToPosition(0);
                progressBar.setVisibility(View.GONE);

            } catch (Exception e) {
                e.printStackTrace();
            }


        }

    }
    public String fetchman() {

        mydb = new DBHelper(this);
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

        mCursor = this.getContentResolver().query(
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
}
