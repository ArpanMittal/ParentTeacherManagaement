package com.eurovisionedusolutions.android.rackup;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;

import com.google.android.gms.gcm.GcmListenerService;

/**
 * Created by arpan on 9/14/2017.
 */

public class GcmBroadcastReceiver extends GcmListenerService {

    @Override
    public void onMessageReceived(String from, Bundle data) {
        Log.d("brodcast","GCMLISTENER here");
//        Intent intent = new Intent(this, PushReceiverIntentService.class);
//        intent.putExtras(data);
//        startService(intent);
    }
}