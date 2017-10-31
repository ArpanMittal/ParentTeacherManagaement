package com.eurovisionedusolutions.android.rackup;

import android.content.DialogInterface;
import android.database.Cursor;
import android.graphics.Color;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.alamkanak.weekview.WeekViewEvent;

import org.json.JSONArray;
import org.json.JSONException;
import org.w3c.dom.Text;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

import static android.R.id.input;
import static android.icu.lang.UCharacter.GraphemeClusterBreak.V;

public class book_appointment extends AppCompatActivity implements RemoteCallHandler{
    private EditText mstartTime,mendTime,mName,mstatus,mdate,mreason,mWhatsapp,mTeacherWhatsapp;
   // private TextView mReasonView,mReasonView_teacher,mWhatsappView;
    private Button mButton, cancelButton;
    private Toolbar toolbar;
    public static String token = "there1";
    private boolean isghostappointment=false;

    private int title_int;
    DBHelper mydb;
    private String reason, contact;
String Name1,Id,startTime,endTime,date,TeacherId,title,fromActivity;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_notificationactivity);
      toolbar=(Toolbar)findViewById(R.id.toolbar);
//        mReasonView=(TextView)findViewById(R.id.textView17);
//        mWhatsappView=(TextView)findViewById(R.id.textView18);
        cancelButton = (Button) findViewById(R.id.button4);
        mButton=(Button) findViewById(R.id.button5);
        mName =(EditText) findViewById(R.id.editText7);
        mstatus =(EditText) findViewById(R.id.editText4);
        mstartTime =(EditText) findViewById(R.id.editText5);
        mendTime=(EditText) findViewById(R.id.editText6);
        mdate=(EditText) findViewById(R.id.editText8);
        mreason=(EditText)findViewById(R.id.editText9);
        mWhatsapp=(EditText)findViewById(R.id.editText10);
        mTeacherWhatsapp=(EditText)findViewById(R.id.tt);

        setSupportActionBar(toolbar);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        //toolbar.setTitleTextColor(getResources().getColor(R.color.white));
      toolbar.setNavigationOnClickListener(new View.OnClickListener() {
          @Override
          public void onClick(View v) {
              finish();
          }
      });
        Bundle extras = getIntent().getExtras();
        if (extras != null) {

            Name1= extras.getString("Name");
            Id=extras.getString("Id");
            startTime=extras.getString("startTime");
            endTime=extras.getString("endTime");
            date=extras.getString("Date");

        }
     Long currentTime= System.currentTimeMillis();
        Long timeInMilliseconds=1L;
        String givenDateString = date+" "+startTime;
        SimpleDateFormat sdf = new SimpleDateFormat("dd-MM-yyyy HH:mm");
        try {
            Date mDate = sdf.parse(givenDateString);
            timeInMilliseconds = mDate.getTime();
//            System.out.println("Date in milli :: " + timeInMilliseconds);
            //Toast.makeText(this, String.valueOf(timeInMilliseconds),Toast.LENGTH_SHORT);
        } catch (ParseException e) {
            e.printStackTrace();
        }

        mName.setEnabled(false);
        mstatus.setEnabled(false);
        mstartTime.setEnabled(false);
        mendTime.setEnabled(false);
        mdate.setEnabled(false);
        mTeacherWhatsapp.setEnabled(false);
        mWhatsapp.setEnabled(true);

if(Name1!=null){
        String name2  = Name1;
        int position2=name2.indexOf("@");
        int position=name2.indexOf("/");
        int position3=name2.indexOf("#");

        TeacherId=Name1.substring(position+1,position2);
        Id=Name1.substring(position3+1);
        title=Name1.substring(position2+1,position3);
        Name1=Name1.substring(0,position);}
        contact=mWhatsapp.getText().toString().trim();
        reason=mreason.getText().toString().trim();

        mName.setText(Name1);
        if(title!=null || title!="" ) {
            title_int=Integer.valueOf(title);}
       // mreason.setHint("Reason for the appointment");
//           mreason.setBackgroundResource(R.color.edittext_background);
//        mWhatsapp.setBackgroundResource(R.color.edittext_background);
        //mId.setText(Id);
        mstartTime.setText(startTime);
        mendTime.setText(endTime);
        mButton.setText("Confirm");
        mButton.setEnabled(false);
        mdate.setText(date);
        mreason.setVisibility(View.VISIBLE);
        mWhatsapp.setVisibility(View.VISIBLE);
