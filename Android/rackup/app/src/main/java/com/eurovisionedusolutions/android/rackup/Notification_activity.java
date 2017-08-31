package com.eurovisionedusolutions.android.rackup;

import android.content.ClipData;
import android.content.ClipboardManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.w3c.dom.Text;

public class Notification_activity extends AppCompatActivity implements RemoteCallHandler{
    private EditText mstartTime,mendTime,mName,mId,mdate,mWhatsapp,mWhatsapp1,mstatus,mreason_teacher;
//    private TextView mWhatsappView,mWhatsappView1,mStatusView,mreason_teacherView,delete;
    private Button mButton_Cancel,mButton_Confirm;
   private Toolbar toolbar;
    public String reason="", contact="",token="";
    private int title_int=4;
    private int alertbox_flag=0;
    String Name1="",Id="",startTime="",endTime="",date="",TeacherId="",title="",fromActivity="",teacher_Reason="",
            teacher_contact="",request_type="";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_notificationactivity);
        mButton_Confirm=(Button) findViewById(R.id.button5);
      //  delete=(EditText)findViewById(R.id.editText3);
        //mStatusView=(TextView)findViewById(R.id.textView4);
        mButton_Cancel=(Button)findViewById(R.id.button4);
        toolbar=(Toolbar)findViewById(R.id.toolbar);
        mName =(EditText) findViewById(R.id.editText7);
        mstatus =(EditText) findViewById(R.id.editText4);
        mstartTime =(EditText) findViewById(R.id.editText5);
        mendTime=(EditText) findViewById(R.id.editText6);
        mWhatsapp=(EditText)findViewById(R.id.tt);
//        mWhatsappView=(TextView)findViewById(R.id.textView17);
        mWhatsapp1=(EditText)findViewById(R.id.editText10);
//        mWhatsappView1=(TextView)findViewById(R.id.textView18);
        mreason_teacher=(EditText)findViewById(R.id.editText9);
//        mreason_teacherView=(TextView)findViewById(R.id.textView4);
                //mWhatsappView=(TextView)findViewById(R.id.textView18);
        //mWhatsapp=(EditText) findViewById(R.id.editText10);
        mdate=(EditText) findViewById(R.id.editText8);
        setSupportActionBar(toolbar);
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);
        toolbar.setNavigationOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });

        //mstatus=(EditText)findViewById(R.id.editText);
        Bundle extras = getIntent().getExtras();
        if (extras != null) {
            title=extras.getString("title");
            Name1 = extras.getString("Name");
            TeacherId=extras.getString("teacherId");
            teacher_Reason=extras.getString("reason");
            teacher_contact=extras.getString("teacherContact");
            request_type=extras.getString("requestType");
            Id = extras.getString("Id");
            startTime = extras.getString("startTime");
            endTime = extras.getString("endTime");
            date = extras.getString("Date");
        }
        mName.setEnabled(false);

        mstartTime.setEnabled(false);
        mendTime.setEnabled(false);
        mdate.setEnabled(false);
        mstatus.setEnabled(false);
        mWhatsapp.setFocusable(false);
        mWhatsapp.setClickable(true);
        mWhatsapp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(title_int==2){
                    Intent intent = new Intent(Intent.ACTION_DIAL);
                    intent.setData(Uri.parse("tel:"+teacher_contact));
                    startActivity(intent);
                }
            }
        });
        mWhatsapp.setEnabled(false);
        mreason_teacher.setEnabled(false);

        mWhatsapp.setVisibility(View.GONE);
       // mWhatsappView.setVisibility(View.GONE);
       /* mreason.setVisibility(View.GONE);
        mReasonView.setVisibility(View.GONE);*/
        mButton_Confirm.setVisibility(View.GONE);
        mButton_Cancel.setVisibility(View.GONE);
        mreason_teacher.setVisibility(View.GONE);
//        mreason_teacherView.setVisibility(View.GONE);
        mWhatsapp1.setVisibility(View.GONE);
