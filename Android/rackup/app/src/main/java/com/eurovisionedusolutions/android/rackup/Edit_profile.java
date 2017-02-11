package com.eurovisionedusolutions.android.rackup;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
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
import org.json.JSONException;
import org.json.JSONObject;
import java.io.ByteArrayOutputStream;
import java.io.FileDescriptor;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.Calendar;


public class Edit_profile extends AppCompatActivity implements RemoteCallHandler {

    private static int SELECT_PICTURE = 1;
    public Bitmap bitmap1;
    ProgressDialog pd;
    private Calendar calendar;
    private Button done;
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

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.edit_profile);
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
    }

    public String getStringImage(Bitmap bmp) {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        bmp.compress(Bitmap.CompressFormat.JPEG, 100, baos);
        byte[] imageBytes = baos.toByteArray();
        String encodedImage = Base64.encodeToString(imageBytes, Base64.DEFAULT);
        return encodedImage;
    }

    private void uploadImage() {
        if (bitmap1 == null) {
            name.getText().toString();
            bitmap1 = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
        }
        String image = getStringImage(bitmap1);
        pd = new ProgressDialog(this);
        pd.setMessage("loading");

        new RemoteHelper(getApplicationContext()).verifyLogin1(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS,
                image,
                name.getText().toString(),
                contact.getText().toString(),
                address.getText().toString(),
                student_name.getText().toString(),
                dateView.getText().toString(),
                student_class.getText().toString(),
                student_address.getText().toString()
        );

        pd.show();


    }

    public void setDate(View view) {
        showDialog(999);
        Toast.makeText(getApplicationContext(), "ca",
                Toast.LENGTH_SHORT)
                .show();
    }

    @Override
    protected Dialog onCreateDialog(int id) {
        // TODO Auto-generated method stub
        if (id == 999) {
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
        String username = "nooo";
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
