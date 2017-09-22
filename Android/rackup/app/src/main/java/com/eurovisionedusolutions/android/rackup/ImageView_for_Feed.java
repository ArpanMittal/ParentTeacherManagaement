package com.eurovisionedusolutions.android.rackup;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.squareup.picasso.Picasso;

public class ImageView_for_Feed extends AppCompatActivity {
    private ImageView imageview;
    private TextView mtitle, mdesc,mdate;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_image_view_for__feed);
        imageview=(ImageView)findViewById(R.id.imageView);
        mtitle=(TextView)findViewById(R.id.textView5);
        mdate=(TextView)findViewById(R.id.textView6);
        mdesc=(TextView)findViewById(R.id.textView7);
        // imageview.setImageResource(R.drawable.large_image);
        String url="",description="",title="",date="";
        Bundle extras = getIntent().getExtras();
        if (extras != null) {
            url=extras.getString("imageURL");
            description=extras.getString("description");
            title=extras.getString("title");
            date=extras.getString("date");

            //The key argument here must match that used in the other activity
        }
        mtitle.setText(title);
        mdate.setText(date);
        mdesc.setText(description);
        Picasso.with(getApplicationContext()).load("http://web.rackupcambridge.com"+url)
                .placeholder(R.drawable.no_thumbnail)
                .error(R.drawable.no_thumbnail)
                .into(imageview);


    }
    public void onBackPressed() {
        finish();
    }

}
