package com.eurovisionedusolutions.android.rackup;

import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.support.annotation.RequiresApi;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.GridLayout;
import android.widget.GridView;
import android.widget.LinearLayout;
import android.widget.ListView;
import com.eurovisionedusolutions.android.rackup.UserContract_Video_Details.UserDetailEntry;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class Test_fragment extends Fragment {
    int b = 0;
    int[] flags = new int[0];
    private String[] mFaculty = new String[]{"Faculty_1", "Faculty_1", "Faculty_1", "Faculty_1", "Faculty_1"};
    public String[] mNames = new String[]{"Yoga11", "Rhymes11"};
    private String[] mURL = new String[100];
    private String[] mtoken = new String[100];
    DBHelper mydb;

    @RequiresApi(api = 23)
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        List<HashMap<String, String>> aList = new ArrayList();
        fetchman(getArguments().getString("key"));
        for (int i = 0; i < this.b; i++) {
            HashMap<String, String> hm = new HashMap();
            hm.put("txt",  this.mtoken[i]);
            hm.put("cur", "creator : faculty1");
            hm.put("flag", this.mURL[i]);
            aList.add(hm);
        }
        this.b = 0;
        String[] from = new String[]{"flag", "txt", "cur"};
        int[] to = new int[]{R.id.flag, R.id.txt, R.id.cur};
        final View v = inflater.inflate(R.layout.fragment_top_rated, container, false);
        GridView list = (GridView) v.findViewById(R.id.listView1);
        list.setAdapter(new CustomAdapter(getActivity().getBaseContext(), aList, R.layout.listview_layout, from, to));
        list.setOnItemClickListener(new OnItemClickListener() {
            public void onItemClick(AdapterView<?> adapterView, View view, int position, long id) {
                GridLayout layout = (GridLayout) v.findViewById(R.id.layout);
                Intent aa = new Intent(Test_fragment.this.getActivity(), FullscreenDemoActivity.class);
                aa.putExtra("videoID", Test_fragment.this.mURL[position]);
                Test_fragment.this.startActivity(aa);
            }
        });
        return v;
    }

    public void fetchman(String tab) {
        this.mydb = new DBHelper(getContext());
        String[] mProjection = new String[]{UserDetailEntry.F_key, "name", UserDetailEntry.Video_URL};
        String[] mSelectionArgs = new String[]{tab};
        Cursor mCursor = getContext().getContentResolver().query(UserContract_Video_Details.BASE_CONTENT_URI_Full, mProjection, "foreign_key=?", mSelectionArgs, null);
        if (mCursor.getCount() > 0) {
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserDetailEntry.F_key);
            int mCursorColumnIndex_VideoName = mCursor.getColumnIndex("name");
            int mCursorColumnIndex_VideoURL = mCursor.getColumnIndex(UserDetailEntry.Video_URL);
            while (mCursor.moveToNext()) {
                String t = mCursor.getString(mCursorColumnIndex_VideoName);
                String l = mCursor.getString(mCursorColumnIndex_VideoURL);
                this.mtoken[this.b] = t;
                this.mURL[this.b] = l;
                this.b++;
            }
        }
        mCursor.close();
        this.mydb.close();
    }
}
