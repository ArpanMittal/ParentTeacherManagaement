package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/2/2017.
 */

import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
import android.support.v4.content.LocalBroadcastManager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.util.Log;
import android.util.Patterns;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.google.android.gms.iid.InstanceID;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


/*
 * A login screen that offers login via email/password.
 */
public class LoginActivity extends AppCompatActivity implements RemoteCallHandler {
    VideoAPI_Call vid=new VideoAPI_Call(this);
    private BroadcastReceiver mRegistrationBroadcastReceiver;
    // UI references.
    private EditText mEmailView;
    private String email = "", password1 = "",student_name, address;
    public static String token="",GCMId="";
    public static int GCM_flag=0;
    private EditText mPasswordView;
    private int flag = 0;
    private Button button;
    DBHelper mydb;
    private int count=0;
    public static ProgressDialog pd;
    private Toolbar toolbar;
    private TextInputLayout inputLayoutName, inputLayoutEmail, inputLayoutPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout);
        // Set up the login form.
        mRegistrationBroadcastReceiver = new BroadcastReceiver() {

            //When the broadcast received
            //We are sending the broadcast from GCMRegistrationIntentService

            @Override
            public void onReceive(Context context, Intent intent) {
                //If the broadcast has received with success
                //that means device is registered successfully
                if(intent.getAction().equals(GCMRegistrationIntentService.REGISTRATION_SUCCESS)){
                    //Getting the registration token from the intent
                    GCMId = intent.getStringExtra("token");
                    GCM_flag=1;
                    //Displaying the token as toast
                  //  Toast.makeText(getApplicationContext(), "Registration token:" + GCMId, Toast.LENGTH_LONG).show();

                    //if the intent is not with success then displaying error messages
                } else if(intent.getAction().equals(GCMRegistrationIntentService.REGISTRATION_ERROR)){
                    GCM_flag=0;
                  //  Toast.makeText(getApplicationContext(), "GCM registration error!", Toast.LENGTH_LONG).show();
                } else {
                    GCM_flag=0;
                  //  Toast.makeText(getApplicationContext(), "Error occurred (GCM)", Toast.LENGTH_LONG).show();
                }
            }
        };

        //Checking play service is available or not
        int resultCode = GooglePlayServicesUtil.isGooglePlayServicesAvailable(getApplicationContext());

        //if play service is not available
        if(ConnectionResult.SUCCESS != resultCode) {
            //If play service is supported but not installed
            if(GooglePlayServicesUtil.isUserRecoverableError(resultCode)) {
                //Displaying message that play service is not installed
                Toast.makeText(getApplicationContext(), "Google Play Service is not install/enabled in this device!", Toast.LENGTH_LONG).show();
                GooglePlayServicesUtil.showErrorNotification(resultCode, getApplicationContext());

                //If play service is not supported
                //Displaying an error message
            } else {
                Toast.makeText(getApplicationContext(), "This device does not support for Google Play Service!", Toast.LENGTH_LONG).show();
            }

            //If play service is available
        } else {
            //Starting intent to register device
            Intent itent = new Intent(this, GCMRegistrationIntentService.class);
            startService(itent);
        }

        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
//        button = (Button) findViewById(R.id.button2);
        mEmailView = (EditText) findViewById(R.id.email);
        mPasswordView = (EditText) findViewById(R.id.password);
        inputLayoutEmail = (TextInputLayout) findViewById(R.id.input_layout_Email);
        inputLayoutPassword = (TextInputLayout) findViewById(R.id.input_layout_password);
        Button mEmailSignInButton = (Button) findViewById(R.id.email_sign_in_button);
        mEmailView.addTextChangedListener(new MyTextWatcher(mEmailView));
        mPasswordView.addTextChangedListener(new MyTextWatcher(mPasswordView));
         Splash_Screen.fa.finish();



        /*
        Log in button at the end of username and password
         */
        mEmailSignInButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                attemptLogin();
            }
        });
        /*
        forgot password button on click
         */
