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
import android.widget.TextView;
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
  private Button myapp;
    private int year, month, day;
    private String token="temp";
    private ImageView imageView;
    private Button circularButton1 ;
    private TextInputLayout studentName_layout,studentGrade_layout,studentDOB_layout, fatherName_layout,motherName_layout,
                            primaryContact_layout, secondaryContact_layout, address_layout, teacherName_layout,teacherContact_layout;
    private EditText studentName,studentGrade,studentDOB,fatherName, motherName,primaryContact,secondaryContact, address, teacherName,
                     teacherContact;
    private String UPLOAD_URL = "http://14.192.16.145/celearn/laravel/public/test";


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
        View view =inflater.inflate(R.layout.edit_profile, container, false);
        Toolbar toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        toolbar.setTitle("Profile");
        toolbar.setTitleTextColor(getResources().getColor(R.color.black));
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);
        done=(Button) view.findViewById(R.id.done);
        myapp=(Button) view.findViewById(R.id.cancelled_events);
        // mydb = new DBHelper(this);
    /*    done = (Button) findViewById(R.id.done);*/
        studentName = (EditText) view.findViewById(R.id.studentName1);
        studentDOB = (EditText) view.findViewById(R.id.DOB);
        studentGrade = (EditText) view.findViewById(R.id.grade1);
        fatherName = (EditText) view.findViewById(R.id.fatherName);
        motherName = (EditText) view.findViewById(R.id.motherName);
        primaryContact = (EditText) view.findViewById(R.id.primaryNumber);
        secondaryContact = (EditText) view.findViewById(R.id.secondaryNumber);
        address = (EditText) view.findViewById(R.id.address1);
        teacherName =(EditText) view.findViewById(R.id.teacherName1);
        teacherContact = (EditText) view.findViewById(R.id.teacherContact1);
         primaryContact_layout=(TextInputLayout) view.findViewById(R.id.primaryNumber_layout);
        secondaryContact_layout=(TextInputLayout) view.findViewById(R.id.secondaryNumber_layout);
        address_layout=(TextInputLayout) view.findViewById(R.id.address_layout1);
        circularButton1 = (Button) view.findViewById(R.id.done);
        fatherName.setFocusable(false);
        fatherName.setClickable(false);
        motherName.setFocusable(false);
        motherName.setClickable(false);
        studentName.setFocusable(false);
        studentName.setClickable(false);
        studentDOB.setFocusable(false);
        studentDOB.setClickable(false);
        studentGrade.setFocusable(false);
        studentGrade.setClickable(false);
        teacherContact.setFocusable(false);
        teacherContact.setClickable(false);
        teacherName.setFocusable(false);
        teacherName.setClickable(false);

        imageView = (ImageView) view.findViewById(R.id.imageView);
        //loadImageFromStorage();
        fetch = (Button) view.findViewById(R.id.done11);
        /*inputL=(TextInputLayout)view.findViewById(R.id.input_layout_contact);
        primaryContac=(TextInputLayout) view.findViewById(R.id.input_layout_name);
        inputLayoutaddress=(TextInputLayout) view.findViewById(R.id.input_layout_address);
        inputLayoutStudent_name=(TextInputLayout) view.findViewById(R.id.input_layout_student_name);
        inputLayoutdob=(TextInputLayout) view.findViewById(R.id.input_layout_birthday);*/

        primaryContact.addTextChangedListener(new MyTextWatcher(primaryContact_layout));
        secondaryContact.addTextChangedListener(new MyTextWatcher(secondaryContact_layout));
        address.addTextChangedListener(new MyTextWatcher(address_layout));
        //secondaryContact.addTextChangedListener(new MyTextWatcher(secondaryContact_layout));
        /*.setEnabled(false);
        student_name.setEnabled(false);
        dateView.setEnabled(false);*/

//        circularButton1.setIndeterminateProgressMode(true);
//        circularButton1.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                //fetchman();
//
//                if (circularButton1.getProgress() == 0) {
//                    circularButton1.setProgress(50);
//                } else if (circularButton1.getProgress() == 100) {
//                    circularButton1.setProgress(0);
//                } else {
//                    circularButton1.setProgress(100);
//                }

