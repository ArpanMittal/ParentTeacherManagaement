package com.eurovisionedusolutions.android.rackup;

import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.support.v4.content.WakefulBroadcastReceiver;
import android.util.Log;

import com.google.android.gms.gcm.GcmReceiver;

/**
 * Created by arpan on 9/15/2017.
 */

public class GcmMainBroadcastReceiver extends WakefulBroadcastReceiver {
    @Override
    public void onReceive(Context context, Intent intent) {
        Log.d("brodcast","reached here");
        // Explicitly specify that GcmIntentService will handle the intent.
        ComponentName comp = new ComponentName(context.getPackageName(),
                PushReceiverIntentService.class.getName());
        // Start the service, keeping the device awake while it is launching.
        startWakefulService(context, (intent.setComponent(comp)));
//        completeWakefulIntent(intent);
        // http://developer.android.com/reference/android/content/BroadcastReceiver.html#setResultCode(int)
        setResultCode(Activity.RESULT_OK);
    }

}
