package com.eurovisionedusolutions.android.rackup;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;
import android.support.v4.view.*;
import android.support.v4.view.PagerAdapter;
import android.view.View;

import java.io.Serializable;
import java.util.List;

/**
 * Created by arpan on 9/1/2017.
 */

public class CustomPagerAdapter extends FragmentStatePagerAdapter {
    private Context mContext;
    private List<EventModel_Feed> mEventFeed;
    private int currentPosition;


    public CustomPagerAdapter(FragmentManager fm, Context context, List<EventModel_Feed> list, int cuurent_position) {
        super(fm);
        mContext = context;
        mEventFeed = list;
        this.currentPosition = cuurent_position;
    }

    @Override
    public int getCount() {
        return mEventFeed.size();
    }

    @Override
    public Fragment getItem(int position) {
        Bundle bundle=new Bundle();

        bundle.putSerializable("List",(Serializable)mEventFeed);
        bundle.putInt("Position", position);

        ImageFeedFragment question_answer_fragment=new ImageFeedFragment();
        question_answer_fragment.setArguments(bundle);
        return question_answer_fragment;
    }




}
