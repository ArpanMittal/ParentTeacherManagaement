package com.eurovisionedusolutions.android.rackup;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.BottomSheetBehavior;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.google.android.gms.vision.text.Text;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;


public class MyAdapter extends RecyclerView.Adapter<MyAdapter.ViewHolder>  {
    private Context context;
    private Context context1;
    public MyAdapter(Context context, ArrayList<EventModel_Feed> items){
        this.context=context;
        this.items = items;
        context1=context;
    }
    private final ArrayList<EventModel_Feed> items;

    public MyAdapter(final ArrayList<EventModel_Feed> items) {
        this.items = items;
    }

    @Override
    public ViewHolder onCreateViewHolder(final ViewGroup parent, final int viewType) {
        final Context context = parent.getContext();
        context1=context;
        final View view = LayoutInflater.from(context).inflate(R.layout.item_main, parent, false);
        final ViewHolder viewHolder = new ViewHolder(view);
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {
        // final Context context = parent.getContext();
        final String itemText = items.get(position).getTitle();
        final String date=items.get(position).getTime();
        final String description=items.get(position).getDescription();
        Picasso.with(context1).load("http://web.rackupcambridge.com"+items.get(position).getImage_url()).into(holder.imageView);
        //Picasso.with(context1).load("http://web.rackupcambridge.com/storage/1/50_republic day 2.jpg").into(holder.imageView);
        holder.tvItem.setText(itemText);
        holder.dateView.setText(date);
        holder.description.setText(description);

        if(items.get(position).getActivities() != null){
            holder.moreDetails.setVisibility(View.VISIBLE);
        }

        holder.moreDetails.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(holder.bottomSheetBehavior.getState() == BottomSheetBehavior.STATE_EXPANDED){
                    holder. bottomSheetBehavior.setState(BottomSheetBehavior.STATE_COLLAPSED);
                    holder.imageView.setVisibility(View.VISIBLE);
                    holder.main_layout.setVisibility(View.INVISIBLE);
                }else{

                    holder. bottomSheetBehavior.setState(BottomSheetBehavior.STATE_EXPANDED);
                    holder.imageView.setVisibility(View.GONE);
                    holder.main_layout.setVisibility(View.VISIBLE);

                }
            }
        });




        // Capturing the callbacks for bottom sheet
        holder.bottomSheetBehavior.setBottomSheetCallback(new BottomSheetBehavior.BottomSheetCallback() {
            @Override
            public void onStateChanged(View bottomSheet, int newState) {

//                if (newState == BottomSheetBehavior.STATE_EXPANDED) {
//                    holder.bottomSheetHeading.setText(getString(R.string.text_collapse_me));
//                } else {
//                    holder.bottomSheetHeading.setText(getString(R.string.text_expand_me));
//                }

                // Check Logs to see how bottom sheets behaves
                switch (newState) {
                    case BottomSheetBehavior.STATE_COLLAPSED:
                        Log.e("Bottom Sheet Behaviour", "STATE_COLLAPSED");
                        break;
                    case BottomSheetBehavior.STATE_DRAGGING:
                        Log.e("Bottom Sheet Behaviour", "STATE_DRAGGING");
                        break;
                    case BottomSheetBehavior.STATE_EXPANDED:
                        Log.e("Bottom Sheet Behaviour", "STATE_EXPANDED");
                        break;
                    case BottomSheetBehavior.STATE_HIDDEN:
                        Log.e("Bottom Sheet Behaviour", "STATE_HIDDEN");
                        break;
                    case BottomSheetBehavior.STATE_SETTLING:
                        Log.e("Bottom Sheet Behaviour", "STATE_SETTLING");
                        break;
                }
            }


            @Override
            public void onSlide(View bottomSheet, float slideOffset) {

            }
        });

    /*if(position%2==1){
    holder.imageView.setImageResource(R.drawable.large_image);}
    else {holder.imageView.setImageResource(R.drawable.arduino);}*/

        holder.itemView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
//        Intent act = new Intent(MyAdapter.this.context, ImageView_for_Feed.class);
//        act.putExtra("imageURL", ((EventModel_Feed) MyAdapter.this.items.get(position)).getImage_url());
//        act.putExtra("date", ((EventModel_Feed) MyAdapter.this.items.get(position)).getTime());
//        act.putExtra("description", ((EventModel_Feed) MyAdapter.this.items.get(position)).getDescription());
//        act.putExtra("title", ((EventModel_Feed) MyAdapter.this.items.get(position)).getTitle());
//        MyAdapter.this.context.startActivity(act);
//        MyAdapter.this.context.startActivity(act);

                Intent intent = new Intent(MyAdapter.this.context,ImageFeedViewPager.class);
                Bundle bundle = new Bundle();
