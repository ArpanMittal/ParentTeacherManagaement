package com.eurovisionedusolutions.android.rackup;

import android.app.Activity;
import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ContentValues;
import android.content.Context;
import android.content.ContextWrapper;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.ParcelFileDescriptor;
import android.provider.MediaStore;
import android.support.design.widget.TextInputLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.util.Base64;
import android.util.Patterns;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.DatePicker;
import android.text.Editable;
import android.text.TextUtils;
import android.text.TextWatcher;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.android.gms.common.api.GoogleApiClient;
import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileDescriptor;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

import static android.app.Activity.RESULT_OK;


public class Edit_profile extends Fragment implements RemoteCallHandler {
    public static Edit_profile newInstance() {
        Edit_profile fragment = new Edit_profile();
        return fragment;
    }
    private static int SELECT_PICTURE = 1;
    private static int constant = 999;
    public Bitmap bitmap1;
    private volatile String path="";
    public String image,image1;
    public int flag = 0;
    private volatile int  contact_flag=0;
    private volatile long timer=0;
    public static Activity fa4;
    int id_To_Update = 0;
    ProgressDialog pd;
    DBHelper mydb;
    private Calendar calendar, myCalendar;
    private Button done, fetch;
    private String selectedImagePath;

    private int year, month, day;
    private String token="temp";
    private ImageView imageView;
    private TextInputLayout inputLayoutContact,inputLayoutname, inputLayoutaddress,inputLayoutdob,inputLayoutStudent_name;
    private EditText email, name, contact, address, student_name, dateView, student_class, student_address;
    private String UPLOAD_URL = "http://14.192.16.145/celearn/laravel/public/test";
    private DatePickerDialog.OnDateSetListener myDateListener = new
            DatePickerDialog.OnDateSetListener() {
                @Override
                public void onDateSet(DatePicker arg0,
                                      int arg1, int arg2, int arg3) {
                    // TODO Auto-generated method stub
                    // arg1 = year
                    // arg2 = month
                    // arg3 = day
                    showDate(arg1, arg2 + 1, arg3);
                }
            };

