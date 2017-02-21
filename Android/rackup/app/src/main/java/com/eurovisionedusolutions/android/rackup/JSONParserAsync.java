package com.eurovisionedusolutions.android.rackup;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Map;

/**
 * Created by sushant on 2/2/2017.
 */


public class JSONParserAsync implements RemoteCallHandler {
    final String tag_json_obj = "json_obj_req";
    private final RemoteCalls callFor;
    String url;
    Map<String, String> params;
    Map<String, String> header;
    JSONObject jsonObject;
    boolean isRemoteCallSuccessful = false;
    Exception exception;
    CustomRequest req;
    private RemoteCallHandler listener;

    //TODO: Remove list parameter from this call
    public JSONParserAsync(String url, Map<String, String> params, Map<String, String> header, RemoteCallHandler caller, RemoteCalls callFor) {
        this.url = url;
        this.listener = caller;
        this.callFor = callFor;

        this.params = params;
        this.header = header;


        // Tag used to cancel the request

        req = new CustomRequest(Request.Method.POST, url,
                this.params,
                this.header,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        // response
                        JSONParserAsync.this.isRemoteCallSuccessful = true;
                        try {


                            if (response.get("code").toString().equals(GlobalConstants.EXPIRED_TOKEN)) {


                            } else {
                                JSONParserAsync.this.listener.HandleRemoteCall(JSONParserAsync.this.isRemoteCallSuccessful, JSONParserAsync.this.callFor, response, JSONParserAsync.this.exception);
                            }
                        } catch (JSONException e) {
                            JSONParserAsync.this.listener.HandleRemoteCall(JSONParserAsync.this.isRemoteCallSuccessful, JSONParserAsync.this.callFor, response, JSONParserAsync.this.exception);
                            //e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {

                    @Override
                    public void onErrorResponse(VolleyError error) {
                        JSONParserAsync.this.isRemoteCallSuccessful = false;
                        JSONParserAsync.this.exception = error;
                        JSONParserAsync.this.listener.HandleRemoteCall(JSONParserAsync.this.isRemoteCallSuccessful, JSONParserAsync.this.callFor, null, JSONParserAsync.this.exception);
                    }
                });
        //setting time out and retries
        req.setRetryPolicy(new DefaultRetryPolicy(40 * 1000, 0, 1.0f));
        // Adding request to request queue
        VolleyController.getInstance().addToRequestQueue(req, tag_json_obj);
    }

//

    @Override
    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONObject response, Exception exception) {
        if (isSuccessful) {
            try {

                req.getParams().put("access_token", response.get("access_token").toString());
                VolleyController.getInstance().addToRequestQueue(req, tag_json_obj);
                //new RemoteHelper(getApplicationContext()).getUserDetails(this,RemoteCalls.GET_USER_DETAILS,response.get("access_token").toString());


            } catch (Exception e) {
                LogHelper logHelper = new LogHelper(e);
                e.printStackTrace();
            }
        }
    }

}