//        mWhatsappView.setVisibility(View.VISIBLE);
//        mReasonView.setVisibility(View.VISIBLE);
        if (currentTime>timeInMilliseconds){
            isghostappointment=true;
        }
        if(title_int==2){

           mButton.setVisibility(View.VISIBLE);
            mButton.setText("Cancel Appointment?" );
            mstatus.setText("Confirmed");
            if(isghostappointment==false){
                mButton.setEnabled(true);}
            mreason.setVisibility(View.GONE);
            mWhatsapp.setVisibility(View.GONE);
            cancelButton.setVisibility(View.GONE);
//            mWhatsappView.setVisibility(View.GONE);
           // mReasonView.setVisibility(View.GONE);


        }
        else if (title_int==1){
            mButton.setText("Cancel");
            cancelButton.setText("Confirm");
            mstatus.setText("Awaited");
            getSupportActionBar().setTitle("Awaiting Appointment Details");
            if(isghostappointment==false){
                mButton.setEnabled(true);
                cancelButton.setEnabled(true);
            }else{
                cancelButton.setEnabled(false);
            }
            mWhatsapp.requestFocus();
            mreason.setVisibility(View.GONE);
//            mWhatsapp.setVisibility(View.VISIBLE);
//            cancelButton.setVisibility(View.VISIBLE);
//            mWhatsappView.setVisibility(View.GONE);
//            mReasonView.setVisibility(View.GONE);

        }
        else if(title_int==5){

            mstatus.setText("Available");
            mButton.setText("Send Request");
            if(isghostappointment==false){
                mButton.setEnabled(true);}
            mreason.setVisibility(View.VISIBLE);
            mWhatsapp.setVisibility(View.VISIBLE);
            cancelButton.setVisibility(View.GONE);
            mreason.requestFocus();

            getSupportActionBar().setTitle("Request Appointment");

//            mWhatsappView.setVisibility(View.VISIBLE);
//            mReasonView.setVisibility(View.VISIBLE);
        }
        else if(title_int==3){
            mstatus.setText("Cancelled");
            mButton.setText("Cancelled");
            cancelButton.setVisibility(View.GONE);
            getSupportActionBar().setTitle("Cancelled Appointment");
            mButton.setEnabled(false);
            mreason.setVisibility(View.GONE);
            mWhatsapp.setVisibility(View.GONE);
//            mWhatsappView.setVisibility(View.GONE);
//            mReasonView.setVisibility(View.GONE);
        }
        else if(title_int==4){
            mstatus.setText("Invalid");
            mButton.setText("Invalid");
            mButton.setEnabled(false);
            mreason.setVisibility(View.GONE);
            mWhatsapp.setVisibility(View.GONE);
//            mWhatsappView.setVisibility(View.GONE);
//            mReasonView.setVisibility(View.GONE);
        }


      token=fetchman();
        cancelButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(title_int == 1){
                    contact=mWhatsapp.getText().toString().trim();
                    if(contact.length()==10) {
                        //TODO:: confirm Appointment
                        new RemoteHelper(getApplicationContext()).Update_Event(book_appointment.this, RemoteCalls.UPDATE_THE_APPOINTMENT_3, token, Id,"2",contact,0);

                    }
//                    else{
////                        Toast.makeText(this,"there is problem with number", Toast.LENGTH_LONG).show();
//                    }
                }
            }
        });
        mButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mreason.requestFocus();

                reason=mreason.getText().toString().trim();
                contact=mWhatsapp.getText().toString().trim();
                if(contact.length()==10 && title_int==5) {

                    new RemoteHelper(getApplicationContext()).Book_Appointment(book_appointment.this, RemoteCalls.BookAppointment_Confirm,
                            token, TeacherId, Id, reason, contact);

                }
                else  if( title_int==2) {
                    alertbox();

            }
                else  if( title_int==1 ) {
                    alertbox();

                }
                else  if(title_int==4) {
                    Toast.makeText(getApplication(),"Invalid", Toast.LENGTH_LONG).show();


                }
                else if(contact.length()!=10 || title_int==5){
                    Toast.makeText(getApplication(),"Enter Valid Contact", Toast.LENGTH_LONG).show();
                }

                else{
                    Toast.makeText(getApplication(),"Error", Toast.LENGTH_LONG).show();
                }
            }
        });


    } public void alertbox(){
        LayoutInflater layoutInflaterAndroid = LayoutInflater.from(this);
        View mView = layoutInflaterAndroid.inflate(R.layout.user_input_dialog_box, null);

        AlertDialog.Builder alert = new AlertDialog.Builder(this,R.style.MyAlertDialogStyle);



        alert.setTitle("Alert");
        alert.setMessage("You are about to cancel the appointment.\r\n Are you sure?");

//// Set an EditText view to get user input
//        final EditText input = new EditText(this);
//        alert.setView(input);




        alert.setView(mView);
        final EditText userInputDialogEditText = (EditText) mView.findViewById(R.id.userInputDialog);

        alert.setPositiveButton("Ok", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int whichButton) {
                reason= "reason: "+userInputDialogEditText.getText().toString().trim();


                new RemoteHelper(getApplicationContext()).Update_Event(book_appointment.this, RemoteCalls.UPDATE_THE_APPOINTMENT_4,
                        token, Id, "3",reason);

                // Do something with value!
            }
        });

        alert.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int whichButton) {

                // Canceled.

            }
        });

        alert.show();
        return;
    }
    public void onBackPressed() {

            finish();}

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception)  {

        if (isSuccessful) {
            if(callFor==RemoteCalls.BookAppointment_Confirm){
                        Toast.makeText(this,"Request sent. Please wait for confirmation", Toast.LENGTH_LONG).show();                 finish();
                }

            else if(callFor==RemoteCalls.UPDATE_THE_APPOINTMENT_4){

                        Toast.makeText(this,"Your Appointment is  Cancelled", Toast.LENGTH_LONG).show();
                        finish();
            }
            else if(callFor==RemoteCalls.UPDATE_THE_APPOINTMENT_3){
                Toast.makeText(this,"Confirmed", Toast.LENGTH_LONG).show();
                finish();
            }


        } else {
            Toast.makeText(this, "server error", Toast.LENGTH_SHORT).show();
        }
    }
    public String fetchman() {

        mydb = new DBHelper(getApplication());
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

        mCursor = getApplication().getContentResolver().query(
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
