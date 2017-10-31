package com.eurovisionedusolutions.android.rackup;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.support.v4.app.NotificationCompat;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;

import static android.support.v4.app.NotificationManagerCompat.IMPORTANCE_HIGH;

public class PushReceiverIntentService extends IntentService implements RemoteCallHandler {
    public static String  token="";
    public int message_int;
    DBHelper mydb;

    private String startDate, endDate, startDateTime,endDateTime, teacherName,
            title,message,eventId,event,teacherId,reason,request_type,teacherContact;
    private int event_int,event_int1,api_event_int;

    /**
     * Creates an IntentService.  Invoked by your subclass's constructor.
     *
     * @param name Used to name the worker thread, important only for debugging.
     */
    public PushReceiverIntentService(String name) {
        super(name);
    }

    public PushReceiverIntentService() {
        super("PushReceiverIntentService");
    }


    @Override
    protected void onHandleIntent(Intent intent) {
        Bundle data = intent.getExtras();

        //Getting the message from the bundle
        int d= data.getInt("type");
        if(data!=null) {
            message = data.getString("message");
            event = data.getString("eventId");
            if(event!=null)
                api_event_int = Integer.parseInt(event);
            else
                api_event_int = 0;


        }
        if(d == 2)
        {
            Uri sound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
            Bitmap icon = BitmapFactory.decodeResource(this.getResources(), R.mipmap.ic_launcher);
            NotificationCompat.Builder noBuilder = new NotificationCompat.Builder(this)

                    .setContentText(message)
                    .setSmallIcon(R.drawable.ic_notifications_black_24dp)
//                    .setSmallIcon(R.mipmap.ic_launcher)
                    .setLargeIcon(icon)
                    .setAutoCancel(true)
                    .setContentTitle("Photo List Updated")
//                    .setContentIntent(pendingIntent)
                    .setColor(getResources().getColor(R.color.colorPrimary))
                    .setSound(sound);

            noBuilder.setVibrate(new long[] { 0, 200, 200, 200, 200, 200 });

            NotificationManager notificationManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);
            notificationManager.notify(0, noBuilder.build()); //0 = ID of notification
//            GcmBroadcastReceiver.completeWakefulIntent(intent);


        }
        else {
            //api_event_int=Integer.valueOf("event");
            //message_int=Integer.valueOf(message);
            //Displaying a notiffication with the message
            token = fetchman();
            new RemoteHelper(getApplicationContext()).Slot_Details(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, token);
        }

    }

    //This method is generating a notification and displaying the notification
    private void sendNotification(String message)
    {
        Intent intent = new Intent(this, Notification_activity.class);
        intent.putExtra("title",title);
        intent.putExtra("startTime", startDateTime);
        intent.putExtra("endTime", endDateTime);
        intent.putExtra("Id", event);
        intent.putExtra("reason",reason);
        intent.putExtra("requestType", request_type);
        intent.putExtra("teacherContact",teacherContact);
        intent.putExtra("Name", teacherName);
        intent.putExtra("Date", startDate);
        intent.putExtra("teacherId",teacherId);
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
//        startActivity(intent);

        // intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

        int requestCode = 0;
        PendingIntent pendingIntent = PendingIntent.getActivity(this, requestCode, intent, PendingIntent.FLAG_ONE_SHOT);
        Uri sound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        Bitmap icon = BitmapFactory.decodeResource(this.getResources(), R.mipmap.ic_launcher);

        NotificationCompat.Builder noBuilder = new NotificationCompat.Builder(this)

                .setContentText(message)
                .setSmallIcon(R.drawable.ic_notifications_black_24dp)
                .setPriority(IMPORTANCE_HIGH)
                .setStyle(new NotificationCompat.BigTextStyle().bigText(message))
                .setAutoCancel(true)
                .setLargeIcon(icon)
                .setContentTitle("Appointment Status")
                .setContentIntent(pendingIntent)
                .setColor(getResources().getColor(R.color.colorPrimary))
                .setSound(sound);

        NotificationManager notificationManager = (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);
        notificationManager.notify(0, noBuilder.build()); //0 = ID of notification
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

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception)  {
        if(isSuccessful){

            try {
                for (int i = 0; i < response.getJSONArray(1).length(); i++) {
                    event_int = Integer.valueOf(response.getJSONArray(1).getJSONObject(i).getString("id"));
                    if (event_int == api_event_int) {
                        event = response.getJSONArray(1).getJSONObject(i).getString("id");
                        startDateTime = response.getJSONArray(1).getJSONObject(i).getString("startTime");
                        endDateTime = response.getJSONArray(1).getJSONObject(i).getString("endTime");
                        startDate = response.getJSONArray(1).getJSONObject(i).getString("startDate");
                        teacherName = response.getJSONArray(1).getJSONObject(i).getString("teacherName");
                        title = response.getJSONArray(1).getJSONObject(i).getString("title");
                        teacherId=response.getJSONArray(1).getJSONObject(i).getString("teacherId");
                        reason=response.getJSONArray(1).getJSONObject(i).getString("reason");
                        request_type=response.getJSONArray(1).getJSONObject(i).getString("requestType");
                        teacherContact=response.getJSONArray(1).getJSONObject(i).getString("teacherContact");
                        break;

                    } else {
                        // Toast.makeText(this, "No such event", Toast.LENGTH_SHORT).show();
                    }
                }
            } catch (JSONException e) {
                e.printStackTrace();
                Toast.makeText(this, "Event Id in invalid", Toast.LENGTH_SHORT).show();
            }


            sendNotification(message);
        } else {  Toast.makeText(this, "Server Error", Toast.LENGTH_SHORT).show();}
    }

}
