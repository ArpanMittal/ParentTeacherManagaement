package com.eurovisionedusolutions.android.rackup;

import android.app.Notification;
import android.database.Cursor;
import android.graphics.Color;
import android.graphics.Movie;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.DividerItemDecoration;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static android.R.string.no;

public class Video_Call extends Fragment implements RemoteCallHandler {
    private RecyclerView mRecyclerView;
    private NotificationAdapter mAdapter;
    private View progressBar;
    private DBHelper mydb;
    private String token;
    private ImageView imageView;
    private List<NotificationList> notificationList = new ArrayList<>();
    public static Video_Call newInstance() {
        return new Video_Call();
    }



    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.video_call, container, false);
        Toolbar toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        progressBar = view.findViewById(R.id.dashboard_progress);
        imageView = (ImageView) view.findViewById(R.id.imageView5);
        toolbar.setTitle("Notifications");
        toolbar.setTitleTextColor(getResources().getColor(R.color.black));
        mRecyclerView = (RecyclerView) view.findViewById(R.id.recycler_view);
        mAdapter = new NotificationAdapter(notificationList);
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);
        mRecyclerView.setHasFixedSize(true);
        RecyclerView.LayoutManager mLayoutManager = new LinearLayoutManager(getContext());
        mRecyclerView.setLayoutManager(mLayoutManager);
        mRecyclerView.addItemDecoration(new DividerItemDecoration(getActivity(), LinearLayoutManager.VERTICAL));
        mRecyclerView.setItemAnimator(new DefaultItemAnimator());
        mRecyclerView.setAdapter(mAdapter);
        new RemoteHelper(getContext()).getNotificationList(Video_Call.this,RemoteCalls.GET_NOTIFICATION_LIST, fetchman());
//        prepareMovieData();
        return  view;
    }

//    private void prepareMovieData() {
//        NotificationList movie = new NotificationList("Mad Max: Fury Road", "Action & Adventure", "2015");
//        notificationList.add(movie);
//
//
//
//        mAdapter.notifyDataSetChanged();
//    }

    public String fetchman() {
        mydb = new DBHelper(getActivity());
        String[] mProjection = new String[]{UserContract.UserDetailEntry.COLUMN_ID, UserContract.UserDetailEntry.CoLUMN_TOKEN};
        String[] mSelectionArgs = new String[]{"1"};
        Cursor mCursor = getActivity().getContentResolver().query(UserContract.BASE_CONTENT_URI_Full, mProjection, "_id=?", mSelectionArgs, null);
        if (mCursor.getCount() > 0) {
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex_token = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TOKEN);
            while (mCursor.moveToNext()) {
                token = mCursor.getString(mCursorColumnIndex_token);
            }
        }
        mCursor.close();
        this.mydb.close();
        return token;
    }

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) throws JSONException {
        if(isSuccessful){
            switch (callFor){
                case GET_NOTIFICATION_LIST:{
                    progressBar.setVisibility(View.GONE);
                    JSONArray jsonArray = response.getJSONArray(0);
                    if(jsonArray.length() == 0){
                        imageView.setVisibility(View.VISIBLE);
                    }else{
                        mRecyclerView.setVisibility(View.VISIBLE);
                    }
                    for(int i = 0; i<jsonArray.length(); i++) {
                        JSONObject object = jsonArray.getJSONObject(i);
//                        JSONArray jsonArray1 = object.getJSONArray("message");
                        String message = object.getString("message");
                        JSONObject jsonObject = new JSONObject(message);
                        message = jsonObject.getString("message");

                        NotificationList movie = new NotificationList(message, "", "");
                        notificationList.add(movie);
                    }
                    mAdapter.notifyDataSetChanged();

                }
            }
        }

    }
}
