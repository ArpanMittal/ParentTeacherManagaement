package com.eurovisionedusolutions.android.rackup;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;

public class PagerAdapter extends FragmentStatePagerAdapter {
    int mNumOfTabs;

    public PagerAdapter(FragmentManager fm, int NumOfTabs) {
        super(fm);
        this.mNumOfTabs = NumOfTabs;
    }

    public Fragment getItem(int position) {
        Bundle args = new Bundle();
        Test_fragment tab1 = new Test_fragment();
        args.putString("key", String.valueOf(position));
        tab1.setArguments(args);
        return tab1;
    }

    public int getCount() {
        return this.mNumOfTabs;
    }
}
