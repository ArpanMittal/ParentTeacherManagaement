package com.eurovisionedusolutions.android.rackup;

import android.app.ProgressDialog;
import android.database.Cursor;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.design.widget.TabLayout.OnTabSelectedListener;
import android.support.design.widget.TabLayout.Tab;
import android.support.design.widget.TabLayout.TabLayoutOnPageChangeListener;
import android.support.v4.app.Fragment;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import com.eurovisionedusolutions.android.rackup.UserContract_Video_Category.UserDetailEntry;

public class Tab_fragment extends Fragment {
    static ProgressDialog pd;
    private String category = "";
    private int category_num = 0;

    DBHelper mydb;

    public static Tab_fragment newInstance() {
        return new Tab_fragment();
    }

    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootview = inflater.inflate(R.layout.activity_main_youtube, container, false);
//        Toolbar toolbar = (Toolbar)rootview.findViewById(R.id.toolbar);
        ((AppCompatActivity) getActivity()).setSupportActionBar((Toolbar) rootview.findViewById(R.id.toolbar));
        ImageView refreshIcon = (ImageView)rootview.findViewById(R.id.imageButton2);

        refreshIcon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new VideoAPI_Call(getContext()).api_Call();

            }
        });
        TabLayout tabLayout = (TabLayout) rootview.findViewById(R.id.tab_layout);
        for (int i = 0; i < VideoAPI_Call.tab_count; i++) {
            fetchman(i);
            tabLayout.addTab(tabLayout.newTab().setText(this.category));
        }
        tabLayout.setTabMode(0);
        tabLayout.setTabGravity(0);
        final ViewPager viewPager = (ViewPager) rootview.findViewById(R.id.pager);
        viewPager.setAdapter(new PagerAdapter(getActivity().getSupportFragmentManager(), tabLayout.getTabCount()));
        viewPager.addOnPageChangeListener(new TabLayoutOnPageChangeListener(tabLayout));
        tabLayout.setOnTabSelectedListener(new OnTabSelectedListener() {
            public void onTabSelected(Tab tab) {
                viewPager.setCurrentItem(tab.getPosition());
            }

            public void onTabUnselected(Tab tab) {
            }

            public void onTabReselected(Tab tab) {
            }
        });
        return rootview;
    }

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    public boolean onCreateOptionsMenu(Menu menu) {
        return true;
    }

    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    public void fetchman(int i) {
        String id = String.valueOf(i);
        this.mydb = new DBHelper(getContext());
        String[] mProjection = new String[]{UserDetailEntry.ID, UserDetailEntry.Category};
        String[] mSelectionArgs = new String[]{id};
        Cursor mCursor = getContext().getContentResolver().query(UserContract_Video_Category.BASE_CONTENT_URI_Full, mProjection, "id1=?", mSelectionArgs, null);
        if (mCursor.getCount() > 0) {
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserDetailEntry.ID);
            int mCursorColumnIndex_Category = mCursor.getColumnIndex(UserDetailEntry.Category);
            while (mCursor.moveToNext()) {
                this.category_num = mCursor.getInt(mCursorColumnIndex_main);
                this.category = mCursor.getString(mCursorColumnIndex_Category);
            }
        }
        mCursor.close();
        this.mydb.close();
    }
}
