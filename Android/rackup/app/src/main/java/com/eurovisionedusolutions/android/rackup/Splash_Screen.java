package com.eurovisionedusolutions.android.rackup;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Handler;
import android.util.Log;
import android.widget.Toast;

import java.util.Calendar;


public class Splash_Screen extends Activity implements RemoteCallHandler {


    DBHelper mydb;
    private String email="temp", password="temp";
    private int id = 0;
    public static Activity fa;
    public volatile int flag=0;
    public volatile long seconds1=0,seconds2=0,timer=0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
        fa = this;

        /**
         * Showing splashscreen while making network calls to download necessary
         * data before launching the app Will use AsyncTask to make http call
         */
        new PrefetchData().execute();


        Calendar c = Calendar.getInstance();
         seconds1 = c.get(Calendar.MILLISECOND);

    }

    /**
     * Async Task to make http call
     */
    private class PrefetchData extends AsyncTask<Void, Void, Void> {

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // before making http calls

        }

        @Override
        protected Void doInBackground(Void... arg0) {
            /*
             * Will make http call here This call will download required data
             * before launching the app
             * example:
             * 1. Downloading and storing in SQLite
             * 2. Downloading images
             * 3. Fetching and parsing the xml / json
             * 4. Sending device information to server
             * 5. etc.,
             */
            fetchman();
            if (id == 0) {
                insert();
                Intent intent6=new Intent(Splash_Screen.this,LoginActivity.class);
                startActivity(intent6);

            }
            else
            {
                if (isNetworkAvailable() == true) {// online, with proper email, password saved locally. Verify over network.
                    if (password!=null || password!="") {
                        new RemoteHelper(getApplicationContext()).verifyLogin(Splash_Screen.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, email, password);
                    } else {// online, with null password or email. Open Login activity
                      flag=0;

                        //Toast.makeText(getApplicationContext(), "password is null/faulty local credentials", Toast.LENGTH_LONG).show();
                    }
                } else {//offline, with proper email, password saved locally, Open edit profile page
                    if (password!=null || password!="") {
                      //  Toast.makeText(getApplicationContext(), "Offline mode login Sucessful", Toast.LENGTH_LONG).show();

                       /*Intent intent2 = new Intent(Splash_Screen.this, MainActivity.class);
                        intent2.putExtra("email_send",email);*/
                        /*startActivity(intent2);*/
                       flag=1;

                    } else {// offline with no proper email, password. Open login page
                      //  Toast.makeText(getApplicationContext(), "No locally saved credentials found else wala loop", Toast.LENGTH_LONG).show();
                        flag=0;

                        return null;
                    }

                }
            }

            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            // After completing http call
            // will close this activity and lauch main activity
            Calendar c = Calendar.getInstance();
            seconds2 = c.get(Calendar.MILLISECOND);

            timer=2000-(seconds2-seconds1);

            final Handler handler = new Handler();
            handler.postDelayed(new Runnable() {
                @Override
                public void run() {
                    // Do something after "timer" milliseconds
                    Toast.makeText(getApplicationContext(), timer+" milliseconds man..", Toast.LENGTH_LONG).show();
                    if(flag==1){
                        Intent intent1=new Intent(Splash_Screen.this,MainActivity.class);
                        intent1.putExtra("email_send",email);
                        startActivity(intent1);

                    }
                    else {
                        Intent intent2=new Intent(Splash_Screen.this,LoginActivity.class);
                        startActivity(intent2);

                    }
                }
            }, timer);
          /*  if(flag==1){
                Intent intent1=new Intent(Splash_Screen.this,MainActivity.class);
                intent1.putExtra("email_send",email);
                startActivity(intent1);
            }
            else {
                Intent intent2=new Intent(Splash_Screen.this,LoginActivity.class);
                startActivity(intent2);
            }*/


            // close this activity

        }

    }
    private void insert() {
        // Defines a new Uri object that receives the result of the insertion
        Uri mNewUri;
// Defines an object to contain the new values to insert
        ContentValues mNewValues = new ContentValues();
/*
 * Sets the values of each column and inserts the word. The arguments to the "put"
 * method are "column name" and "value"
 */
        mNewValues.put(UserContract.UserDetailEntry.COLUMN_ID, 1);
        mNewValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL, "temp");
        mNewValues.putNull(UserContract.UserDetailEntry.CoLUMN_PASSWORD);
        mNewValues.putNull(UserContract.UserDetailEntry.CoLUMN_NAME);
        mNewValues.putNull(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
        mNewValues.putNull(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);

        mNewUri = getContentResolver().insert(
                UserContract.BASE_CONTENT_URI_Full,   // the user dictionary content URI
                mNewValues                          // the values to insert
        );
      //  Toast.makeText(getApplicationContext(), "Changes done locally", Toast.LENGTH_LONG).show();
        return;
    }

    public boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    public void fetchman() {

        mydb = new DBHelper(this);
        //To retrive information on opening the edit profile page
        String[] mProjection =
                {
                        UserContract.UserDetailEntry.COLUMN_ID,    // Contract class constant for the _ID column name
                        UserContract.UserDetailEntry.CoLUMN_EMAIL, // Contract class constant for the locale column name
                        UserContract.UserDetailEntry.CoLUMN_PASSWORD
                };
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        String[] mSelectionArgs = {"1"};
        Cursor mCursor;
        String mSortOrder = null;
        mCursor = getContentResolver().query(
                UserContract.BASE_CONTENT_URI_Full,  // The content URI of the words table
                mProjection,                       // The columns to return for each row
                mSelectionClause,                   // Either null, or the word the user entered
                mSelectionArgs,                    // Either empty, or the string the user entered
                mSortOrder);
        if (mCursor.getCount() > 0) {
            //Search is successful
            // Insert code here to do something with the results
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex_email = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_EMAIL);
            int mCursorColumnIndex_password = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_PASSWORD);

            while (mCursor.moveToNext()) {
                // Insert code here to process the retrieved word.
                email = mCursor.getString(mCursorColumnIndex_email);
                password = mCursor.getString(mCursorColumnIndex_password);
                id = mCursor.getInt(mCursorColumnIndex_main);

                // end of while loop
            }

        }
        mCursor.close();
        mydb.close();


    }

    private void update(String email) {
        mydb = new DBHelper(this);
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_NAME);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL, email);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
        String[] mSelectionArgs = {"1"};
        int mRowsUpdated = getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
        String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";
        Toast.makeText(getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
        mydb.close();
    }


    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        if (isSuccessful) {
            // write code here for checking the login verificaton
            // as of now every login is successful
            Toast.makeText(getApplicationContext(), "login successful with locally saved credentials ", Toast.LENGTH_LONG).show();
            flag=1;
         /*   Intent intent = new Intent(this, MainActivity.class);
            intent.putExtra("email_send",email);
            startActivity(intent);*/
             return;

        } else {
            Toast.makeText(getApplicationContext(), "can't connect to server", Toast.LENGTH_LONG).show();
            flag=0;
           /* Intent intent1 = new Intent(this, LoginActivity.class);
           // intent1.putExtra("email_send",email);
            startActivity(intent1);*/
           return;
        }

    }
}