    /**
     * ATTENTION: This was auto-generated to implement the App Indexing API.
     * See https://g.co/AppIndexing/AndroidStudio for more information.
     */
    private GoogleApiClient client;
    /*public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view =inflater.inflate(R.layout.fragment_item_three, container, false);
        done=(Button) view.view.findViewById(R.id.done);
        return view;
    }*/
    @Override
      public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view =inflater.inflate(R.layout.fragment_item_three, container, false);
        Toolbar toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);
        done=(Button) view.findViewById(R.id.done);

        // mydb = new DBHelper(this);
    /*    done = (Button) findViewById(R.id.done);*/
        email = (EditText) view.findViewById(R.id.email);
        name = (EditText) view.findViewById(R.id.name);
        contact = (EditText) view.findViewById(R.id.contact);
        address = (EditText) view.findViewById(R.id.address);
        student_name = (EditText) view.findViewById(R.id.student_name);
        dateView = (EditText) view.findViewById(R.id.dob);
        student_class = (EditText) view.findViewById(R.id.student_class);
        student_address = (EditText) view.findViewById(R.id.student_address);
        dateView = (EditText) view.findViewById(R.id.dob);
        calendar = Calendar.getInstance();
        year = calendar.get(Calendar.YEAR);
        month = calendar.get(Calendar.MONTH);
        day = calendar.get(Calendar.DAY_OF_MONTH);
        showDate(year, month + 1, day);
        imageView = (ImageView) view.findViewById(R.id.imageView);
        fetch = (Button) view.findViewById(R.id.done11);
        inputLayoutContact=(TextInputLayout)view.findViewById(R.id.input_layout_contact);
        inputLayoutname=(TextInputLayout) view.findViewById(R.id.input_layout_name);
        inputLayoutaddress=(TextInputLayout) view.findViewById(R.id.input_layout_address);
        inputLayoutStudent_name=(TextInputLayout) view.findViewById(R.id.input_layout_student_name);
        inputLayoutdob=(TextInputLayout) view.findViewById(R.id.input_layout_birthday);

        contact.addTextChangedListener(new MyTextWatcher(contact));
        name.setEnabled(false);
        student_name.setEnabled(false);
        dateView.setEnabled(false);






        fetch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //fetchman();
                logout();
            }
        });
           /*
        upload to server button
         */
        done.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                uploadImage();
            }
        });
        contact.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (!validateContact()) {
                    return;
                }
            }
        });
        address.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(address.getText().toString().isEmpty()==true){
                    inputLayoutaddress.setErrorEnabled(false);
                    return;
                }
            }
        });
        student_name.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                inputLayoutStudent_name.setErrorEnabled(false);
                return;
            }
        });
        /*
         For changing the profile pic
        */
        mydb = new DBHelper(getContext());
        imageView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent();
                intent.setType("image/*");
                intent.setAction(Intent.ACTION_GET_CONTENT);
                startActivityForResult(Intent.createChooser(intent,
                        "Select Picture"), SELECT_PICTURE);
            }
        });
        //To retrive information from the local database
        fetchman();
        name.requestFocus();

         myCalendar = Calendar.getInstance();

        final DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {

            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear,
                                  int dayOfMonth) {
                // TODO Auto-generated method stub
                myCalendar.set(Calendar.YEAR, year);
                myCalendar.set(Calendar.MONTH, monthOfYear);
                myCalendar.set(Calendar.DAY_OF_MONTH, dayOfMonth);
                updateLabel();
            }

        };

        /*dateView.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                DatePickerDialog dialog=new DatePickerDialog(getContext(),date,myCalendar
                        .get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),myCalendar.get(Calendar.DAY_OF_MONTH));
                Calendar l= Calendar.getInstance();
                dialog.getDatePicker().setMaxDate(l.getTimeInMillis());
                dialog.show();
                *//*new DatePickerDialog(getContext(), date, myCalendar
                        .get(Calendar.YEAR), myCalendar.get(Calendar.MONTH),
                        myCalendar.get(Calendar.DAY_OF_MONTH))
                        .show();*//*
            }
        });*/
        //imageView.setImageBitmap(StringToBitMap(image1));
       // Toast.makeText(getContext().getApplicationContext(), image1, Toast.LENGTH_LONG).show();
      /*  Picasso.with(this)
                .load("http://res.cloudinary.com/demo/image/upload/q_90/happy_dog.jpg")
                .into(new Target() {
                    @Override
                    public void onBitmapLoaded(Bitmap bitmap, Picasso.LoadedFrom from) {
                        imageView.setImageBitmap(bitmap);
                        saveToInternalStorage(bitmap);

                    }

                    @Override
                    public void onBitmapFailed(Drawable errorDrawable) {
                        loadImageFromStorage("/data/user/0/com.eurovisionedusolutions.android.rackup/app_imageDir");

                    }

                    @Override
                    public void onPrepareLoad(Drawable placeHolderDrawable) {
                        //imageView.setImageResource(R.drawable.loading_thumbnail);


                    }
                });*/
       /* if(isNetworkAvailable()==false){
        loadImageFromStorage("/data/user/0/com.eurovisionedusolutions.android.rackup/app_imageDir");}
        else  {imageView.setImageBitmap(getBitmapFromURL("http://res.cloudinary.com/demo/image/upload/q_90/happy_dog.jpg"));}*/
        return view;
    }
    private void updateLabel() {

        String myFormat = "MM/dd/yy"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);

        dateView.setText(sdf.format(myCalendar.getTime()));
    }
    private void logout() {
       /* mydb = new DBHelper(Edit_profile.this);*/
        mydb=new DBHelper(getContext());
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_NAME);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL,"temp");
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_PASSWORD);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
        String[] mSelectionArgs = {"1"};
        int mRowsUpdated = getContext().getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
        String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";
       // Toast.makeText(getContext().getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
        mydb.close();
        Intent intent1=new Intent(getContext(),LoginActivity.class);
        //MainActivity.fa1.finish();
        getActivity().finish();
        startActivity(intent1);
        //VideoListDemoActivity.fa2.finish();
        intent1.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);

    }

    private void fetchman() {
        mydb = new DBHelper(getContext());
        //To retrive information on opening the edit profile page

        String[] mProjection =
                {
                        UserContract.UserDetailEntry.COLUMN_ID,    // Contract class constant for the _ID column name
                        UserContract.UserDetailEntry.CoLUMN_NAME,  // Contract class constant for the word column name
                        UserContract.UserDetailEntry.CoLUMN_EMAIL, // Contract class constant for the locale column name
                        UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER,
                        UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH,
                        UserContract.UserDetailEntry.CoLUMN_TOKEN,
                        UserContract.UserDetailEntry.CoLUMN_ADDRESS,
                        UserContract.UserDetailEntry.CoLUMN_STUDENT_NAME
                };
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        String[] mSelectionArgs = {"1"};
        Cursor mCursor=null;
        String mSortOrder = null;
        mCursor = getContext().getContentResolver().query(
                UserContract.BASE_CONTENT_URI_Full,  // The content URI of the words table
                mProjection,                       // The columns to return for each row
                mSelectionClause,                   // Either null, or the word the user entered
                mSelectionArgs,                    // Either empty, or the string the user entered
                mSortOrder);

        if (null == mCursor) {
    /*
     * Insert code here to handle the error. Be sure not to use the cursor! You may want to
     * call android.util.Log.e() to log this error.
     */
            Toast.makeText(getContext().getApplicationContext(), "Error", Toast.LENGTH_LONG).show();
// If the Cursor is empty, the provider found no matches
        } else if (mCursor.getCount() < 1) {

    /*
     * Insert code here to notify the user that the search was unsuccessful. This isn't necessarily
     * an error. You may want to offer the user the option to insert a new row, or re-type the
     * search term.
     */
            Toast.makeText(getContext().getApplicationContext(), "Search was unsuccessfull", Toast.LENGTH_LONG).show();

        } else if (mCursor.getCount() > 0) {
            //Search is successful
            // Insert code here to do something with the results
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_EMAIL);
            int mCursorColumnIndex1 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_NAME);
            int mCursorColumnIndex2 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
            int mCursorColumnIndex3 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);
            int mCursorColumnIndex4 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TOKEN);
            int mCursorColumnIndex5=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_STUDENT_NAME);
            int mCursorColumnIndex6=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_ADDRESS);
            while (mCursor.moveToNext()) {

                // Insert code here to process the retrieved word.
                if (mCursor.getInt(mCursorColumnIndex_main) == 1) {
                    token=mCursor.getString(mCursorColumnIndex4);
                   name.setText(mCursor.getString(mCursorColumnIndex1));
                    String l=mCursor.getString(mCursorColumnIndex);
                    email.setText(mCursor.getString(mCursorColumnIndex));
                    contact.setText(mCursor.getString(mCursorColumnIndex3));
                    dateView.setText(mCursor.getString(mCursorColumnIndex2));
                    student_name.setText(mCursor.getString(mCursorColumnIndex5));
                    address.setText(mCursor.getString(mCursorColumnIndex6));
                   // Toast.makeText(getContext().getApplicationContext(),mCursor.getString(mCursorColumnIndex4), Toast.LENGTH_LONG).show();
                }


                // end of while loop
            }

        }
        mCursor.close();
        mydb.close();


    }


    public String getStringImage(Bitmap bmp) {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        bmp.compress(Bitmap.CompressFormat.JPEG, 100, baos);
        byte[] imageBytes = baos.toByteArray();
        String encodedImage = Base64.encodeToString(imageBytes, Base64.DEFAULT);
        return encodedImage;
    }

    private void uploadImage() {
        saveToInternalStorage(bitmap1);
        String contact_check2 = contact.getText().toString().trim();

        if(contact_check2!=null || contact_check2.isEmpty()==false){
            if(contact_check2.length()!=10){
                inputLayoutContact.setErrorEnabled(true);
                inputLayoutContact.setError(getString(R.string.err_msg_contact_notValid));
                //Toast.makeText(getContext().getApplicationContext(), "Check for Errors", Toast.LENGTH_LONG).show();
                contact_flag=1;
            }
        }
        if(name.getText().toString().trim().isEmpty()==true ){
            inputLayoutname.setErrorEnabled(true);
            inputLayoutname.setError("Enter Your Name");
            contact_flag=1;
        }
        if(address.getText().toString().trim().isEmpty()==true ){
            inputLayoutaddress.setErrorEnabled(true);
            inputLayoutaddress.setError("Enter Your Address");
            contact_flag=1;
        }
        if(student_name.getText().toString().trim().isEmpty()==true ){
            inputLayoutStudent_name.setErrorEnabled(true);
            inputLayoutStudent_name.setError("Enter Student's Name");
            contact_flag=1;
        }
        if(dateView.getText().toString().trim().isEmpty()==true){
            inputLayoutdob.setErrorEnabled(true);
            inputLayoutdob.setError("Enter Your Name");
            contact_flag=1;
        }

        mydb = new DBHelper(getContext());
        if (bitmap1 == null) {
            name.getText().toString();
            bitmap1 = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
        }
        image = getStringImage(bitmap1);
        /*pd = new ProgressDialog(getContext());
        pd.setMessage(getContext().getApplicationContext().getResources().getString(R.string.loadingmsg));*/
        if(contact_flag==0){
        update(contact.getText().toString(),dateView.getText().toString().trim(),
                address.getText().toString());
        new RemoteHelper(getContext().getApplicationContext()).Edit_profile(this, RemoteCalls.EDIT_PROFILE,
                token,
                name.getText().toString().trim(),
                contact.getText().toString().trim(),
                address.getText().toString().trim(),
                dateView.getText().toString().trim(),
                student_name.getText().toString().trim()
                );}

    }
    private int update(String phone_num,String dob,String address) {
        mydb = new DBHelper(getContext());
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        if(contact_flag!=1 && dob!=null && address!=null){
            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER, phone_num);

            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH, dob);

            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_ADDRESS,address);

            String[] mSelectionArgs = {"1"};
            int mRowsUpdated = getContext().getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
            String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";

            /*if(updatedrows!=null || updatedrows!=""){
                return 0;
            }else {return 1;}*/
            // Toast.makeText(getContext().getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
           // Toast.makeText(getContext().getApplicationContext(), "profile updated", Toast.LENGTH_LONG).show();
        }
        mydb.close();
        return 0;
    }

    public void setDate(View view) {
        showDialog(999);
        Toast.makeText(getContext().getApplicationContext(), "Choose a date",
                Toast.LENGTH_SHORT)
                .show();
    }

    //@Override
    protected Dialog showDialog(int id) {
        // TODO Auto-generated method stub
        if (id == 999) {

            DatePickerDialog dialog=new DatePickerDialog(getContext(),
                    myDateListener, year, month, day);
            Calendar c=Calendar.getInstance();
            dialog.getDatePicker().setMaxDate(c.getTimeInMillis());

            return dialog;
        }
        return null;
    }

    private void showDate(int year, int month, int day) {
        dateView.setText(new StringBuilder().append(day).append("/")
                .append(month).append("/").append(year));
    }

    /*
   convert image to bitmap, set bitmap image to imageview as a profile pic

     */
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (resultCode == RESULT_OK) {
            if (requestCode == SELECT_PICTURE) {
                Uri selectedImageUri = data.getData();
                if (Build.VERSION.SDK_INT < 19) {
                    selectedImagePath = getPath(selectedImageUri);
                    Bitmap bitmap = BitmapFactory.decodeFile(selectedImagePath);
                    imageView.setImageBitmap(bitmap);
                    saveToInternalStorage(bitmap);


                } else {
                    ParcelFileDescriptor parcelFileDescriptor;
                    try {
                        parcelFileDescriptor = getContext().getContentResolver().openFileDescriptor(selectedImageUri, "r");
                        FileDescriptor fileDescriptor = parcelFileDescriptor.getFileDescriptor();
                        bitmap1 = BitmapFactory.decodeFileDescriptor(fileDescriptor);
                        parcelFileDescriptor.close();
                        imageView.setImageBitmap(bitmap1);
                       // image=getStringImage(bitmap1);
                      //  imageView.setImageBitmap(bitmap1);
                      //  saveToInternalStorage(bitmap1);
                        //Toast.makeText(getContext().getApplicationContext(), saveToInternalStorage(bitmap1), Toast.LENGTH_LONG).show();


                    } catch (FileNotFoundException e) {
                        e.printStackTrace();
                    } catch (IOException e) {
                        // TODO Auto-generated catch block
                        e.printStackTrace();
                    }
                }
            }
        }
    }

    private String saveToInternalStorage(Bitmap bitmapImage) {

            ContextWrapper cw = new ContextWrapper(getContext().getApplicationContext());
            // path to /data/data/yourapp/app_data/imageDir
            File directory = cw.getDir("imageDir", Context.MODE_PRIVATE);
            // Create imageDir
            File mypath=new File(directory,"profile.jpg");

            FileOutputStream fos = null;
            try {
                fos = new FileOutputStream(mypath);
                // Use the compress method on the BitMap object to write image to the OutputStream
                bitmapImage.compress(Bitmap.CompressFormat.PNG, 100, fos);
            } catch (Exception e) {
                e.printStackTrace();
            } finally {
                try {
                    fos.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        path=directory.getAbsolutePath();
            return directory.getAbsolutePath();
        }

    private void loadImageFromStorage(String path)
    {

        try {
            File f=new File(path, "profile.jpg");
            Bitmap b = BitmapFactory.decodeStream(new FileInputStream(f));
            imageView.setImageBitmap(b);

        }
        catch (FileNotFoundException e)
        {
            e.printStackTrace();
        }

    }
    /*
    get image path from cellphone
     */
    public String getPath(Uri uri) {
        if (uri == null) {
            return null;
        }
        String[] projection = {MediaStore.Images.Media.DATA};
        Cursor cursor = getContext().getContentResolver().query(uri, projection, null, null, null);
        if (cursor != null) {
            int column_index = cursor
                    .getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
            cursor.moveToFirst();
            return cursor.getString(column_index);
        }
        return uri.getPath();
    }

    /*
    handle response from server, parse the JSON object, decode the image sting data.
     */
    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        String username = null;
        if (isSuccessful) {
            //pd.dismiss();
            Toast.makeText(getContext().getApplicationContext(), "Profile Updated", Toast.LENGTH_LONG).show();


            /*byte[] decodedString = Base64.decode(username, Base64.DEFAULT);
            Bitmap decodedByte = BitmapFactory.decodeByteArray(decodedString, 0, decodedString.length);
            imageView.setImageBitmap(decodedByte);*/


        } else {
            Toast.makeText(getContext().getApplicationContext(), "connection to server failed", Toast.LENGTH_LONG).show();
        }

    }
    private boolean validateContact() {
        String contact_check=contact.getText().toString().trim();
          inputLayoutContact.setErrorEnabled(false);
         if (contact_check.length()==10){
           // inputLayoutContact.setError(getString(R.string.err_msg_contact_notValid));
             contact.setTextColor(Color.GREEN);
             requestFocus(contact);
             return false;}
         else {contact.setTextColor(Color.RED);
             requestFocus(contact);
             return false;
         }
    }


    private void requestFocus(View view) {
        if (view.requestFocus()) {
            getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_VISIBLE);
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
                case R.id.contact:
                  validateContact();
                    break;
            }
        }
    }
    public void delay(int timer) {
        final Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                // Do something after "timer" milliseconds

               return;
            }
        }, timer);
    }
    public void onBackPressed() {
        Intent mA=new Intent(getContext(),MainActivity.class);
        startActivity(mA);
        getActivity().finish();
    }
    public Bitmap StringToBitMap(String encodedString){
        try{
            byte [] encodeByte=Base64.decode(encodedString,Base64.DEFAULT);
            Bitmap bitmap=BitmapFactory.decodeByteArray(encodeByte, 0, encodeByte.length);
            return bitmap;
        }catch(Exception e){
            e.getMessage();
            return null;
        }
    }
    public static Bitmap getBitmapFromURL(String src) {
        try {
            URL url = new URL(src);
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
            connection.setDoInput(true);
            connection.connect();
            InputStream input = connection.getInputStream();
            Bitmap myBitmap = BitmapFactory.decodeStream(input);
            return myBitmap;
        } catch (IOException e) {
            // Log exception
            return null;
        }
    }
   /* private boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }*/
}