//            }
//        });




        fetch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //fetchman();
                logout();
            }
        });
        myapp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent l=new Intent(getContext(),MyAppointments_CardView.class);
                startActivity(l);

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
        primaryContact.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (!validateContact1()) {
                    return;
                }
            }
        });
        secondaryContact.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (!validateContact2()) {
                    return;
                }
            }
        });
        address.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(address.getText().toString().isEmpty()==true){
                    address_layout.setErrorEnabled(false);
                    return;
                }
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
        studentName.requestFocus();

       /*  myCalendar = Calendar.getInstance();

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

        };*/

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
   /* private void updateLabel() {

        String myFormat = "MM/dd/yy"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);

        dateView.setText(sdf.format(myCalendar.getTime()));
    }*/
    private void logout() {
       /* mydb = new DBHelper(Edit_profile.this);*/
        mydb=new DBHelper(getContext());
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_FATHER);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_MOTHER);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_SECONDARYCONTACT);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_TEACHERCONTACT);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_TEACHER);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_GRADE);
        mUpdateValues.putNull(UserContract.UserDetailEntry.CoLUMN_PHOTO_PATH);
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
                        UserContract.UserDetailEntry.CoLUMN_FATHER,  // Contract class constant for the word column name
                        UserContract.UserDetailEntry.CoLUMN_EMAIL, // Contract class constant for the locale column name
                        UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER,
                        UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH,
                        UserContract.UserDetailEntry.CoLUMN_TOKEN,
                        UserContract.UserDetailEntry.CoLUMN_ADDRESS,
                        UserContract.UserDetailEntry.CoLUMN_STUDENT_NAME,
                        UserContract.UserDetailEntry.CoLUMN_MOTHER,
                        UserContract.UserDetailEntry.CoLUMN_SECONDARYCONTACT,
                        UserContract.UserDetailEntry.CoLUMN_GRADE,
                        UserContract.UserDetailEntry.CoLUMN_TEACHER,
                        UserContract.UserDetailEntry.CoLUMN_PHOTO_PATH,
                        UserContract.UserDetailEntry.CoLUMN_TEACHERCONTACT

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
            int mCursorColumnIndex1 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_FATHER);
            int mCursorColumnIndex2 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
            int mCursorColumnIndex3 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);
            int mCursorColumnIndex4 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TOKEN);
            int mCursorColumnIndex5=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_STUDENT_NAME);
            int mCursorColumnIndex6=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_ADDRESS);
            int mCursorColumnIndex7=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_MOTHER);
            int mCursorColumnIndex8=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_SECONDARYCONTACT);
            int mCursorColumnIndex9=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_GRADE);
            int mCursorColumnIndex10=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TEACHER);
            int mCursorColumnIndex11=mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_TEACHERCONTACT);
            int photopath = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_PHOTO_PATH);
            while (mCursor.moveToNext()) {

                // Insert code here to process the retrieved word.
                if (mCursor.getInt(mCursorColumnIndex_main) == 1) {
                    token=mCursor.getString(mCursorColumnIndex4);
                   studentName.setText(mCursor.getString(mCursorColumnIndex1));
                    String l=mCursor.getString(mCursorColumnIndex);
//                    email.setText(mCursor.getString(mCursorColumnIndex));
                    fatherName.setText(mCursor.getString(mCursorColumnIndex1));
                    primaryContact.setText(mCursor.getString(mCursorColumnIndex3));
                    secondaryContact.setText(mCursor.getString(mCursorColumnIndex8));
                    motherName.setText(mCursor.getString(mCursorColumnIndex7));
                    studentGrade.setText(mCursor.getString(mCursorColumnIndex9));
                    teacherName.setText(mCursor.getString(mCursorColumnIndex10));
                    teacherContact.setText(mCursor.getString(mCursorColumnIndex11));
                    studentDOB.setText(mCursor.getString(mCursorColumnIndex2));
                    studentName.setText(mCursor.getString(mCursorColumnIndex5));
                    address.setText(mCursor.getString(mCursorColumnIndex6));
                    loadImageFromStorage(mCursor.getString(photopath));
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
        path = saveToInternalStorage(bitmap1);
        String contact_check1="",contact_check2="",address_check1="";
         contact_check1 = primaryContact.getText().toString().trim();
         contact_check2 = secondaryContact.getText().toString().trim();
         address_check1 = address.getText().toString().trim();

       if(contact_check1.isEmpty()==true || contact_check1.length()!=10){
                primaryContact_layout.setErrorEnabled(true);
                primaryContact_layout.setError(getString(R.string.err_msg_contact_notValid));
                //Toast.makeText(getContext().getApplicationContext(), "Check for Errors", Toast.LENGTH_LONG).show();
                contact_flag=1;
        }
         if(contact_check2.isEmpty()==true || contact_check2.length()!=10){
           secondaryContact_layout.setErrorEnabled(true);
           secondaryContact_layout.setError(getString(R.string.err_msg_contact_notValid));
           //Toast.makeText(getContext().getApplicationContext(), "Check for Errors", Toast.LENGTH_LONG).show();
           contact_flag=1;
       }
       if(address_check1.isEmpty()==true){
            address_layout.setErrorEnabled(true);
           address_layout.setError(getString(R.string.address_is_empty));
            //Toast.makeText(getContext().getApplicationContext(), "Check for Errors", Toast.LENGTH_LONG).show();
            contact_flag=1;
        }

        mydb = new DBHelper(getContext());
        if (bitmap1 == null) {
            studentName.getText().toString();
            bitmap1 = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
        }
        image = getStringImage(bitmap1);
        /*pd = new ProgressDialog(getContext());
        pd.setMessage(getContext().getApplicationContext().getResources().getString(R.string.loadingmsg));*/
        if(contact_flag==0){
        update(primaryContact.getText().toString(),secondaryContact.getText().toString().trim(),
                address.getText().toString());
        new RemoteHelper(getContext().getApplicationContext()).Edit_profile(this, RemoteCalls.EDIT_PROFILE,
                token,
                studentName.getText().toString().trim(),
                primaryContact.getText().toString().trim(),
                address.getText().toString().trim(),
                secondaryContact.getText().toString().trim(),
                studentDOB.getText().toString().trim(),
                studentName.getText().toString().trim(),
                path
                );}
    }
    private int update(String phone_num,String scontact,String address) {
        mydb = new DBHelper(getContext());
        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        if(scontact!=null && address!=null && phone_num!=null){
            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER, phone_num);

            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_SECONDARYCONTACT, scontact);

            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_ADDRESS,address);
            mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PHOTO_PATH, path);

            String[] mSelectionArgs = {"1"};
            int mRowsUpdated = getContext().getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
            String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";

            /*if(updatedrows!=null || updatedrows!=""){
                return 0;
            }else {return 1;}*/
            // Toast.makeText(getContext().getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
