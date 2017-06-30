package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 5/25/2017.
 */

import android.content.ClipData;
import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.text.format.DateUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.squareup.picasso.Picasso;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;

import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.Date;


public class MyAdapter_appointment extends RecyclerView.Adapter<MyAdapter_appointment.ViewHolder>  {
    private Context context;
    private Context context1;

    public MyAdapter_appointment(Context context, ArrayList<Event_Model_appointment> items){
        this.context=context;
        this.items = items;
        context1=context;
    }
    private final ArrayList<Event_Model_appointment> items;

    public MyAdapter_appointment(final ArrayList<Event_Model_appointment> items) {
        this.items = items;
    }

    @Override
    public ViewHolder onCreateViewHolder(final ViewGroup parent, final int viewType) {
        final Context context = parent.getContext();
        context1=context;
        final View view = LayoutInflater.from(context).inflate(R.layout.item_cancelled_events, parent, false);
        final ViewHolder viewHolder = new ViewHolder(view);
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {
        String status="",requestby="";
        int event_type=Integer.parseInt(items.get(position).getStatus());
        if(items.get(position).getRequestby()=="Parent Request"){requestby="YOU";}
        else {requestby=items.get(position).getTeacherName();}
        if(event_type==1){status="Awaited";}
        else if(event_type==2){status="Confirmed";}
        else if(event_type==3){status="Cancelled";}
        else if(event_type==4){status="Invalid";}
        else if(event_type==5){status="Free Slot";}
        else if(event_type==6){status="School Event";}
        else {status="invalid";}
        String date1=items.get(position).getDate() + " "+items.get(position).getStartTime();
        long now = System.currentTimeMillis();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        Date date = null;
        long epoch= 0;
        try {
            date = df.parse(date1);
            epoch = date.getTime();
        } catch (ParseException e) {
            e.printStackTrace();
        }

        String date2=(DateUtils.getRelativeTimeSpanString(epoch, now, DateUtils.DAY_IN_MILLIS)).toString();
        holder.date_human_readable.setText(date2);

        holder.title.setText("Appointment "+status);
        holder.startTime.setText("Start Time : "+ items.get(position).getStartTime());
        holder.endTime.setText("End Time : " + items.get(position).getEndTime());
        holder.date.setText("Date : " + items.get(position).getDate());
        holder.status.setText("Status : " + status);
        holder.teacherName.setText("Teacher Name: "+ items.get(position).getTeacherName());
        holder.teacherContact.setText("Teacher Contact : "+ items.get(position).getTeacherContact());
        holder.requestby.setText(requestby);
        holder.reason.setText("Reason:"+items.get(position).getReason());


         //final Context context = parent.getContext();
      /*  final String itemText = items.get(position).getTitle();
        final String date=items.get(position).getTime();
        final String description=items.get(position).getDescription();
        Picasso.with(context1).load("http://web.rackupcambridge.com"+items.get(position).getImage_url()).into(holder.imageView);
        //Picasso.with(context1).load("http://web.rackupcambridge.com/storage/1/50_republic day 2.jpg").into(holder.imageView);
        holder.tvItem.setText(itemText);
        holder.dateView.setText(date);
        holder.description.setText(description);*/

    /*if(position%2==1){
    holder.imageView.setImageResource(R.drawable.large_image);}
    else {holder.imageView.setImageResource(R.drawable.arduino);}*/

        holder.itemView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // holder.tvItem.setText(itemText+","+ String.valueOf(position));

               /* Intent act= new Intent(context,ImageView_for_Feed.class);
                act.putExtra("imageURL",items.get(position).getImage_url());
                act.putExtra("date",items.get(position).getTime());
                act.putExtra("description",items.get(position).getDescription());
                act.putExtra("title",items.get(position).getTitle());
                context.startActivity(act);

                context.startActivity(act);*/

            }
        });
    }

    @Override
    public int getItemCount() {
        return items.size();
    }

    public ArrayList<Event_Model_appointment> getItems() {
        return items;
    }



    public class ViewHolder extends RecyclerView.ViewHolder {
        protected TextView title,date_human_readable,startTime, endTime, date, status, requestby,reason,teacherName, teacherContact;

        public ViewHolder(final View itemView) {
            super(itemView);
            title = (TextView) itemView.findViewById(R.id.title);
            date_human_readable= (TextView) itemView.findViewById(R.id.date_human_readable);
            startTime = (TextView) itemView.findViewById(R.id.startTime);
            endTime = (TextView) itemView.findViewById(R.id.endTime);
            date = (TextView) itemView.findViewById(R.id.date);
            status = (TextView) itemView.findViewById(R.id.status);
            requestby = (TextView) itemView.findViewById(R.id.requestedby);
            reason = (TextView) itemView.findViewById(R.id.reason);
            teacherName = (TextView) itemView.findViewById(R.id.teacherName);
            teacherContact= (TextView) itemView.findViewById(R.id.teacherContact);




        }
    }
}
