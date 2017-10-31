package com.eurovisionedusolutions.android.rackup;

import android.content.Intent;
import android.database.Cursor;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.format.DateUtils;
import android.view.DragEvent;
import android.view.MotionEvent;
import android.view.View;
import android.widget.ImageView;
import android.widget.Toast;



import org.json.JSONArray;
import org.json.JSONException;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

import static com.eurovisionedusolutions.android.rackup.R.id.viewPager;
import static java.util.Collections.addAll;

public class ImageFeedViewPager extends AppCompatActivity implements RemoteCallHandler {
    public static final String IMAGEVIEWLIST = "image_view_list";
    public static final String CURRENTPOSITION = "current_position";
    private List<EventModel_Feed> eventModel_feeds;
    private CustomPagerAdapter customPagerAdapter;
    private int current_position;
   private String token;
    private boolean enabled = false;
    private int mDataSize = 0;
    // stores the first X position in the dataset
    private long mDataStartX = 0;
    // stores the last X position in the dataset
    private long mDataEndX = 0;
    private ViewPager viewPager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_image_feed_view_pager);
        Intent intent = getIntent();
        eventModel_feeds = (List<EventModel_Feed>) intent.getSerializableExtra(IMAGEVIEWLIST);
        current_position = (int)intent.getIntExtra(CURRENTPOSITION,0);
        customPagerAdapter = new CustomPagerAdapter(getSupportFragmentManager(), this, eventModel_feeds, current_position);
        viewPager = (ViewPager) findViewById(R.id.viewPager);
        viewPager.setAdapter(customPagerAdapter);
        viewPager.setCurrentItem(current_position);


        viewPager.setOnPageChangeListener(new ViewPager.OnPageChangeListener() {
            @Override
            public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {

            }

            @Override
            public void onPageSelected(int position) {
                if(position == 0){
//                    Toast.makeText(getApplicationContext(), "This is my Toast message!", Toast.LENGTH_LONG).show();
//                    new RemoteHelper(getApplicationContext()).FeedActivity(ImageFeedViewPager.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, fetchman(), " ");
                }
                else if(position == eventModel_feeds.size()-1){
//                    Toast.makeText(getApplicationContext(), "This is my Toast final message!", Toast.LENGTH_LONG).show();
                    new RemoteHelper(getApplicationContext()).FeedActivity(ImageFeedViewPager.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, fetchman(), eventModel_feeds.get(position).getId());
                }
//                else{
//                 /   Toast.makeText(getApplicationContext(), "This is my Toast final message!" + position, Toast.LENGTH_LONG).show();
//                }
            }

            @Override
            public void onPageScrollStateChanged(int state) {

            }
        });


    }




    public String fetchman() {

        DBHelper mydb;
        mydb = new DBHelper(getApplicationContext());
        String[] mProjection = new String[]{UserContract.UserDetailEntry.COLUMN_ID, UserContract.UserDetailEntry.CoLUMN_TOKEN};
        String[] mSelectionArgs = new String[]{"1"};
        Cursor mCursor = getApplicationContext().getContentResolver().query(UserContract.BASE_CONTENT_URI_Full, mProjection, "_id=?", mSelectionArgs, null);
        if (mCursor.getCount() > 0) {
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex_token = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TOKEN);
            while (mCursor.moveToNext()) {
                token = mCursor.getString(mCursorColumnIndex_token);
            }
        }
        mCursor.close();
        mydb.close();
        return token;
    }

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) throws JSONException {
        if (isSuccessful) {
            try {

                ArrayList<EventModel_Feed> itemsLocal1 = new ArrayList();
                for (int i = 0; i < response.getJSONArray(0).length(); i++) {
                    String id = response.getJSONArray(0).getJSONObject(i).getString(DBHelper.CONTACTS_COLUMN_ID);
                    String title1 = response.getJSONArray(0).getJSONObject(i).getString("title");
                    String date1 = response.getJSONArray(0).getJSONObject(i).getJSONObject("created_at").getString("date");
                    String date2 = DateUtils.getRelativeTimeSpanString(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(date1).getTime(), System.currentTimeMillis(), 86400000).toString();
                    String image_url = response.getJSONArray(0).getJSONObject(i).getString("filePath");
                    ArrayList<EventModel_Feed> arrayList = itemsLocal1;
                    arrayList.add(new EventModel_Feed(title1, date2, response.getJSONArray(0).getJSONObject(i).getString("description"), image_url, id));
                }
                int oldposition = eventModel_feeds.size();
                this.eventModel_feeds.addAll(itemsLocal1);
                if (((EventModel_Feed) this.eventModel_feeds.get(0)).getTime() == "HHH") {
                    this.eventModel_feeds.remove(0);
                }

                customPagerAdapter.notifyDataSetChanged();

            } catch (Exception e) {
                e.printStackTrace();
            }
        }

    }
}
