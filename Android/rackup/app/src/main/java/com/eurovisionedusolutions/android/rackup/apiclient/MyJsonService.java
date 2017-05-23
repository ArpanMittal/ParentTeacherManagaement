package com.eurovisionedusolutions.android.rackup.apiclient;

import com.eurovisionedusolutions.android.rackup.AsynchronousActivity;

import retrofit.http.GET;

import static com.android.volley.Request.Method.GET;

/**
 * Created by Raquib-ul-Alam Kanak on 1/3/16.
 * Website: http://alamkanak.github.io
 */
public interface MyJsonService {

    @GET("/1kpjf")
    void listEvents(AsynchronousActivity eventsCallback);

}
