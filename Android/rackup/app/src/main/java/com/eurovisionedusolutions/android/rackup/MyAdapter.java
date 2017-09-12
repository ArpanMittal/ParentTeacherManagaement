package com.eurovisionedusolutions.android.rackup;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
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
    public ViewHolder(final View itemView) {
      super(itemView);
      tvItem = (TextView) itemView.findViewById(R.id.tv_item);
      imageView=(ImageView)itemView.findViewById(R.id.imageview);
      dateView=(TextView)itemView.findViewById(R.id.textview6);
      description=(TextView)itemView.findViewById(R.id.textview7);
    }
  }
}
