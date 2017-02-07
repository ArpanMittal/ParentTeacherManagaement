package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/2/2017.
 */

import android.content.Context;
import android.content.SharedPreferences;



import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.util.ArrayMap;



/**
 * Created by arpan on 8/16/2016.
 */
public class SharedPrefrence
{
    private static final String ACCESS_TOKEN="Access_Token";
    private static final String REFRESH_TOKEN="Refresh_Token";
    public static final String PREFS_NAME = "AOP_PREFS";
    public static final String USER_NAME="User_Name";
    public static final String USER_EMAIL="User_Email";
    public static final String GOOGLE_USER="Google_User";
    public static final String USER_PASSWORD="User_Password";
    public static final String USER_PIC="User_Pic";
    public static final String SHOW_TOUR_GUIDE="Tour_Guide";

    public static final String RECENT_CHAPTER_ID="Chapter Id";
    public static final String RECENT_CHAPTER_NAME="Chapter Name";
    public static final String RECENT_EXAM_ID="Exam Id";
    public static final String RECENT_TOPIC_POSITION="Topic Position";

    public void saveAccessToken(Context context, String accessToken, String refreshToken)
    {
        //save access token and refresh token
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.putString(ACCESS_TOKEN,accessToken);
        editor.putString(REFRESH_TOKEN,refreshToken);
        editor.apply();

    }

    public void saveGoogleLoginStatus(Context context)
    {
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.putBoolean(GOOGLE_USER,true);
        editor.apply();
    }

    public void saveTour_Guide_Mode(Context context,String tour_guide)
    {
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.putString(SHOW_TOUR_GUIDE,tour_guide);
        editor.apply();
    }

    public void saveUserCredentials(Context context,String email,String password,String name,String pic)
    {
        //save user credentials
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.putString(USER_NAME,name);
        editor.putString(USER_EMAIL,email);
        editor.putString(USER_PASSWORD,password);
        editor.putString(USER_PIC,pic);
        editor.apply();
    }
    public boolean getGoogleUser(Context context)
    {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getBoolean(GOOGLE_USER,false);
    }
    public String getUserEmail(Context context)
    {
        //return email
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(USER_EMAIL,null);
    }
    public String getShowTourGuide(Context context)
    {
        //return email
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(SHOW_TOUR_GUIDE,null);
    }
    public String getUserPassword(Context context)
    {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(USER_PASSWORD,null);
    }
    public String getAccessToken(Context context)
    {
        // retreive access token
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(ACCESS_TOKEN,null);
    }
    public String getRefreshToken(Context context)
    {
        // retereive refresh token
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(REFRESH_TOKEN,null);
    }

    public void logout(Context context)
    {
        //save access token and refresh token
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.remove(ACCESS_TOKEN);
        editor.remove(REFRESH_TOKEN);
        editor.remove(USER_NAME);
        editor.remove(USER_EMAIL);
        editor.remove(USER_PASSWORD);
        editor.remove(USER_PIC);
        editor.remove(GOOGLE_USER);
        editor.apply();

    }

    public String getUserName(Context context) {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(USER_NAME,"");
    }

    public String getUserPic(Context context) {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(USER_PIC,"");
    }

    public void saveRecentChapter(Context context,String chapterId,String chapterName,String examId)
    {
        //save user credentials
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.putString(RECENT_CHAPTER_ID,chapterId);
        editor.putString(RECENT_CHAPTER_NAME,chapterName);
        editor.putString(RECENT_EXAM_ID,examId);
        editor.putInt(RECENT_TOPIC_POSITION,0);
        editor.apply();
    }
    public void saveRecentTopic(Context context, int topicPosition)
    {
        //save user credentials
        SharedPreferences.Editor editor;
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        editor=settings.edit();
        editor.putInt(RECENT_TOPIC_POSITION,topicPosition);
        editor.apply();
    }
    public String getRecentChapterId(Context context) {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(RECENT_CHAPTER_ID,"");
    }
    public String getRecentChapterName(Context context) {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(RECENT_CHAPTER_NAME,"");
    }
    public String getRecentExamId(Context context) {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getString(RECENT_EXAM_ID,"");
    }
    public int getRecentTopicPosition(Context context) {
        SharedPreferences  settings =context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
        return settings.getInt(RECENT_TOPIC_POSITION,0);
    }
}

