package com.eurovisionedusolutions.android.rackup;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.net.Uri;
import android.widget.Toast;
import com.eurovisionedusolutions.android.rackup.UserContract_Video_Details.UserDetailEntry;
import java.util.ArrayList;
import org.json.JSONArray;
import org.json.JSONException;

public class VideoAPI_Call implements RemoteCallHandler {
    public static int tab_count = 0;
    private Context context;
    DBHelper mydb;
    ArrayList str = new ArrayList();
    String token = "";

    public void setStr(ArrayList str) {
        this.str = str;
    }

    public ArrayList getStr() {
        return this.str;
    }

    public VideoAPI_Call(Context context) {
        this.context = context;
    }

    public void api_Call() {
        delete();
        fetchman();
        new RemoteHelper(this.context).VideoData(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, this.token);
    }

    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        String msg = "";
        if (isSuccessful) {
            try {
                delete();
                int status = response.getJSONArray(0).getJSONObject(0).getInt("gradeId");
                tab_count = response.getJSONArray(0).length();
                int count2 = response.getJSONArray(0).getJSONObject(0).getJSONArray("categoryData").length();
                String name = response.getJSONArray(0).getJSONObject(0).getString("contentName");
                String status1 = String.valueOf(status);
                for (int i = 0; i < response.getJSONArray(0).length(); i++) {
                    this.str.add(i, response.getJSONArray(0).getJSONObject(i).getString("contentName"));
                    insert_1(i, response.getJSONArray(0).getJSONObject(i).getString("contentName"));
                    ArrayList inner = new ArrayList();
                    for (int j = 0; j < response.getJSONArray(0).getJSONObject(i).getJSONArray("categoryData").length(); j++) {
                        insert_2(i, response.getJSONArray(0).getJSONObject(i).getJSONArray("categoryData").getJSONObject(j).getString("categoryName"), getviewkey(response.getJSONArray(0).getJSONObject(i).getJSONArray("categoryData").getJSONObject(j).getString(UserDetailEntry.Video_URL)));
                    }
                    this.str.add(inner);
                }
                VideoAPI_Call vid1 = new VideoAPI_Call(this.context);
                Tab_fragment tab_frag = new Tab_fragment();
                vid1.setStr(this.str);
                vid1.getStr();
                return;
            } catch (JSONException e) {
                e.printStackTrace();
                msg = "error";
                return;
            }
        }
        Toast.makeText(this.context, "can't connect to server", 1).show();
    }

    private String getviewkey(String viewkey) {
        int position;
        if (viewkey.contains("youtu.be")) {
            position = viewkey.indexOf("e/") + 2;
            return viewkey.substring(position, position + 11);
        }
        position = viewkey.indexOf("v=") + 2;
        return viewkey.substring(position, position + 11);
    }

    private void insert_1(int i, String category) {
        ContentValues mNewValues = new ContentValues();
        mNewValues.put(UserContract_Video_Category.UserDetailEntry.ID, Integer.valueOf(i));
        mNewValues.put(UserContract_Video_Category.UserDetailEntry.Category, category);
        Uri mNewUri = this.context.getContentResolver().insert(UserContract_Video_Category.BASE_CONTENT_URI_Full, mNewValues);
    }

    private void insert_2(int i, String category, String URL) {
        ContentValues mNewValues = new ContentValues();
        mNewValues.put(UserDetailEntry.F_key, Integer.valueOf(i));
        mNewValues.put("name", category);
        mNewValues.put(UserDetailEntry.Video_URL, URL);
        Uri mNewUri = this.context.getContentResolver().insert(UserContract_Video_Details.BASE_CONTENT_URI_Full, mNewValues);
    }

    public void fetchman() {
        this.mydb = new DBHelper(this.context);
        String[] mProjection = new String[]{UserContract.UserDetailEntry.COLUMN_ID, UserContract.UserDetailEntry.CoLUMN_TOKEN};
        String[] mSelectionArgs = new String[]{"1"};
        Cursor mCursor = this.context.getContentResolver().query(UserContract.BASE_CONTENT_URI_Full, mProjection, "_id=?", mSelectionArgs, null);
        if (mCursor.getCount() > 0) {
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex_token = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TOKEN);
            while (mCursor.moveToNext()) {
                this.token = mCursor.getString(mCursorColumnIndex_token);
            }
        }
        mCursor.close();
        this.mydb.close();
    }

    public void delete() {
        this.mydb = new DBHelper(this.context);
        int rows = this.context.getContentResolver().delete(UserContract_Video_Category.BASE_CONTENT_URI_Full, null, null);
        int rows2 = this.context.getContentResolver().delete(UserContract_Video_Details.BASE_CONTENT_URI_Full, null, null);
        this.mydb.close();
    }
}
