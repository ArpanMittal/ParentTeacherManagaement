package com.eurovisionedusolutions.android.rackup;

import android.database.Cursor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v4.widget.SwipeRefreshLayout.OnRefreshListener;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.text.format.DateUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import com.eurovisionedusolutions.android.rackup.UserContract.UserDetailEntry;
import com.github.pwittchen.infinitescroll.library.InfiniteScrollListener;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import org.json.JSONArray;

public class Feed_Activity extends Fragment implements RemoteCallHandler {
    private static final int MAX_ITEMS_PER_REQUEST = 10;
    private static final int NUMBER_OF_ITEMS = 1;
    private static final int SIMULATED_LOADING_TIME_IN_MS = 1500;
    private static String prv_ID = "";
    private ArrayList<EventModel_Feed> ModelList;
    private String lastId = "";
    private LinearLayoutManager layoutManager;
    SwipeRefreshLayout mSwipeRefreshLayout;
    DBHelper mydb;
    private int page;
    private int position_to_scroll = 0;
    public ProgressBar progressBar;
    public RecyclerView recyclerView;
    private String token = "temp";
    public Toolbar toolbar;

    public static Feed_Activity newInstance() {
        return new Feed_Activity();
    }

    private static ArrayList<EventModel_Feed> createItems() {
        ArrayList<EventModel_Feed> itemsLocal1 = new ArrayList();
        for (int i = 0; i < 1; i++) {
            String prefix;
            if (i < 10) {
                prefix = "0";
            } else {
                prefix = "";
            }
            String title = "Please Refresh The layout";
            itemsLocal1.add(new EventModel_Feed(title, "HHH", "Slight Internet problem", title, "0"));
        }
        return itemsLocal1;
    }

    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        remotecall();
    }

    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.activity_feed_, container, false);
        Toolbar toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        toolbar.setTitle("ImageFeed");
        toolbar.setTitleTextColor(getResources().getColor(R.color.black));
        ((AppCompatActivity)getActivity()).setSupportActionBar(toolbar);
        this.ModelList = createItems();
        initViews(view);
        this.mSwipeRefreshLayout.setOnRefreshListener(new OnRefreshListener() {
            public void onRefresh() {
                Feed_Activity.this.ModelList.clear();
                Feed_Activity.this.position_to_scroll = 0;
                Feed_Activity.this.remotecall();
            }
        });
        initRecyclerView();
        ((AppCompatActivity) getActivity()).setSupportActionBar(this.toolbar);
        return view;
    }

    private void remotecall() {
        this.token = fetchman();
        this.lastId = "";
        new RemoteHelper(getActivity()).FeedActivity(this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, this.token, this.lastId);
    }

    private void initViews(View view) {
        this.toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        this.recyclerView = (RecyclerView) view.findViewById(R.id.recycler_view);
        this.progressBar = (ProgressBar) view.findViewById(R.id.progress_bar);
        this.mSwipeRefreshLayout = (SwipeRefreshLayout) view.findViewById(R.id.activity_main_swipe_refresh_layout);
    }

    private void initRecyclerView() {
        this.layoutManager = new LinearLayoutManager(getContext());
        this.recyclerView.setHasFixedSize(true);
        this.recyclerView.setLayoutManager(this.layoutManager);
        this.recyclerView.setAdapter(new MyAdapter(getContext(), this.ModelList));
        this.recyclerView.addOnScrollListener(createInfiniteScrollListener());
    }

    @NonNull
    private InfiniteScrollListener createInfiniteScrollListener() {
        return new InfiniteScrollListener(10, this.layoutManager) {
            public void onScrolledToEnd(int firstVisibleItemPosition) {
                Feed_Activity.this.simulateLoading();
                String ID = ((EventModel_Feed) Feed_Activity.this.ModelList.get(Feed_Activity.this.ModelList.size() - 1)).getId();
                if (ID == Feed_Activity.prv_ID) {
                    Feed_Activity.this.progressBar.setVisibility(View.GONE);
                    return;
                }
                Feed_Activity.prv_ID = ID;
                Feed_Activity.this.position_to_scroll = firstVisibleItemPosition;
                new RemoteHelper(Feed_Activity.this.getContext().getApplicationContext()).FeedActivity(Feed_Activity.this, RemoteCalls.CHECK_LOGIN_CREDENTIALS, Feed_Activity.this.token, ID);
            }
        };
    }

    @NonNull
    private ArrayList<EventModel_Feed> getItemsToBeLoaded(int start, int end) {
        String title = "kjfvn";
        ArrayList<EventModel_Feed> oldItems = ((MyAdapter) this.recyclerView.getAdapter()).getItems();
        ArrayList<EventModel_Feed> itemsLocal = new ArrayList();
        itemsLocal.addAll(oldItems);
        return itemsLocal;
    }

    private void simulateLoading() {
        new AsyncTask<Void, Void, Void>() {
            protected void onPreExecute() {
                Feed_Activity.this.progressBar.setVisibility(View.VISIBLE);
            }

            protected Void doInBackground(Void... params) {
                try {
                    Thread.sleep(1500);
                } catch (InterruptedException e) {
                    Log.e("MainActivity", e.getMessage());
                }
                return null;
            }

            protected void onPostExecute(Void param) {
                Feed_Activity.this.progressBar.setVisibility(View.GONE);
            }
        }.execute(new Void[0]);
    }

    public void HandleRemoteCall(boolean isSuccessful, RemoteCalls callFor, JSONArray response, Exception exception) {
        if (isSuccessful) {
            try {
                this.mSwipeRefreshLayout.setRefreshing(false);
                ArrayList<EventModel_Feed> itemsLocal1 = new ArrayList();
                for (int i = 0; i < response.getJSONArray(0).length(); i++) {
                    String id = response.getJSONArray(0).getJSONObject(i).getString(DBHelper.CONTACTS_COLUMN_ID);
                    String title1 = response.getJSONArray(0).getJSONObject(i).getString("title");
                    String date1 = response.getJSONArray(0).getJSONObject(i).getJSONObject("created_at").getString("date");
                    String date2 = DateUtils.getRelativeTimeSpanString(new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").parse(date1).getTime(), System.currentTimeMillis(), 86400000).toString();
                    String image_url = response.getJSONArray(0).getJSONObject(i).getString("filePath");
                    ArrayList<EventModel_Feed> arrayList = itemsLocal1;
                    arrayList.add(new EventModel_Feed(title1, date2, response.getJSONArray(0).getJSONObject(i).getString("description"), image_url, id));
                }
                ArrayList<EventModel_Feed> oldItems = ((MyAdapter) this.recyclerView.getAdapter()).getItems();
                this.ModelList.addAll(itemsLocal1);
                if (((EventModel_Feed) this.ModelList.get(0)).getTime() == "HHH") {
                    this.ModelList.remove(0);
                }
                this.recyclerView.setAdapter(new MyAdapter(getContext(), this.ModelList));
                this.recyclerView.invalidate();
                this.recyclerView.scrollToPosition(this.position_to_scroll);
                this.progressBar.setVisibility(View.GONE);
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    public String fetchman() {
        this.mydb = new DBHelper(getActivity());
        String[] mProjection = new String[]{UserDetailEntry.COLUMN_ID, UserDetailEntry.CoLUMN_TOKEN};
        String[] mSelectionArgs = new String[]{"1"};
        Cursor mCursor = getActivity().getContentResolver().query(UserContract.BASE_CONTENT_URI_Full, mProjection, "_id=?", mSelectionArgs, null);
        if (mCursor.getCount() > 0) {
            int mCursorColumnIndex_main = mCursor.getColumnIndex(UserDetailEntry.COLUMN_ID);
            int mCursorColumnIndex_token = mCursor.getColumnIndex(UserDetailEntry.CoLUMN_TOKEN);
            while (mCursor.moveToNext()) {
                this.token = mCursor.getString(mCursorColumnIndex_token);
            }
        }
        mCursor.close();
        this.mydb.close();
        return this.token;
    }
}