//        button.setOnClickListener(new OnClickListener() {
//            @Override
//            public void onClick(View view) {
//                forgotpassword();
//            }
//        });

    }
    //Registering receiver on activity resume
    @Override
    protected void onResume() {
        super.onResume();
        Log.w("MainActivity", "onResume");
        LocalBroadcastManager.getInstance(this).registerReceiver(mRegistrationBroadcastReceiver,
                new IntentFilter(GCMRegistrationIntentService.REGISTRATION_SUCCESS));
        LocalBroadcastManager.getInstance(this).registerReceiver(mRegistrationBroadcastReceiver,
                new IntentFilter(GCMRegistrationIntentService.REGISTRATION_ERROR));
    }


    //Unregistering receiver on activity paused
    @Override
    protected void onPause() {
        super.onPause();
        Log.w("MainActivity", "onPause");
        LocalBroadcastManager.getInstance(this).unregisterReceiver(mRegistrationBroadcastReceiver);
    }

    /*
    check for validation of entered data, send data to remote helper
     */
    private void attemptLogin() {


        if (!validateEmail()) {
            inputLayoutEmail.setErrorEnabled(true);
            inputLayoutEmail.setError(getString(R.string.err_msg_email_Not_valid));
            return;
        }

        if (!validatePassword()) {
            return;
        }
        String email = mEmailView.getText().toString();
        password1 = mPasswordView.getText().toString();

        if (isNetworkAvailable() == true) {

            if(GCM_flag==1){
            pd = new ProgressDialog(this);
            pd.setMessage("loading");
            new RemoteHelper(getApplicationContext()).verifyLogin(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, email, password1,GCMId);
            pd.show();}
            else {Toast.makeText(getApplicationContext(), "GCM Error", Toast.LENGTH_LONG).show();}
        } else {
            Toast.makeText(getApplicationContext(), "Not Connected to the internet", Toast.LENGTH_LONG).show();
        }
    }

    private boolean validateEmail() {
        String email = mEmailView.getText().toString().trim();
         inputLayoutEmail.setErrorEnabled(false);
        if (email.isEmpty()) {
           // inputLayoutEmail.setError(getString(R.string.err_msg_email));
            mEmailView.setTextColor(Color.RED);
            requestFocus(mEmailView);
            return false;
        }
        else if(!isValidEmail(email)){
           // inputLayoutEmail.setError(getString(R.string.err_msg_email_Not_valid));
            mEmailView.setTextColor(Color.RED);
            requestFocus(mEmailView);
            return false;
        }
        else {
            mEmailView.setTextColor(Color.GREEN);
          //  inputLayoutEmail.setErrorEnabled(false);
        }

        return true;
    }

    private boolean validatePassword() {
        String password=mPasswordView.getText().toString().trim();
        inputLayoutPassword.setErrorEnabled(false);
        if (password.isEmpty()) {
         //   inputLayoutPassword.setError(getString(R.string.err_msg_password));
            mPasswordView.setTextColor(Color.RED);
            requestFocus(mPasswordView);
            return false;
        } else if (password.length()<8){
            //inputLayoutPassword.setError(getString(R.string.err_msg_password_Lenth_not_8));
            mPasswordView.setTextColor(Color.RED);
            requestFocus(mPasswordView);
            return false;
        }else {
           // inputLayoutPassword.setErrorEnabled(false);
            mPasswordView.setTextColor(Color.GREEN);
        }

        return true;
    }


    private static boolean isValidEmail(String email) {
        return !TextUtils.isEmpty(email) && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }

    private void requestFocus(View view) {
        if (view.requestFocus()) {
            getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_VISIBLE);
        }
    }

    /*
    for animations
     */
    private class MyTextWatcher implements TextWatcher {

        private View view;

        private MyTextWatcher(View view) {
            this.view = view;
        }


        public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {
        }

        public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
        }

        public void afterTextChanged(Editable editable) {
            switch (view.getId()) {
                case R.id.email:
                    validateEmail();
                    break;
                case R.id.password:
                    validatePassword();
                    break;
            }
        }
    }

    /*
    parse the JSon object response from server
     */
    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
           int status=0;String name, primaryContact,secondaryContact,dob;
           String fatherName,motherName,grade,teacherName,teacherContact;
        pd.dismiss();
        if (isSuccessful) {

            try {
                status=response.getJSONObject(1).getInt("original");
                email = response.getJSONObject(0).getString("username");
                token =response.getJSONObject(0).getString("token");
                 name=response.getJSONObject(0).getString("fatherName");
                 primaryContact=response.getJSONObject(0).getString("primartContact");
                student_name=response.getJSONObject(0).getString("studentName");
                address=response.getJSONObject(0).getString("address");
                 dob=response.getJSONObject(0).getString("dob");
                fatherName=response.getJSONObject(0).getString("fatherName");
                motherName=response.getJSONObject(0).getString("motherName");
                secondaryContact=response.getJSONObject(0).getString("secondaryContact");
                grade=response.getJSONObject(0).getString("grade");
                teacherName=response.getJSONObject(0).getString("teacherName");
                teacherContact=response.getJSONObject(0).getString("teacherContact");
               // Tab_fragment.pd.show();

                if(status==200){
                    update(email, password1,token,student_name,grade,dob,fatherName,motherName,primaryContact,secondaryContact ,
                            address,teacherName,teacherContact);
                    vid.api_Call();
                    int position= email.indexOf("@");
                    email="welcome "+email.substring(0,position);
                Intent intent = new Intent(this, MainActivity.class);
                startActivity(intent);
                finish();}
                else if(status==204){

                }
                else {
                    Toast.makeText(getApplicationContext(), "Username/password incorrect", Toast.LENGTH_LONG).show();}

            } catch (JSONException e) {
                e.printStackTrace();
                email = "error";
            }
            Toast.makeText(getApplicationContext(), email, Toast.LENGTH_LONG).show();
        } else {

            Toast.makeText(getApplicationContext(), "can't connect to server", Toast.LENGTH_LONG).show();
        }

    }

    /*
    check for internet network before proceding form login
     */
    public boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    public void forgotpassword() {
      /*  GCMRegistrationIntentService class2=new GCMRegistrationIntentService();
        String token=class2.registerGCM();
        Toast.makeText(getApplicationContext(), token, Toast.LENGTH_LONG).show();*/
       /* Intent sendIntent = new Intent();
        sendIntent.setAction(Intent.ACTION_SEND);
        sendIntent.putExtra(Intent.EXTRA_TEXT, "This is my text to send.");
        sendIntent.setType("text/plain");
        sendIntent.setPackage("com.whatsapp");
        startActivity(sendIntent);*/

        //forget password action
        Intent intent5=new Intent(this,MyAppointments_CardView.class);
        startActivity(intent5);
    }


    private void update(String email, String password,String token,String student_name,String grade,String dob,String fatherName,
                        String motherName,String primaryContact,String secondaryContact,String address,String teacherName,
            String teacherContact) {
        mydb = new DBHelper(this);
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
//        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_FATHER,fatherName);
//        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_MOTHER,motherName);
//        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_SECONDARYCONTACT,secondaryContact);
//        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_TEACHER,teacherName);
//        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_GRADE,grade);
//        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_TEACHERCONTACT,teacherContact);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER,primaryContact);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL, email);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH,dob);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PASSWORD, password);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_TOKEN,token);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_ADDRESS,address);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_STUDENT_NAME,student_name);
        String[] mSelectionArgs = {"1"};
        int mRowsUpdated = getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
      //  String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";
       // Toast.makeText(getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
        mydb.close();
    }
    public void onBackPressed() {
        count++;
        if(count==1)
        { Toast.makeText(getApplicationContext(), "Press Again to exit", Toast.LENGTH_LONG).show();}

        if(count>=2){
            finish();}
    }

}



