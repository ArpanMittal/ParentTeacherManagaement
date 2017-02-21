package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/2/2017.
 */

import org.json.JSONObject;


public interface RemoteCallHandler {

    void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONObject response, Exception exception);
}