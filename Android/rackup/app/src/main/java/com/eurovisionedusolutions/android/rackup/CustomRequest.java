package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/3/2017.
 */

import com.android.volley.AuthFailureError;
import com.android.volley.NetworkResponse;
import com.android.volley.ParseError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.toolbox.HttpHeaderParser;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.Map;


public class CustomRequest extends Request<JSONArray> {

    private Response.Listener<JSONArray> listener;
    private Map<String, String> params;
    private Map<String, String> header;

  /*  public CustomRequest(String url, Map<String, String> params, Response.Listener<JSONObject> responseListener, Response.ErrorListener errorListener) {
        super(Method.GET, url, errorListener);
        this.listener = responseListener;
        this.params = params;
    }*/

    public CustomRequest(int method, String url, Map<String, String> params, Map<String, String> header, Response.Listener<JSONArray> reponseListener, Response.ErrorListener errorListener) {
        super(method, url, errorListener);
        this.listener = reponseListener;
        this.params = params;
        this.header = header;
    }

    @Override
    protected Map<String, String> getParams() throws com.android.volley.AuthFailureError {
        return params;
    }

    ;

    @Override
    public Map<String, String> getHeaders() throws AuthFailureError {
        if (header == null) {
            return super.getHeaders();
        }
        return header;
    }

    @Override
    protected Response<JSONArray> parseNetworkResponse(NetworkResponse response) {
        try {
            String jsonString = new String(response.data, HttpHeaderParser.parseCharset(response.headers));

            return Response.success(new JSONArray(jsonString), HttpHeaderParser.parseCacheHeaders(response));
        } catch (UnsupportedEncodingException e) {
            return Response.error(new ParseError(e));
        } catch (JSONException je) {
            return Response.error(new ParseError(je));
        }
    }

    protected void deliverResponse(JSONArray response) {
        listener.onResponse(response);
    }
}
