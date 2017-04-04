package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/2/2017.
 */

import android.app.ProgressDialog;
import android.content.ContentValues;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.support.design.widget.TextInputLayout;
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

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


/*
 * A login screen that offers login via email/password.
 */
public class LoginActivity extends AppCompatActivity implements RemoteCallHandler {

    // UI references.
    private EditText mEmailView;
    private String email = "", password = "";
    private EditText mPasswordView;
    private int flag = 0;
    private Button button;
    DBHelper mydb;
    private int count=0;
    ProgressDialog pd;
    private Toolbar toolbar;
    private TextInputLayout inputLayoutName, inputLayoutEmail, inputLayoutPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout);
        // Set up the login form.
        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        button = (Button) findViewById(R.id.button2);
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
        button.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                forgotpassword();
            }
        });

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
        String password = mPasswordView.getText().toString();

        if (isNetworkAvailable() == true) {

            Toast.makeText(getApplicationContext(), "Thank You!", Toast.LENGTH_SHORT).show();
            pd = new ProgressDialog(this);
            pd.setMessage("loading");
            new RemoteHelper(getApplicationContext()).verifyLogin(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, email, password);
            pd.show();
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
           int status=0;
        pd.dismiss();
        if (isSuccessful) {

            try {
                status=response.getJSONObject(1).getInt("original");
                email = response.getJSONObject(0).getString("username");
               password=response.getJSONObject(0).getString("parent_name");
                update(email, password);
                if(status==200){
                Intent intent = new Intent(this, MainActivity.class);
                startActivity(intent);
                finish();}
                else {Toast.makeText(getApplicationContext(), email, Toast.LENGTH_LONG).show();}

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

        //forget password action
        Intent intent5=new Intent(this,Tab_fragment.class);
        startActivity(intent5);
    }


    private void update(String email, String password) {
        mydb = new DBHelper(this);
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_NAME);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL, email);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PASSWORD, password);
        String[] mSelectionArgs = {"1"};
        int mRowsUpdated = getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
        String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";
        Toast.makeText(getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
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



