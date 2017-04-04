package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/2/2017.
 */

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


public interface RemoteCallHandler {

    void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) throws JSONException;
}