package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 5/10/2017.
 */

import android.content.Intent;

import com.google.android.gms.iid.InstanceIDListenerService;



public class GCMTokenRefreshListenerService extends InstanceIDListenerService {

    //If the token is changed registering the device again
    @Override
    public void onTokenRefresh() {
        Intent intent = new Intent(this, GCMRegistrationIntentService.class);
        startService(intent);
    }
}