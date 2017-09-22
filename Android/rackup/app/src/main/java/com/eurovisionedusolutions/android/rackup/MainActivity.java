/*
 * Copyright (c) 2016. Truiton (http://www.truiton.com/).
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * Mohit Gupt (https://github.com/mohitgupt)
 *
 */

package com.eurovisionedusolutions.android.rackup;

import android.app.ActionBar;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.design.widget.TabLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.ActionBarActivity;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.view.View;
import android.widget.Toast;

public class MainActivity extends AppCompatActivity{

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        BottomNavigationView bottomNavigationView = (BottomNavigationView)
                findViewById(R.id.navigation);
        //ActionBar actionBar=getSupportActionBar();
       // actionBar.show();
        //bottomNavigationView.setScrollbarFadingEnabled(true);
        BottomNavigationViewHelper.disableShiftMode(bottomNavigationView);
        final View decorView = getWindow().getDecorView();
        final int uiOptions = View.SYSTEM_UI_FLAG_HIDE_NAVIGATION
                | View.SYSTEM_UI_FLAG_FULLSCREEN;
        decorView.setVisibility(decorView.VISIBLE);
        decorView.setOnSystemUiVisibilityChangeListener
                (new View.OnSystemUiVisibilityChangeListener() {
                    @Override
                    public void onSystemUiVisibilityChange(int visibility) {
                        // Note that system bars will only be "visible" if none of the
                        // LOW_PROFILE, HIDE_NAVIGATION, or FULLSCREEN flags are set.
                        if ((visibility & View.SYSTEM_UI_FLAG_FULLSCREEN) == 0) {
                            // TODO: The system bars are visible. Make any desired
                            // adjustments to your UI, such as showing the action bar or
                            // other navigational controls.

                           // Toast.makeText(getApplicationContext(), "yo", Toast.LENGTH_LONG).show();

                        } else {
                            // TODO: The system bars are NOT visible. Make any desired
                            // adjustments to your UI, such as hiding the action bar or
                            // other navigational controls.

                            //Toast.makeText(getApplicationContext(), "ya", Toast.LENGTH_LONG).show();
                        }
                    }
                });



        bottomNavigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
                    @Override
                    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                        Fragment selectedFragment = null;
                        switch (item.getItemId()) {
                            case R.id.action_item1:
                                selectedFragment = Tab_fragment.newInstance();
                                break;
                            case R.id.action_item2:
                                selectedFragment = Video_Call.newInstance();
                                break;
                            case R.id.action_item3:

                               selectedFragment =Edit_profile.newInstance();
                                break;
                            case R.id.action_item4:
                                selectedFragment =Calendar_fragment.newInstance();
                                break;
                            case R.id.action_item5:
                                selectedFragment=Feed_Activity.newInstance();
                                break;

                        }
                        if(item.getItemId() == R.id.action_item5)
                            getSupportActionBar().setTitle("ImageFeed");
//                        else if(item.getItemId() == R.id.action_item4)
//                            getSupportActionBar().setTitle("Calendar");
//                        else if(item.getItemId() == R.id.action_item1)
//                            getSupportActionBar().setTitle("Video");

                        FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
                        transaction.replace(R.id.frame_layout, selectedFragment);
                        transaction.commit();
                        return true;
                    }
                });
        //Manually displaying the first fragment - one time only
        FragmentTransaction transaction = getSupportFragmentManager().beginTransaction();
        transaction.replace(R.id.frame_layout, Tab_fragment.newInstance());
        transaction.commit();



        //Used to select an item programmatically
        bottomNavigationView.getMenu().getItem(1).setChecked(true);
    }
}
