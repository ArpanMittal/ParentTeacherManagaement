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
package com.eurovisionedusolutions.android.rackup;

import android.database.Cursor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.Fragment;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import com.github.pwittchen.infinitescroll.library.InfiniteScrollListener;

import org.json.JSONArray;
import org.json.JSONException;

import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;


public class Feed_Activity extends Fragment implements RemoteCallHandler{
    public static Feed_Activity newInstance() {
        Feed_Activity fragment = new Feed_Activity();
        return fragment;
    }
    private static final int MAX_ITEMS_PER_REQUEST = 10;
    private int position_to_scroll=0;
    private static String prv_ID="";
    private static final int NUMBER_OF_ITEMS = 1;
    private static final int SIMULATED_LOADING_TIME_IN_MS = 1500;
    private String token="temp";
    DBHelper mydb;
    private String lastId="";
    public Toolbar toolbar;
    public RecyclerView recyclerView;
    public ProgressBar progressBar;

    private LinearLayoutManager layoutManager;
    
    private ArrayList<EventModel_Feed> ModelList;
    private int page;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        remotecall();

    }
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view= inflater.inflate(R.layout.activity_feed_, container, false);
        this.ModelList=createItems();

        initViews(view);
        initRecyclerView();
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);

        return view;
    }

    private void remotecall() {
        token=fetchman();
        lastId="";
        new RemoteHelper(getActivity()).FeedActivity(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token, lastId);
    }

    private static ArrayList<EventModel_Feed> createItems() {
        ArrayList<EventModel_Feed> itemsLocal1 = new ArrayList<>();
        for (int i = 0; i < NUMBER_OF_ITEMS; i++) {
            String prefix = i < 10 ? "0" : "";
            String title="HHH";
            itemsLocal1.add(new EventModel_Feed(title,title,title,title,"0"));

           // itemsLocal.add
        }
       return itemsLocal1;
    }


    private void initViews(View view) {
        toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        recyclerView = (RecyclerView) view.findViewById(R.id.recycler_view);
        progressBar = (ProgressBar) view.findViewById(R.id.progress_bar);
    }

    private void initRecyclerView() {
        layoutManager = new LinearLayoutManager(getContext());
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(layoutManager);
        recyclerView.setAdapter(new MyAdapter(ModelList));
        recyclerView.addOnScrollListener(createInfiniteScrollListener());
    }

    @NonNull private InfiniteScrollListener createInfiniteScrollListener() {
        return new InfiniteScrollListener(MAX_ITEMS_PER_REQUEST, layoutManager) {
            @Override public void onScrolledToEnd(final int firstVisibleItemPosition) {
               simulateLoading();
                //progressBar.setVisibility(View.VISIBLE);
              // String ID= ModelList.get(firstVisibleItemPosition).getId();
               // new RemoteHelper(getContext().getApplicationContext()).FeedActivity(Feed_Activity.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token, lastId);
                /*int start = ++page * MAX_ITEMS_PER_REQUEST;
                final boolean allItemsLoaded = start >= ModelList.size();
                if (allItemsLoaded) {
                   progressBar.setVisibility(View.GONE);
                } else {*/
                   // int end = start + MAX_ITEMS_PER_REQUEST;
                   // final ArrayList<EventModel_Feed> itemsLocal = getItemsToBeLoaded(start, end);

                String ID = ModelList.get(ModelList.size() - 1).getId();
                if(ID==prv_ID ){ progressBar.setVisibility(View.GONE);}
                else {
                    prv_ID=ID;
                    position_to_scroll = firstVisibleItemPosition;
                    new RemoteHelper(getContext().getApplicationContext()).FeedActivity(Feed_Activity.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token, ID);
                }
                  //  refreshView(recyclerView, new MyAdapter(ModelList), firstVisibleItemPosition);


                //}
            }
        };
    }

    @NonNull private ArrayList<EventModel_Feed> getItemsToBeLoaded(int start, int end) {
        String title="kjfvn";

        final ArrayList<EventModel_Feed> oldItems = ((MyAdapter) recyclerView.getAdapter()).getItems();
        final ArrayList<EventModel_Feed> itemsLocal = new ArrayList<>();
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

        try { ArrayList<EventModel_Feed> itemsLocal1 = new ArrayList<>();
            for (int i=0;i<response.getJSONArray(0).length();i++){
           String id= response.getJSONArray(0).getJSONObject(i).getString("id");
                String title1=response.getJSONArray(0).getJSONObject(i).getString("title");

                //String date=response.getJSONArray(0).getJSONObject(i).getJSONObject(2).getString("");
                String date ="a min ago";
                String image_url=response.getJSONArray(0).getJSONObject(i).getString("filePath");
                String description=response.getJSONArray(0).getJSONObject(i).getString("description");
            itemsLocal1.add(new EventModel_Feed(title1,date,description,image_url,id));
            }
             ArrayList<EventModel_Feed> oldItems = ((MyAdapter) recyclerView.getAdapter()).getItems();
//            ModelList.clear();
//            ModelList.addAll(oldItems);
            ModelList.addAll(itemsLocal1);
            if (ModelList.get(0).getTime()=="HHH"){
                ModelList.remove(0);
            }
            //progressBar.setVisibility(View.GONE);
           // refreshView(recyclerView, new MyAdapter(ModelList), firstVisibleItemPosition);
            recyclerView.setAdapter(new MyAdapter(ModelList));
            recyclerView.invalidate();
           recyclerView.scrollToPosition(position_to_scroll);
            progressBar.setVisibility(View.GONE);

        } catch (Exception e) {
            e.printStackTrace();
        }


    }

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
}
