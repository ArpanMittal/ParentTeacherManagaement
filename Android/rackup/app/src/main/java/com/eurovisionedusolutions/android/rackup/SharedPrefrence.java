package com.eurovisionedusolutions.android.rackup;

/**
 * Created by arpan on 8/29/2017.
 */

import android.content.Context;
import android.content.SharedPreferences;
import android.widget.LinearLayout;

import static android.os.Build.BOARD;
import static android.provider.MediaStore.Video.VideoColumns.LANGUAGE;


/**
 * Created by arpan on 8/16/2016.
 */
public class SharedPrefrence
{

    public static final String PREFS_NAME = "AOP_PREFS";
    public static final String PREVIOUSLAYOUT = "PREVIOUS_LAYOUT";
    public static final String IS_PREVIOUS_CURRENT_DATE = "IS_PREVIOUS_CURRENT";
    public static final String GCMREGITRATION_ID = "GCM_ID";
    public static final String SCHOOL_ID = "SCHOOL_ID";

    public void saveSchoolID(Context context, String school_id){
        SharedPreferences.Editor editor;
        SharedPreferences settings = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor = settings.edit();
        editor.putString(SCHOOL_ID,school_id);
        editor.apply();
    }

    public String getSchoolId(Context context){
        SharedPreferences  string =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return string.getString(SCHOOL_ID, "1");
    }

    public void savePreviousLayoutDetails(Context context, boolean is_Previous_Current, LinearLayout previousLayout) {
        SharedPreferences.Editor editor;
        SharedPreferences settings = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor = settings.edit();
        editor.putBoolean(IS_PREVIOUS_CURRENT_DATE, is_Previous_Current);
        editor.putInt(PREVIOUSLAYOUT, previousLayout.getId());
        editor.apply();
    }

    public  boolean getPreviousCurrentFlag(Context context){
        SharedPreferences  string =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return string.getBoolean(IS_PREVIOUS_CURRENT_DATE, false);
    }

    public int getPreviousId(Context context){
        SharedPreferences  string =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return string.getInt(PREVIOUSLAYOUT, 0);
    }

    public void saveGCMRegistrationId(Context context, String token) {
        SharedPreferences.Editor editor;
        SharedPreferences settings = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor = settings.edit();
        editor.putString(GCMREGITRATION_ID, token);
        editor.apply();
    }

    public String getGcmregitrationId(Context context){
        SharedPreferences  string =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return string.getString(GCMREGITRATION_ID , null);
    }





}
