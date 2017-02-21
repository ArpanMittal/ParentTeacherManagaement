package com.eurovisionedusolutions.android.rackup;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ContentValues;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.ParcelFileDescriptor;
import android.provider.MediaStore;
import android.support.v7.app.AppCompatActivity;
import android.util.Base64;
import android.view.View;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.android.gms.common.api.GoogleApiClient;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.ByteArrayOutputStream;
import java.io.FileDescriptor;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.Calendar;


public class Edit_profile extends AppCompatActivity implements RemoteCallHandler {

    private static int SELECT_PICTURE = 1;
    private static int constant = 999;
    public Bitmap bitmap1;
    public int flag = 0;
    int id_To_Update = 0;
    ProgressDialog pd;
    DBHelper mydb;
    private Calendar calendar;
    private Button done, fetch;
    private String selectedImagePath;
    private int year, month, day;
    private ImageView imageView;
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

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.edit_profile);
        // mydb = new DBHelper(this);
        done = (Button) findViewById(R.id.done);
        email = (EditText) findViewById(R.id.email);
        name = (EditText) findViewById(R.id.name);
        contact = (EditText) findViewById(R.id.contact);
        address = (EditText) findViewById(R.id.address);
        student_name = (EditText) findViewById(R.id.student_name);
        dateView = (EditText) findViewById(R.id.dob);
        student_class = (EditText) findViewById(R.id.student_class);
        student_address = (EditText) findViewById(R.id.student_address);
        dateView = (EditText) findViewById(R.id.dob);
        calendar = Calendar.getInstance();
        year = calendar.get(Calendar.YEAR);
        month = calendar.get(Calendar.MONTH);
        day = calendar.get(Calendar.DAY_OF_MONTH);
        showDate(year, month + 1, day);
        imageView = (ImageView) findViewById(R.id.imageView);
        fetch = (Button) findViewById(R.id.done11);

        fetch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                fetchman();
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

        /*
         For changing the profile pic
        */
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

    }

    private void fetchman() {
        mydb = new DBHelper(this);
        //To retrive information on opening the edit profile page

        String[] mProjection =
                {
                        UserContract.UserDetailEntry.COLUMN_ID,    // Contract class constant for the _ID column name
                        UserContract.UserDetailEntry.CoLUMN_NAME,  // Contract class constant for the word column name
                        UserContract.UserDetailEntry.CoLUMN_EMAIL, // Contract class constant for the locale column name
                        UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER,
                        UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH
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

        if (null == mCursor) {
    /*
     * Insert code here to handle the error. Be sure not to use the cursor! You may want to
     * call android.util.Log.e() to log this error.
     */
            Toast.makeText(getApplicationContext(), "Error", Toast.LENGTH_LONG).show();
// If the Cursor is empty, the provider found no matches
        } else if (mCursor.getCount() < 1) {

    /*
     * Insert code here to notify the user that the search was unsuccessful. This isn't necessarily
     * an error. You may want to offer the user the option to insert a new row, or re-type the
     * search term.
     */
            Toast.makeText(getApplicationContext(), "Search was unsuccessfull", Toast.LENGTH_LONG).show();

        } else if (mCursor.getCount() > 0) {
            //Search is successful
            // Insert code here to do something with the results
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserContract.UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_EMAIL);
            int mCursorColumnIndex1 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_NAME);
            int mCursorColumnIndex2 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH);
            int mCursorColumnIndex3 = mCursor.getColumnIndex(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER);
            while (mCursor.moveToNext()) {

                // Insert code here to process the retrieved word.
                if (mCursor.getInt(mCursorColumnIndex_main) == 1) {

                    name.setText(mCursor.getString(mCursorColumnIndex1));
                    email.setText(mCursor.getString(mCursorColumnIndex));
                    contact.setText(mCursor.getString(mCursorColumnIndex3));
                    dateView.setText(mCursor.getString(mCursorColumnIndex2));
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
        mydb = new DBHelper(this);
        if (bitmap1 == null) {
            name.getText().toString();
            bitmap1 = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
        }
        String image = getStringImage(bitmap1);
        pd = new ProgressDialog(this);
        pd.setMessage(getApplicationContext().getResources().getString(R.string.loadingmsg));
// upload to server
       /* new RemoteHelper(getApplicationContext()).verifyLogin1(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS,
                image,
                name.getText().toString(),
                contact.getText().toString(),
                address.getText().toString(),
                student_name.getText().toString(),
                dateView.getText().toString(),
                student_class.getText().toString(),
                student_address.getText().toString()
        );*/
// upload to local database


//for sending the data to local server

            /*// Defines a new Uri object that receives the result of the insertion
            Uri mNewUri;
// Defines an object to contain the new values to insert
            ContentValues mNewValues = new ContentValues();
*//*
 * Sets the values of each column and inserts the word. The arguments to the "put"
 * method are "column name" and "value"
 *//*
            mNewValues.put(UserContract.UserDetailEntry.COLUMN_ID, 1);
            mNewValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL, email.getText().toString());
            mNewValues.put(UserContract.UserDetailEntry.CoLUMN_NAME, name.getText().toString());
            mNewValues.put(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH, dateView.getText().toString());
            mNewValues.put(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER, contact.getText().toString());

            mNewUri = getContentResolver().insert(
                    UserContract.BASE_CONTENT_URI_Full,   // the user dictionary content URI
                    mNewValues                          // the values to insert
            );
            Toast.makeText(getApplicationContext(), "Changes done locally", Toast.LENGTH_LONG).show();*/


        ContentValues mUpdateValues = new ContentValues();
        String mSelectionClause = UserContract.UserDetailEntry.COLUMN_ID + "=?";
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_NAME, name.getText().toString());
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_PHONE_NUMBER, contact.getText().toString());
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_EMAIL, email.getText().toString());
        mUpdateValues.put(UserContract.UserDetailEntry.CoLUMN_DATE_OF_BIRTH, dateView.getText().toString());
        String[] mSelectionArgs = {"1"};
        int mRowsUpdated = getContentResolver().update(UserContract.BASE_CONTENT_URI_Full, mUpdateValues, mSelectionClause, mSelectionArgs);
        String updatedrows = String.valueOf(mRowsUpdated) + " row(s) successfully updated";
        Toast.makeText(getApplicationContext(), updatedrows, Toast.LENGTH_LONG).show();
        mydb.close();
    }

    public void setDate(View view) {
        showDialog(constant);
        Toast.makeText(getApplicationContext(), "Choose a date",
                Toast.LENGTH_SHORT)
                .show();
    }

    @Override
    protected Dialog onCreateDialog(int id) {
        // TODO Auto-generated method stub
        if (id == constant) {
            return new DatePickerDialog(this,
                    myDateListener, year, month, day);
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
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (resultCode == RESULT_OK) {
            if (requestCode == SELECT_PICTURE) {
                Uri selectedImageUri = data.getData();
                if (Build.VERSION.SDK_INT < 19) {
                    selectedImagePath = getPath(selectedImageUri);
                    Bitmap bitmap = BitmapFactory.decodeFile(selectedImagePath);
                    imageView.setImageBitmap(bitmap);


                } else {
                    ParcelFileDescriptor parcelFileDescriptor;
                    try {
                        parcelFileDescriptor = getContentResolver().openFileDescriptor(selectedImageUri, "r");
                        FileDescriptor fileDescriptor = parcelFileDescriptor.getFileDescriptor();
                        bitmap1 = BitmapFactory.decodeFileDescriptor(fileDescriptor);
                        parcelFileDescriptor.close();
                        imageView.setImageBitmap(bitmap1);


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

    /*
    get image path from cellphone
     */
    public String getPath(Uri uri) {
        if (uri == null) {
            return null;
        }
        String[] projection = {MediaStore.Images.Media.DATA};
        Cursor cursor = getContentResolver().query(uri, projection, null, null, null);
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
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONObject response, Exception exception) {
        String username = null;
        if (isSuccessful) {
            pd.dismiss();
            Toast.makeText(getApplicationContext(), "done", Toast.LENGTH_LONG).show();


            try {
                username = response.getString("image");
                String name = response.getString("name");
                Toast.makeText(getApplicationContext(), name, Toast.LENGTH_LONG).show();
            } catch (JSONException e) {
                e.printStackTrace();
                Toast.makeText(getApplicationContext(), "error", Toast.LENGTH_LONG).show();
            }
            byte[] decodedString = Base64.decode(username, Base64.DEFAULT);
            Bitmap decodedByte = BitmapFactory.decodeByteArray(decodedString, 0, decodedString.length);
            imageView.setImageBitmap(decodedByte);


        } else {
            Toast.makeText(getApplicationContext(), "connection to server failed", Toast.LENGTH_LONG).show();
        }

    }


}