//        bundle.putSerializable("value", items);
                intent.putExtra(ImageFeedViewPager.IMAGEVIEWLIST, items);
                intent.putExtra(ImageFeedViewPager.CURRENTPOSITION, position);
                MyAdapter.this.context.startActivity(intent);
//        holder.tvItem.setText(itemText+","+ String.valueOf(position));
//        Intent l= new Intent(context,ImageView_for_Feed.class);
//        context.startActivity(l);

            }
        });

        if(items.get(position).getCircle_time()!= null){
            holder.circle_text.setText(items.get(position).getCircle_time());
        }else{
            holder.circle_layout.setVisibility(View.GONE);
        }

        if(items.get(position).getFirst_meal()!= null){
            holder.first_meal.setText(items.get(position).getFirst_meal());
        }else{
            holder.first_meal_layout.setVisibility(View.GONE);
        }

        if(items.get(position).getSecond_meal()!= null){
            holder.second_meal.setText(items.get(position).getSecond_meal());
        }else{
            holder.second_meal_layout.setVisibility(View.GONE);
        }

        if(items.get(position).getThired_meal()!= null){
            holder.thired_meal.setText(items.get(position).getThired_meal());
        }else{
            holder.thired_meal_layout.setVisibility(View.GONE);
        }

        if(items.get(position).getActivities()!= null){
            holder.activity.setText(items.get(position).getActivities());
        }else{
            holder.activity_layout.setVisibility(View.GONE);
        }

        if(items.get(position).getEvening_activities()!= null){
            holder.evening_activity.setText(items.get(position).getEvening_activities());
        }else{
            holder.evening_activity_layout.setVisibility(View.GONE);
        }

        if(items.get(position).getOthers()!= null){
            holder.others.setText(items.get(position).getOthers());
        }else{
            holder.other_layout.setVisibility(View.GONE);
        }



    }

    @Override
    public int getItemCount() {
        return items.size();
    }

    public ArrayList<EventModel_Feed> getItems() {
        return items;
    }



    public class ViewHolder extends RecyclerView.ViewHolder {
        protected TextView tvItem;
        private TextView dateView;
        private ImageView imageView;
        private TextView description;
        private ImageView moreDetails;
        private RelativeLayout main_layout;
        private BottomSheetBehavior bottomSheetBehavior;
        private TextView circle_text;
        private TextView first_meal;
        private TextView second_meal;
        private TextView thired_meal;
        private TextView evening_activity;
        private TextView activity;
        private TextView others;
        private LinearLayout circle_layout;
        private LinearLayout first_meal_layout;
        private LinearLayout second_meal_layout;
        private LinearLayout thired_meal_layout;
        private LinearLayout evening_activity_layout;
        private LinearLayout activity_layout;
        private LinearLayout other_layout;
        public ViewHolder(final View itemView) {
            super(itemView);
            bottomSheetBehavior = BottomSheetBehavior.from(itemView.findViewById(R.id.bottomSheetLayout));
            moreDetails = (ImageView) itemView.findViewById(R.id.more_details);
            tvItem = (TextView) itemView.findViewById(R.id.tv_item);
            imageView=(ImageView)itemView.findViewById(R.id.imageview);
            dateView=(TextView)itemView.findViewById(R.id.textview6);
            description=(TextView)itemView.findViewById(R.id.textview7);
            main_layout = (RelativeLayout) itemView.findViewById(R.id.bottomSheetLayout);
            circle_layout = (LinearLayout) itemView.findViewById(R.id.circle_time_layout);
            circle_text = (TextView)itemView.findViewById(R.id.circle_time);
            first_meal_layout = (LinearLayout) itemView.findViewById(R.id.first_meal_layout);
            first_meal = (TextView) itemView.findViewById(R.id.first_meal);
            second_meal = (TextView) itemView.findViewById(R.id.second_meal);
            second_meal_layout = (LinearLayout) itemView.findViewById(R.id.second_meal_layout);
            thired_meal = (TextView) itemView.findViewById(R.id.third_meal);
            thired_meal_layout = (LinearLayout) itemView.findViewById(R.id.third_meal_layout);
            activity = (TextView) itemView.findViewById(R.id.activities);
            activity_layout =(LinearLayout) itemView.findViewById(R.id.activities_layout);
            evening_activity = (TextView) itemView.findViewById(R.id.evening_activities);
            evening_activity_layout = (LinearLayout) itemView.findViewById(R.id.evening_activities_layout);
            others = (TextView) itemView.findViewById(R.id.others);
            other_layout = (LinearLayout)itemView.findViewById(R.id.others_layout);
        }
    }
}
