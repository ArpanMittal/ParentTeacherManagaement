package com.eurovisionedusolutions.android.rackup;

import android.content.Context;

/**
 * Created by sushant on 2/2/2017.
 */


import android.accounts.NetworkErrorException;
import android.content.Context;
import android.net.wifi.WifiInfo;
import android.net.wifi.WifiManager;
import android.os.AsyncTask;
import android.provider.Settings;
import android.util.Log;
import android.view.View;

import com.android.volley.Request;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.security.MessageDigest;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Objects;

/**
 * Created by Prateek Tulsyan on 10-03-2015.
 * Email: prateek.tulsyan13@gmail.com
 * Organization: St. Joseph's Hitech Gurukul.
 */

public class RemoteHelper {
    Context context;
    String SIGNUP_PAGE;
    String GET_ACESS_TOKEN;
    String GET_USER_DETAIL;
    String SEND_QUESTION_RESPONSE;
    String GET_DASHBOARD_DETAILS;
    String GET_ITEM_DETAILS;
    String CHANGE_STREAM_START_POINT;
    String GET_FREE_QUEST_DETAILS;
    String GET_SEARCH;
    String GET_QUEST_DETAILS;
    String GET_QUESTION;
    String GET_TEST_SUMMARY;
    String GET_PROFILE_DETAIL;
    String SAVE_PROFILE_DETAIL;
    String GET_DASHBOARD_IMAGE_LIST;
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    String LOGIN_PAGE;
    String FETCH_CONTENT_PAGE;
    String INSERT_MAC;
    String ACTIVE_SESSIONS;
    String UPDATE_STUDENT_STATUS;
    String UPDATE_SESSION_STATUS;
    String TEST_AVAILABLE_PAGE;
    String FETCH_LAUGHGURU_PAGE;
    String ADAPTIVE_TEST_PAGE;
    String FETCH_NOTES_PAGE;
    String GET_SINGLE_TEST;
    String GET_SINGLEADAPTIVE_TEST;
    String GET_STUDENT_STATUS;
    String SUBSCRIPTION_SUBJECTS;
    String EVENT_DETAILS;
    String GET_GOOGLE_AUTH_DETAILS;
    String SAVE_USER_BAG;
    String GET_USER_BAG_DETAILS;
    String GET_PAYMENT_INFORMATION;
    String DELETE_USER_BAG;

    public RemoteHelper(Context context) {
        this.context = context;
        GET_DASHBOARD_IMAGE_LIST="api/v1/getDashBoardImageDetails";
        SEND_QUESTION_RESPONSE="api/v1/saveQuestionResponse";
        DELETE_USER_BAG = "api/v1/userCartDelete";
        SIGNUP_PAGE=this.context.getResources().getString(R.string.get_sign_up_page);
        GET_ACESS_TOKEN=this.context.getResources().getString(R.string.getaccesstoken);
        GET_USER_DETAIL=this.context.getResources().getString(R.string.getuserdetail);
        GET_GOOGLE_AUTH_DETAILS=context.getResources().getString(R.string.getGoogleAccountDetails);
        GET_DASHBOARD_DETAILS="api/v1/getDashBoardDetails";
        GET_ITEM_DETAILS="api/v1/getDetails";
        GET_FREE_QUEST_DETAILS = "api/v1/freetopics/chapter";
        GET_QUEST_DETAILS = "api/v1/topics/chapter";
        GET_QUESTION="api/v1/getQuestion";
        GET_SEARCH = "api/v1/search";
        GET_PROFILE_DETAIL = "api/v1/profile/detail";
        SAVE_PROFILE_DETAIL =  "api/v1/profile/save";
        GET_TEST_SUMMARY=this.context.getResources().getString(R.string.get_test_summary);
        FETCH_NOTES_PAGE = this.context.getResources().getString(R.string.fetch_notes_page);
        SAVE_USER_BAG="api/v1/userCart";
        GET_USER_BAG_DETAILS="api/v1/userPackage";
        GET_PAYMENT_INFORMATION="api/v1/userOrder";
        CHANGE_STREAM_START_POINT = this.context.getResources().getString(R.string.change_stream_start_point_page);
        //////////////////////////////////////////////////////////////////////////////////////
        GET_SINGLEADAPTIVE_TEST=this.context.getResources().getString(R.string.get_single_adaptive_test);
        LOGIN_PAGE = this.context.getResources().getString(R.string.login_page);
        FETCH_CONTENT_PAGE = this.context.getResources().getString(R.string.fetch_content_page);
        INSERT_MAC = this.context.getResources().getString(R.string.insert_mac_address);
        ACTIVE_SESSIONS = this.context.getResources().getString(R.string.active_sessions);
        UPDATE_STUDENT_STATUS = this.context.getResources().getString(R.string.update_student_status);
        UPDATE_SESSION_STATUS = this.context.getResources().getString(R.string.update_session_status);
        TEST_AVAILABLE_PAGE = context.getResources().getString(R.string.test_available_page);
        FETCH_LAUGHGURU_PAGE=this.context.getResources().getString(R.string.fetch_laughguru_page);
        GET_SINGLE_TEST = this.context.getResources().getString(R.string.get_single_test);
        GET_STUDENT_STATUS = this.context.getResources().getString(R.string.get_student_status);
        SUBSCRIPTION_SUBJECTS = this.context.getResources().getString(R.string.subscription_subjects);
        EVENT_DETAILS = this.context.getResources().getString(R.string.event_details);
        ADAPTIVE_TEST_PAGE=this.context.getResources().getString(R.string.adaptive_test_page);
    }
    public void verifyLogin1(RemoteCallHandler caller1,RemoteCalls functionCalled,String image, String name, String contact, String address,
                             String Sname,String dob, String Sclass,String Saddress)
    {
        String verifyLoginurl="http://14.192.16.145/celearn/laravel/public/test";
        Map<String, String> params = new HashMap<String, String>();

        params.put("image",image);
        params.put("name",name);
        params.put("contact",contact);
        params.put("address",address);
        params.put("Sname",Sname);
        params.put("dob",dob);
        params.put("Sclass",Sclass);
        params.put("Saddress",Saddress);


        Map<String, String> header = new HashMap<String, String>();
        header.put("Content-Type","application/x-www-form-urlencoded");
        new JSONParserAsync(verifyLoginurl,params,header,caller1,functionCalled);

    }
    public void verifyLogin(RemoteCallHandler caller,RemoteCalls functionCalled,String email,String password)
    {
        String verifyLoginurl="http://14.192.16.145/celearn/laravel/public/test";
        Map<String, String> params = new HashMap<String, String>();
        params.put("client_id",GlobalConstants.CLIENT_ID);
        params.put("client_secret",GlobalConstants.CLINET_SECRET);
        params.put("grant_type",GlobalConstants.PASSWORD_GRANTTYPE);
        params.put("username", email);
        params.put("password", password);
        Map<String, String> header = new HashMap<String, String>();
        header.put("Content-Type","application/x-www-form-urlencoded");
        new JSONParserAsync(verifyLoginurl,params,header,caller,functionCalled);

    }
}

