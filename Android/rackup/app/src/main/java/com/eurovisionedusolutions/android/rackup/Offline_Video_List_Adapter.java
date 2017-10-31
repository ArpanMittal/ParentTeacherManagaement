package com.eurovisionedusolutions.android.rackup;

import android.app.Activity;
import android.content.Context;
import android.database.Cursor;
import android.net.Uri;
import android.provider.MediaStore;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.NotificationCompat;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.ListAdapter;
import android.widget.TextView;

import com.squareup.picasso.Picasso;

import java.io.File;

import static android.R.attr.thumb;
import static android.R.attr.value;
import static com.eurovisionedusolutions.android.rackup.R.id.imageView;
import static com.eurovisionedusolutions.android.rackup.R.id.txtSize;

/**
 * Created by arpan on 9/11/2017.
 */

public class Offline_Video_List_Adapter extends BaseAdapter {

    private Context mContext;
    private Cursor videoCursor;
    String thumbPath;
    private int videoColumnIndex;

    String[] thumbColumns = { MediaStore.Video.Thumbnails.DATA,MediaStore.Video.Thumbnails.VIDEO_ID };
    public Offline_Video_List_Adapter(Context context, Cursor cursor) {
        mContext = context;
        videoCursor = cursor;
    }

        public int getCount()
        {
            return videoCursor.getCount();
        }

        public Object getItem(int position)
        {
            return position;
        }

        public long getItemId(int position)
        {
            return position;
        }

        public View getView(int position, View convertView, ViewGroup parent)
        {
            View listItemRow = null;
            Activity activity = (Activity)mContext;
            listItemRow = LayoutInflater.from(mContext).inflate(R.layout.listview_layout, parent, false);

            TextView txtTitle = (TextView)listItemRow.findViewById(R.id.txt);
//            TextView txtSize = (TextView)listItemRow.findViewById(R.id.txtSize);
            ImageView thumbImage = (ImageView)listItemRow.findViewById(R.id.flag);

            videoColumnIndex = videoCursor.getColumnIndexOrThrow(MediaStore.Video.Media.DISPLAY_NAME);
            videoCursor.moveToPosition(position);
            txtTitle.setText(videoCursor.getString(videoColumnIndex));

            videoColumnIndex = videoCursor.getColumnIndexOrThrow(MediaStore.Video.Media.SIZE);
            videoCursor.moveToPosition(position);
//            txtSize.setText(" Size(KB):" + videoCursor.getString(videoColumnIndex));

            int videoId = videoCursor.getInt(videoCursor.getColumnIndexOrThrow(MediaStore.Video.Media._ID));
            Cursor videoThumbnailCursor =activity.managedQuery(MediaStore.Video.Thumbnails.EXTERNAL_CONTENT_URI,
                    thumbColumns, MediaStore.Video.Thumbnails.VIDEO_ID+ "=" + videoId, null, null);

            if (videoThumbnailCursor.moveToFirst())
            {
                thumbPath = videoThumbnailCursor.getString(videoThumbnailCursor.getColumnIndex(MediaStore.Video.Thumbnails.DATA));
                Log.i("ThumbPath: ",Uri.parse(thumbPath).toString());

            }
//            thumbImage.setImageURI(Uri.parse(thumbPath));
            File f = new File(thumbPath);
            Picasso.with(mContext).load(f).placeholder((int) R.drawable.loading_thumbnail).error((int) R.drawable.no_thumbnail).resize(480, 360).rotate(0.0f).into(thumbImage);


            return listItemRow;

        }



    }


