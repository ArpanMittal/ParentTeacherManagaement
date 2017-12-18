package com.eurovisionedusolutions.android.rackup;

import android.app.Activity;
import android.location.Location;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.GenericTypeIndicator;
import com.google.firebase.database.ValueEventListener;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import static android.icu.lang.UCharacter.GraphemeClusterBreak.L;

/**
 * Created by arpan on 10/25/2017.
 */

public class Maps_Fragment extends Fragment implements OnMapReadyCallback {
    private static GoogleMap mMap;
    private FragmentActivity myContext;
    private DatabaseReference mDatabase;
    private double latitude = -34, longitude = 151;
    private static Marker marker;
    public static Maps_Fragment newInstance() {
        return new Maps_Fragment();
    }

//    @Override
//    public void onAttach(Activity activity) {
//        myContext=(FragmentActivity) activity;
//        super.onAttach(activity);
//    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        //return super.onCreateView(inflater, container, savedInstanceState);
        View view = inflater.inflate(R.layout.activity_maps, container, false);
        mDatabase = FirebaseDatabase.getInstance().getReference();
        final Toolbar toolbar = (Toolbar) view.findViewById(R.id.toolbar);

        if(new SharedPrefrence().getSchoolId(getContext()).equals("1")){
            latitude = 28.479877700000003;
            longitude = 77.0967245;
        }else{
            latitude = 25.15106959999999;
            longitude = 75.83976940000002;
        }

        mDatabase.child("raw-locations").addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                String value = dataSnapshot.getKey();

                double lat = 0, lng = 0, time = 0;


                List<Custom_Location> customData = new ArrayList<Custom_Location>();
                for (DataSnapshot postSnapshot : dataSnapshot.getChildren()) {
                    List<String> custom_locations = new ArrayList<>();
                    for(DataSnapshot childSnapchot : postSnapshot.getChildren()){
                        custom_locations.add(childSnapchot.getValue().toString());

                        break;
                    }
                    customData.add(new Custom_Location(postSnapshot.getKey(), custom_locations));
//                    postSnapshot.getValue((GenericTypeIndicator<Object>) custom_locations);
                }
                try {
                    Log.d("come", customData.get(1).getId());
                    //TODO: make customdata to segregate between different field
//                    if(customData.get(1).getId().equals("Arpan")) {
                        JSONObject jsonObject = new JSONObject(customData.get(1).getCustom_lists().get(0));
                        lat = jsonObject.getDouble("lat");
                        lng = jsonObject.getDouble("lng");
                        time = jsonObject.getDouble("time");
                        toolbar.setTitle("Location");
//                    }
                } catch (JSONException e) {
                    Log.d("come","error");
                    e.printStackTrace();
                }
//                String custom = custom_locations.get(0);

                if(mMap!=null && lat != 0 && lng != 0){
                    if(marker!= null){
                        marker.remove();
                    }
                    LatLng sydney = new LatLng(lat, lng);
                    mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(sydney, 15));
                    marker = mMap.addMarker(new MarkerOptions().position(sydney).title("Current Position"));
                }

//
//                Toast.makeText(getContext(), Double.toString(custom_locations.get(0).getLatitude()),Toast.LENGTH_LONG).show();
            }

            @Override
            public void onCancelled(DatabaseError databaseError) {
                Toast.makeText(getContext(),"Please Check Connectivity Or Refresh",Toast.LENGTH_LONG).show();
            }
        });

        toolbar.setTitleTextColor(getResources().getColor(R.color.black));
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);
        SupportMapFragment mapFragment = (SupportMapFragment)getChildFragmentManager().findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);
        return view;
    }




    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;

        // Add a marker in Sydney, Australia, and move the camera.
        LatLng sydney = new LatLng(latitude, longitude);
        marker = mMap.addMarker(new MarkerOptions().position(sydney).title("Current Position"));
//        mMap.moveCamera(CameraUpdateFactory.newLatLng(sydney));

        mMap.animateCamera(CameraUpdateFactory.newLatLngZoom(new LatLng(latitude, longitude), 15.0f));
    }






}
