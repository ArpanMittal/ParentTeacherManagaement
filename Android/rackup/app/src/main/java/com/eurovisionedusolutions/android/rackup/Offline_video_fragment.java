package com.eurovisionedusolutions.android.rackup;

import android.*;
import android.Manifest;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.annotation.Nullable;
import android.support.annotation.RequiresApi;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.Fragment;
import android.support.v4.content.ContextCompat;
import android.support.v4.content.FileProvider;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.GridLayout;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.Toast;

import java.io.File;
import java.util.ArrayList;

/**
 * Created by arpan on 9/11/2017.
 */

public class Offline_video_fragment extends Fragment {
    private Cursor videoCursor;
    private int videoColumnIndex;
    ListView videolist;
    int count;
    public static final int MY_PERMISSIONS_REQUEST_READ_EXTERNAL = 123;
    String[] videoProjection;
    View v;


    @RequiresApi(api = Build.VERSION_CODES.M)
    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        v = inflater.inflate(R.layout.fragment_top_rated, container, false);
        System.gc();
        videoProjection = new String[]{MediaStore.Video.Media._ID, MediaStore.Video.Media.DATA, MediaStore.Video.Media.DISPLAY_NAME, MediaStore.Video.Media.SIZE, MediaStore.Video.Media.MIME_TYPE};


        if(checkPermission()){
            showView();
        }

//        videoCursor = getActivity().managedQuery(MediaStore.Video.Media.EXTERNAL_CONTENT_URI,videoProjection, null, null, null);
//        count = videoCursor.getCount();
//        GridView list = (GridView) v.findViewById(R.id.listView1);
//        list.setAdapter(new Offline_Video_List_Adapter(getActivity(),videoCursor));


        return v;
    }

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN)

    public boolean checkPermission()
    {
        int currentAPIVersion = Build.VERSION.SDK_INT;
        if(currentAPIVersion>=android.os.Build.VERSION_CODES.M)
        {
            if (ContextCompat.checkSelfPermission(getActivity(), android.Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED)
            {
                if (ActivityCompat.shouldShowRequestPermissionRationale(getActivity(), android.Manifest.permission.READ_EXTERNAL_STORAGE)) {
                    AlertDialog.Builder alertBuilder = new AlertDialog.Builder(getActivity());
                    alertBuilder.setCancelable(true);
                    alertBuilder.setTitle("Permission necessary");
                    alertBuilder.setMessage("Write calendar permission is necessary to write event!!!");
                    alertBuilder.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                        @TargetApi(Build.VERSION_CODES.JELLY_BEAN)
                        public void onClick(DialogInterface dialog, int which) {
                            ActivityCompat.requestPermissions(getActivity(), new String[]{android.Manifest.permission.READ_EXTERNAL_STORAGE},  MY_PERMISSIONS_REQUEST_READ_EXTERNAL);
                        }
                    });
                    AlertDialog alert = alertBuilder.create();
                    alert.show();
                }
                else {
                    ActivityCompat.requestPermissions(getActivity(), new String[]{Manifest.permission.READ_EXTERNAL_STORAGE},  MY_PERMISSIONS_REQUEST_READ_EXTERNAL);
                }
                return false;
            }
            else {
                return true;
            }
        } else {
           return false;
        }
    }
    public void showView(){
        videoCursor = getActivity().managedQuery(MediaStore.Video.Media.EXTERNAL_CONTENT_URI,videoProjection, null, null, null);
        count = videoCursor.getCount();
        final GridView list = (GridView) v.findViewById(R.id.listView1);
        list.setAdapter(new Offline_Video_List_Adapter(getActivity(),videoCursor));
        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (videoCursor.moveToPosition(position)) {
                    int fileColumn = videoCursor.getColumnIndexOrThrow(MediaStore.Video.Media.DATA);
                    int mimeColumn = videoCursor.getColumnIndexOrThrow(MediaStore.Video.Media.MIME_TYPE);
                    String videoFilePath = videoCursor.getString(fileColumn);
                    String mimeType = videoCursor.getString(mimeColumn);
                    File newFile = new File(videoFilePath);
                    Uri photoURI = FileProvider.getUriForFile(getActivity(),  BuildConfig.APPLICATION_ID  + ".provider", newFile);
                    Log.i("photouri",photoURI.toString());
                    Intent intent = new Intent(android.content.Intent.ACTION_VIEW,photoURI);
                    intent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION);
                    intent.setDataAndType(photoURI, mimeType);
                    startActivity(intent);
                }
            }
        });


    }


    @Override
    public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults) {
        switch (requestCode) {
            case  MY_PERMISSIONS_REQUEST_READ_EXTERNAL:
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    showView();
                } else {
                    Toast.makeText(getActivity(),"Permission denied",Toast.LENGTH_LONG).show();
                    //code for denyima
//                    ImageView imageView = (ImageView)(v.findViewById(R.id.permission_denied));
//                    imageView.setVisibility(View.VISIBLE);
//                    GridView list = (GridView) v.findViewById(R.id.listView1);
//                    list.setVisibility(View.GONE);

                }
                break;
        }
    }


}
