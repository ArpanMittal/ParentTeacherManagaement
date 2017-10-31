package com.eurovisionedusolutions.android.rackup;

import android.content.res.Configuration;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.text.method.Touch;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;


import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;



import static com.eurovisionedusolutions.android.rackup.R.id.image;
import static com.eurovisionedusolutions.android.rackup.R.id.imageView;
import static com.eurovisionedusolutions.android.rackup.R.id.imageview;

/**
 * Created by arpan on 9/1/2017.
 */

public class ImageFeedFragment extends Fragment {

    List<EventModel_Feed> questionAnswerLists=new ArrayList<>();
    private int position;
    private TouchImageView imageview;

    private TextView mtitle, mdesc,mdate;

    public  ImageFeedFragment()
    {

    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Bundle bundle=this.getArguments();
        this.questionAnswerLists= (List<EventModel_Feed>) bundle.getSerializable("List");
        this.position = bundle.getInt("Position");
        //List<EventModel_Feed> list = questionAnswerLists;

    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState)
    {
        // super.onCreateView(inflater, container, savedInstanceState);
        View rootView = inflater.inflate(R.layout.activity_image_view_for__feed, container, false);
        imageview=(TouchImageView) rootView.findViewById(R.id.imageView);
        mtitle=(TextView)rootView.findViewById(R.id.textView5);
        mdate=(TextView)rootView.findViewById(R.id.textView6);
        mdesc=(TextView)rootView.findViewById(R.id.textView7);
        // imageview.setImageResource(R.drawable.large_image);
        String url="",description="",title="",date="";

            url = questionAnswerLists.get(position).getImage_url();
            description = questionAnswerLists.get(position).getDescription();
            title = questionAnswerLists.get(position).getTitle();
            date = questionAnswerLists.get(position).getTime();

            //The key argument here must match that used in the other activity

        mtitle.setText(title);
        mdate.setText(date);
        mdesc.setText(description);
        Picasso.with(getActivity()).load("http://web.rackupcambridge.com"+url)
                .placeholder(R.drawable.no_thumbnail)
                .error(R.drawable.no_thumbnail)
                .into(imageview);
//        if(!imageview.isZoomed())
//            ImageFeedViewPager.viewPager.setPagingEnabled(true);
//        else
//            ImageFeedViewPager.viewPager.setPagingEnabled(false);




        return rootView;
    }

    @Override
    public void onResume() {
        super.onResume();
        if(getResources().getConfiguration().orientation == Configuration.ORIENTATION_LANDSCAPE) {
            //Do some stuff
            mtitle.setVisibility(View.GONE);
            mdate.setVisibility(View.GONE);
            mdesc.setVisibility(View.GONE);
        }else{
            mtitle.setVisibility(View.VISIBLE);
            mdate.setVisibility(View.VISIBLE);
            mdesc.setVisibility(View.VISIBLE);
        }
    }

    public void onBackPressed() {
        getActivity().finish();
    }

}