//            Toast.makeText(getContext().getApplicationContext(), "profile updated", Toast.LENGTH_LONG).show();
        }
        mydb.close();
        return 0;
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
        if(path != null) {

            try {
                File f = new File(path, "profile.jpg");
                Bitmap b = BitmapFactory.decodeStream(new FileInputStream(f));
                imageView.setImageBitmap(b);

            } catch (FileNotFoundException e) {
                e.printStackTrace();
            }
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
            //circularButton1.setProgress(100);
            //pd.dismiss();
            contact_flag=0;
            Toast.makeText(getContext().getApplicationContext(), "Profile Updated", Toast.LENGTH_LONG).show();

            /*byte[] decodedString = Base64.decode(username, Base64.DEFAULT);
            Bitmap decodedByte = BitmapFactory.decodeByteArray(decodedString, 0, decodedString.length);
            imageView.setImageBitmap(decodedByte);*/


        } else {
            Toast.makeText(getContext().getApplicationContext(), String.valueOf(response), Toast.LENGTH_LONG).show();
        }

    }
    private boolean validateContact1() {
        String contact_check=primaryContact.getText().toString().trim();
          primaryContact_layout.setErrorEnabled(false);
         if (contact_check.length()==10){
            // inputLayoutContact.setError(getString(R.string.err_msg_contact_notValid));
            primaryContact.setTextColor(Color.GREEN);
            requestFocus(primaryContact);
            return false;}
         else if (contact_check.length()==0){
             primaryContact_layout.setErrorEnabled(true);
             primaryContact_layout.setError("Contact field can't be blank");
             requestFocus(primaryContact);
             return false;
         }
        else {primaryContact.setTextColor(Color.RED);
            requestFocus(primaryContact);
            return false;
        }
    }
    private boolean validateContact2() {
        String scontact_check=secondaryContact.getText().toString().trim();
        secondaryContact_layout.setErrorEnabled(false);
        if (scontact_check.length()==10){
            // inputLayoutContact.setError(getString(R.string.err_msg_contact_notValid));
            secondaryContact.setTextColor(Color.GREEN);
            requestFocus(secondaryContact);
            return false;}
        else if (scontact_check.length()==0){
            secondaryContact_layout.setErrorEnabled(true);
            secondaryContact_layout.setError("Contact field can't be blank");
            requestFocus(secondaryContact);
            return false;
        }
        else {secondaryContact.setTextColor(Color.RED);
            requestFocus(secondaryContact);
            return false;
        }
    }
    private boolean validateAddress() {
        String saddress=address.getText().toString().trim();
        address_layout.setErrorEnabled(false);
        if (saddress.length()==0){
            // inputLayoutContact.setError(getString(R.string.err_msg_contact_notValid));
            address_layout.setErrorEnabled(true);
            address_layout.setError("Address field can't be blank");
            requestFocus(address);
            return false;}
        else {
            requestFocus(address);
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
                case R.id.primaryNumber_layout:
                  validateContact1();
                    break;
                case R.id.secondaryNumber_layout:
                    validateContact2();
                    break;
                case R.id.address_layout1:
                    validateAddress();
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
