package com.eurovisionedusolutions.android.rackup;

import android.content.Context;
import android.graphics.Color;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.desai.vatsal.mydynamiccalendar.AppConstants;
import com.desai.vatsal.mydynamiccalendar.EventListAdapter;
import com.desai.vatsal.mydynamiccalendar.EventModel;

import java.util.ArrayList;

import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Toast;

import com.desai.vatsal.mydynamiccalendar.EventListAdapter;
import com.desai.vatsal.mydynamiccalendar.EventModel;

import java.util.ArrayList;
import java.util.StringTokenizer;

import static java.security.AccessController.getContext;

/**
 * Created by sushant on 5/15/2017.
 */

public class EventListAdapter_extended  extends RecyclerView.Adapter<RecyclerView.ViewHolder> {


    private Context context;
    private String strViewFlag;
    private ArrayList<EventModel> eventModelList;



    public interface OnItemClickListener {
        public void onItemClick(View view, int position);

        public void onLongItemClick(View view, int position);
    }
    public EventListAdapter_extended(Context context, ArrayList<EventModel> eventModelList, String strViewFlag) {
        this.context = context;
        this.strViewFlag = strViewFlag;
        this.eventModelList = eventModelList;

    }class EventViewHolder extends RecyclerView.ViewHolder {

        LinearLayout ll_month_events;
        TextView tv_event_name, tv_event_date, tv_event_time, tv_event_simbol;
        View v_divider;

        public EventViewHolder(View itemView) {
            super(itemView);
            ll_month_events = (LinearLayout) itemView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_month_events);
            tv_event_name = (TextView) itemView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_event_name);
            tv_event_date = (TextView) itemView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_event_date);
            tv_event_time = (TextView) itemView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_event_time);
            tv_event_simbol = (TextView) itemView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_event_simbol);
            v_divider = (View) itemView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.v_divider);

        }

        public void setEvent(EventModel model) {

            if (strViewFlag.equals("month")) {
                ll_month_events.setVisibility(View.VISIBLE);

                if (model.getImage() != -1) {
                    tv_event_simbol.setBackgroundResource(model.getImage());
                } else {
                    tv_event_simbol.setBackgroundResource(com.desai.vatsal.mydynamiccalendar.R.drawable.event_view);
                }
                String name_concat=model.getStrName();
                String event_title;
                event_title=name_concat.substring(name_concat.indexOf("@")+1,name_concat.indexOf("#"));
                name_concat=name_concat.substring(0,name_concat.indexOf("/"));

                int event_title_int=Integer.parseInt(event_title);
                if(event_title_int==5){
                    name_concat=name_concat+" (Available)";
                }
                else if(event_title_int==1){
                    name_concat=name_concat+" (Awaited)";
                }
                else if(event_title_int==3){
                    name_concat=name_concat+" (Cancelled)";
                }
                else if(event_title_int==2){
                    name_concat=name_concat+" (Confirmed)";
                }
                else if(event_title_int==4){
                    name_concat="Invalid";
                }
                tv_event_name.setText(name_concat);
                tv_event_date.setText(model.getStrDate());
                tv_event_time.setText(String.format("%s to %s", model.getStrStartTime(), model.getStrEndTime()));


                if (AppConstants.belowMonthEventTextColor != -1) {
                    tv_event_name.setTextColor(AppConstants.belowMonthEventTextColor);
                    tv_event_date.setTextColor(AppConstants.belowMonthEventTextColor);
                    tv_event_time.setTextColor(AppConstants.belowMonthEventTextColor);
                }

                if (!AppConstants.strBelowMonthEventTextColor.equals("null")) {
                    tv_event_name.setTextColor(Color.parseColor(AppConstants.strBelowMonthEventTextColor));
                    tv_event_date.setTextColor(Color.parseColor(AppConstants.strBelowMonthEventTextColor));
                    tv_event_time.setTextColor(Color.parseColor(AppConstants.strBelowMonthEventTextColor));
                }

                if (AppConstants.belowMonthEventDividerColor != -1) {
                    v_divider.setBackgroundColor(AppConstants.belowMonthEventDividerColor);
                }

                if (!AppConstants.strBelowMonthEventDividerColor.equals("null")) {
                    v_divider.setBackgroundColor(Color.parseColor(AppConstants.strBelowMonthEventDividerColor));
                }
            }


        }
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View v = LayoutInflater.from(context).inflate(com.desai.vatsal.mydynamiccalendar.R.layout.row_event, parent, false);
        return new EventViewHolder(v);
    }

    @Override
    public int getItemCount() {
        return eventModelList.size();
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, final int position)  {
        final EventModel eventModel = eventModelList.get(position);

        final EventViewHolder showEventsViewHolder = (EventViewHolder) holder;
        showEventsViewHolder.setEvent(eventModel);


        holder.itemView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

           /* Intent d=new Intent(context,Main2Activity.class);
            context.startActivity(d);*/
          String name= eventModelList.get(position).getStrName();

                String title,imageURL="",Name = null;
                int title_int=99;
                if(name!=null){
                    String name2  = name;
                    int position2=name2.indexOf("@");
                    int position=name2.indexOf("/");
                    int position3=name2.indexOf("#");
                    imageURL=name.substring(position+1,position2);
                    //Id=Name1.substring(position3+1);
                    title=name.substring(position2+1,position3);
                    title_int=Integer.parseInt(title);
                    Name=name.substring(0,position);

                   }
                if(title_int==6){
                Intent i = new Intent(context, ImageView_for_Feed.class);
                i.putExtra("imageURL", imageURL);
                i.putExtra("title",Name);
                i.putExtra("date",eventModelList.get(position).getStrDate());
                i.putExtra("description", "");
               // showEventsViewHolder.tv_event_name.setText(eventModelList.get(position).getStrName());
                context.startActivity(i);
                }
                else {
                    Intent i = new Intent(context,book_appointment.class);
                    i.putExtra("startTime", eventModelList.get(position).getStrStartTime());
                    i.putExtra("endTime",eventModelList.get(position).getStrEndTime() );
                    i.putExtra("Id","");
                    i.putExtra("Name", eventModelList.get(position).getStrName());
                    // showEventsViewHolder.tv_event_name.setText(eventModelList.get(position).getStrName());
                    i.putExtra("Date", eventModelList.get(position).getStrDate());

                    context.startActivity(i);

                }


                //list.notify();
          /*String name= list.get(position).getStrName();
                Toast.makeText(context,name,Toast.LENGTH_SHORT).show();*/

            }
        });
    }

}