//        mWhatsappView1.setVisibility(View.GONE);

        mName.setText(Name1);
        mstartTime.setText(startTime);
        mendTime.setText(endTime);
        mdate.setText(date);



        if(title!="" || title!=null){
        title_int = Integer.parseInt(title);
        }
        if(title_int==1 ){
            mstatus.setText("Awaited");
            mWhatsapp.setVisibility(View.VISIBLE);
//            mWhatsappView.setVisibility(View.VISIBLE);
            mButton_Confirm.setVisibility(View.VISIBLE);
            mButton_Cancel.setVisibility(View.VISIBLE);
            mreason_teacher.setVisibility(View.VISIBLE);
//            mreason_teacherView.setVisibility(View.VISIBLE);
            mWhatsapp1.setVisibility(View.VISIBLE);
           // mWhatsapp1.setBackgroundResource(R.color.edittext_background);
//            mWhatsappView1.setVisibility(View.VISIBLE);
            mWhatsapp.setText(teacher_contact);
            mWhatsapp.setVisibility(View.VISIBLE);
            mWhatsapp.setFocusable(true);
            mWhatsapp.requestFocus();
//            mWhatsapp1.setHint("Enter Whatsapp Number");
            mreason_teacher.setText(teacher_Reason);
            mreason_teacher.setEnabled(false);
          //  mreason_teacherView.setText("Reason given");

        }

       else  if(title_int==2){

            mstatus.setText("Confirmed");

            /*mreason.setVisibility(View.VISIBLE);
            mReasonView.setVisibility(View.VISIBLE);*/
            mButton_Cancel.setVisibility(View.VISIBLE);
            mWhatsapp.setVisibility(View.VISIBLE);
            mWhatsapp.setText(teacher_contact);
//            mWhatsappView.setVisibility(View.VISIBLE);
            ClipboardManager clipboard = (ClipboardManager) getSystemService(Context.CLIPBOARD_SERVICE);
            ClipData clip = ClipData.newPlainText("",teacher_contact);
            clipboard.setPrimaryClip(clip);
            mWhatsapp.setClickable(true);
            Toast.makeText(this,"Whatsapp Number Copied to clipboard",Toast.LENGTH_SHORT);


        }
        else if (title_int==3 ){
            mstatus.setText("Cancelled");
            mreason_teacher.setVisibility(View.VISIBLE);
            //mreason_teacherView.setVisibility(View.VISIBLE);
            mreason_teacher.setText(teacher_Reason);
            mreason_teacher.setEnabled(false);
            //mreason_teacherView.setText("Reason for \r\n cancellation");

        }
        else if (title_int==1 && request_type=="Parent Request"){

            mButton_Confirm.setVisibility(View.VISIBLE);
            mButton_Cancel.setVisibility(View.VISIBLE);
            mWhatsapp.setVisibility(View.VISIBLE);
//            mWhatsappView.setVisibility(View.VISIBLE);
//            mWhatsappView.setVisibility(View.VISIBLE);
        }
        else if (title_int==4 ){
            mstatus.setText("Invalid/No such event found");

        }
        else {
            mstatus.setText("unknown");
        }

        token=GCMPushReceiverService.token;

        mButton_Confirm.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                    contact=mWhatsapp1.getText().toString().trim();
                    if(contact.length()==10){
                    new RemoteHelper(getApplicationContext()).Update_Event(Notification_activity.this, RemoteCalls.UPDATE_THE_APPOINTMENT_3,
                            GCMPushReceiverService.token, Id,"2",contact,0);}
                    else { Toast.makeText(getApplication(), "Enter a valid Contact", Toast.LENGTH_SHORT).show();}

            }
    });
        mButton_Cancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
              alertbox();
                //reason=mstatus.getText().toString().trim();
            }
        });




    }
    public void alertbox(){
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


                new RemoteHelper(getApplicationContext()).Update_Event(Notification_activity.this, RemoteCalls.UPDATE_THE_APPOINTMENT_4,
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

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) throws JSONException {
        if (isSuccessful) {
            if(callFor==RemoteCalls.UPDATE_THE_APPOINTMENT_3){
                Toast.makeText(this,"Confirmed", Toast.LENGTH_LONG).show();
                finish();
            }

            else if(callFor==RemoteCalls.UPDATE_THE_APPOINTMENT_4){

                Toast.makeText(this,"Cancelled", Toast.LENGTH_LONG).show();
                finish();
            }


        } else {
            Toast.makeText(this, "server error", Toast.LENGTH_SHORT).show();
        }
        return;
    }
    public void onBackPressed() {

        finish();}
}
