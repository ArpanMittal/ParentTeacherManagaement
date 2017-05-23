package com.eurovisionedusolutions.android.rackup;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.ImageView;

public class ImageView_for_Feed extends AppCompatActivity {
private ImageView imageview;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_image_view_for__feed);
        imageview=(ImageView)findViewById(R.id.imageView);
        imageview.setImageResource(R.drawable.large_image);


    }
}
