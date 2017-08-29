package com.eurovisionedusolutions.android.rackup;

import android.app.DatePickerDialog;
import android.content.Context;
import android.content.res.TypedArray;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.AttributeSet;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.DatePicker;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.desai.vatsal.mydynamiccalendar.AppConstants;
import com.desai.vatsal.mydynamiccalendar.DateListAdapter;
import com.desai.vatsal.mydynamiccalendar.DateModel;
import com.desai.vatsal.mydynamiccalendar.EventListAdapter;
import com.desai.vatsal.mydynamiccalendar.EventModel;
import com.desai.vatsal.mydynamiccalendar.GetEventListListener;
import com.desai.vatsal.mydynamiccalendar.HourListAdapter;
import com.desai.vatsal.mydynamiccalendar.MyDynamicCalendar;
import com.desai.vatsal.mydynamiccalendar.OnDateClickListener;
import com.desai.vatsal.mydynamiccalendar.OnEventClickListener;
import com.desai.vatsal.mydynamiccalendar.OnMonthBellowEventsDateClickListener;
import com.desai.vatsal.mydynamiccalendar.OnWeekDayViewClickListener;
import com.desai.vatsal.mydynamiccalendar.ShowDayViewEventsListAdapter;
import com.desai.vatsal.mydynamiccalendar.ShowEventsModel;
import com.desai.vatsal.mydynamiccalendar.ShowWeekViewEventsListAdapter;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;

import android.content.Context;
import android.content.res.TypedArray;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.text.TextUtils;
import android.util.AttributeSet;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.desai.vatsal.mydynamiccalendar.AppConstants;
import com.desai.vatsal.mydynamiccalendar.DateListAdapter;
import com.desai.vatsal.mydynamiccalendar.DateModel;
import com.desai.vatsal.mydynamiccalendar.EventListAdapter;
import com.desai.vatsal.mydynamiccalendar.EventModel;
import com.desai.vatsal.mydynamiccalendar.GetEventListListener;
import com.desai.vatsal.mydynamiccalendar.GlobalMethods;
import com.desai.vatsal.mydynamiccalendar.HourListAdapter;
import com.desai.vatsal.mydynamiccalendar.MyDynamicCalendar;
import com.desai.vatsal.mydynamiccalendar.OnDateClickListener;
import com.desai.vatsal.mydynamiccalendar.OnEventClickListener;
import com.desai.vatsal.mydynamiccalendar.OnMonthBellowEventsDateClickListener;
import com.desai.vatsal.mydynamiccalendar.OnWeekDayViewClickListener;
import com.desai.vatsal.mydynamiccalendar.ShowDayViewEventsListAdapter;
import com.desai.vatsal.mydynamiccalendar.ShowEventsModel;
import com.desai.vatsal.mydynamiccalendar.ShowWeekViewEventsListAdapter;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

/**
 * Created by sushant on 5/15/2017.
 */

public class MyDynamicCalendar_Extended extends MyDynamicCalendar {
    private Context context;
    private AttributeSet attrs;
    private View rootView;

    private RecyclerView recyclerView_dates, recyclerView_hours, recyclerView_show_events, recyclerView_month_view_below_events;
    private TextView tv_month_year, tv_mon, tv_tue, tv_wed, tv_thu, tv_fri, tv_sat, tv_sun;
    private ImageView iv_previous, iv_next;
    private LinearLayout parentLayout, ll_upper_part, ll_lower_part, ll_blank_space, ll_header_views, ll_week_day_layout, ll_month_view_below_events, ll_hours;

    private OnDateClickListener onDateClickListener;
    private OnEventClickListener onEventClickListener;
    private OnWeekDayViewClickListener onWeekDayViewClickListener;
    private GetEventListListener getEventListListener;

    private ArrayList<DateModel> dateModelList;
    private DateListAdapterExtend dateListAdapter;
    private ArrayList<EventModel> eventModelList;
    private EventListAdapter_extended eventListAdapter;
    private ArrayList<String> hourList;
    private ArrayList<ShowEventsModel> showEventsModelList;
    private ShowWeekViewEventsListAdapter showWeekViewEventsListAdapter;
    private ShowDayViewEventsListAdapter showDayViewEventsListAdapter;
    private HourListAdapter hourListAdapter;
    private Calendar myCalendar1;
    private SimpleDateFormat sdfMonthYear = new SimpleDateFormat("MMM - yyyy");
    private SimpleDateFormat sdfWeekDay = new SimpleDateFormat("dd MMM");
    private SimpleDateFormat sdfDayMonthYear = new SimpleDateFormat("EEEE,  dd - MMM - yyyy");

    private Calendar calendar = Calendar.getInstance();

    public MyDynamicCalendar_Extended(Context context) {
        super(context);

        this.context = context;

        if (!isInEditMode()) {
            init();
        }
    }

    public MyDynamicCalendar_Extended(Context context, AttributeSet attrs) {
        super(context, attrs);

        this.context = context;
        this.attrs = attrs;

        if (!isInEditMode()) {
            init();
        }
    }
    private void init() {

        TypedArray a = context.getTheme().obtainStyledAttributes(attrs, com.desai.vatsal.mydynamiccalendar.R.styleable.MyDynamicCalendar, 0, 0);

        try {
//            strHeaderBackgroundColor = a.getString(R.styleable.MyCalendar_headerBackgroundColor);
//            strHeaderTextColor = a.getString(R.styleable.MyCalendar_headerTextColor);
        } finally {
            a.recycle();
        }

        LayoutInflater inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        rootView = inflater.inflate(com.desai.vatsal.mydynamiccalendar.R.layout.my_dynamic_calendar, this, true);

        recyclerView_dates = (RecyclerView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.recyclerView_dates);
        recyclerView_hours = (RecyclerView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.recyclerView_hours);
        recyclerView_show_events = (RecyclerView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.recyclerView_show_events);
        recyclerView_month_view_below_events = (RecyclerView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.recyclerView_month_view_below_events);
        iv_previous = (ImageView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.iv_previous);
        iv_next = (ImageView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.iv_next);
        parentLayout = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.parentLayout);
        ll_upper_part = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_upper_part);
        ll_lower_part = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_lower_part);
        ll_blank_space = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_blank_space);
        ll_header_views = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_header_views);
        ll_week_day_layout = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_week_day_layout);
        ll_month_view_below_events = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_month_view_below_events);
        ll_hours = (LinearLayout) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.ll_hours);
        tv_month_year = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_month_year);
        tv_mon = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_mon);
        tv_tue = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_tue);
        tv_wed = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_wed);
        tv_thu = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_thu);
        tv_fri = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_fri);
        tv_sat = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_sat);
        tv_sun = (TextView) rootView.findViewById(com.desai.vatsal.mydynamiccalendar.R.id.tv_sun);


//        ll_header_views.setBackgroundColor(Color.parseColor(strHeaderBackgroundColor));
//        tv_month_year.setTextColor(Color.parseColor(strHeaderTextColor));


        actionListeners();
    }

    private void actionListeners() {

        iv_next.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (AppConstants.isShowMonth) {
                    // setMonthView("add");
                } else if (AppConstants.isShowMonthWithBellowEvents) {
                    setMonthViewWithBelowEvents("add");
                } else if (AppConstants.isShowWeek) {
                    // setWeekView("add");
                } else if (AppConstants.isShowDay) {
                    // setDayView("add");
                } else if (AppConstants.isAgenda) {
                    // setAgendaView("add");
                }

            }
        });

        iv_previous.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (AppConstants.isShowMonth) {
                    //setMonthView("sub");
                } else if (AppConstants.isShowMonthWithBellowEvents) {
                    setMonthViewWithBelowEvents("sub");
                } else if (AppConstants.isShowWeek) {
                    // setWeekView("sub");
                } else if (AppConstants.isShowDay) {
                    // setDayView("sub");
                } else if (AppConstants.isAgenda) {
                    // setAgendaView("sub");
                }

            }
        });


    }


    @Override
    public void showMonthViewWithBelowEvents() {
        setMonthViewWithBelowEvents("");

    }
    private void setMonthViewWithBelowEvents(String sign) {
        dateModelList = new ArrayList<>();
        dateListAdapter = new DateListAdapterExtend(context, dateModelList);

        GridLayoutManager gridLayoutManager = new GridLayoutManager(context, 7);
        recyclerView_dates.setLayoutManager(gridLayoutManager);

        recyclerView_dates.setAdapter(dateListAdapter);


        dateListAdapter.setOnDateClickListener(new OnDateClickListener() {
            @Override
            public void onClick(Date date) {
                ll_month_view_below_events.setVisibility(View.VISIBLE);
                if (onDateClickListener != null) {
                    onDateClickListener.onClick(date);
                }
            }

            @Override
            public void onLongClick(Date date) {
                if (onDateClickListener != null) {
                    onDateClickListener.onLongClick(date);
                }
            }
        });
        if(AppConstants.isShowMonthWithBellowEvents)
            ll_month_view_below_events.setVisibility(View.VISIBLE);
        else
            ll_month_view_below_events.setVisibility(View.GONE);

        AppConstants.isShowMonth = false;
        AppConstants.isShowMonthWithBellowEvents = true;
        AppConstants.isShowWeek = false;
        AppConstants.isShowDay = false;
        AppConstants.isAgenda = false;

        ll_upper_part.setVisibility(View.VISIBLE);
//        ll_month_view_below_events.setVisibility(View.VISIBLE);
        ll_lower_part.setVisibility(View.GONE);
        ll_blank_space.setVisibility(View.GONE);
        ll_hours.setVisibility(View.GONE);

        if (sign.equals("add")) {
            AppConstants.main_calendar.set(Calendar.MONTH, (AppConstants.main_calendar.get(Calendar.MONTH) + 1));
        } else if (sign.equals("sub")) {
            AppConstants.main_calendar.set(Calendar.MONTH, (AppConstants.main_calendar.get(Calendar.MONTH) - 1));
        }

        tv_month_year.setText(sdfMonthYear.format(AppConstants.main_calendar.getTime()));

        // set date start of month
        calendar.setTime(AppConstants.main_calendar.getTime());
        calendar.set((Calendar.DAY_OF_MONTH), 1);

        int monthBeginningCell = calendar.get(Calendar.DAY_OF_WEEK) - 2;

        if (monthBeginningCell == -1) {
            calendar.add(Calendar.DAY_OF_MONTH, -6);
        } else if (monthBeginningCell == 0) {
            calendar.add(Calendar.DAY_OF_MONTH, -7);
        } else {
            calendar.add(Calendar.DAY_OF_MONTH, -monthBeginningCell);
        }

        dateModelList.clear();

        while (dateModelList.size() < 42) {
            DateModel model = new DateModel();
            model.setDates(calendar.getTime());
            model.setFlag("month");
            dateModelList.add(model);

            calendar.add(Calendar.DAY_OF_MONTH, 1);
        }

        dateListAdapter.notifyDataSetChanged();

        dateListAdapter.setOnMonthBellowEventsClick(new OnMonthBellowEventsDateClickListener() {
            @Override
            public void onClick(Date date) {
                eventModelList = new ArrayList<>();
                eventListAdapter = new EventListAdapter_extended(context, eventModelList, "month");

                LinearLayoutManager layoutManagerForShowEventList = new LinearLayoutManager(context);
                recyclerView_month_view_below_events.setLayoutManager(layoutManagerForShowEventList);

                recyclerView_month_view_below_events.setAdapter(eventListAdapter);

                for (int i = 0; i < AppConstants.eventList.size(); i++) {
                    if (AppConstants.eventList.get(i).getStrDate().equals(AppConstants.sdfDate.format(date))) {
                        eventModelList.add(new EventModel(AppConstants.eventList.get(i).getStrDate(), AppConstants.eventList.get(i).getStrStartTime(), AppConstants.eventList.get(i).getStrEndTime(), AppConstants.eventList.get(i).getStrName()));
                    }
                }

                eventListAdapter.notifyDataSetChanged();
            }
        });
    }
    @Override
    public void showAgendaView() {
        setAgendaView("");
    }
    private void setAgendaView(String sign) {
        dateModelList = new ArrayList<>();
        dateListAdapter = new DateListAdapterExtend(context, dateModelList);

        GridLayoutManager gridLayoutManager = new GridLayoutManager(context, 7);
        recyclerView_dates.setLayoutManager(gridLayoutManager);

        recyclerView_dates.setAdapter(dateListAdapter);

        dateListAdapter.setOnDateClickListener(new OnDateClickListener() {
            @Override
            public void onClick(Date date) {
                if (onDateClickListener != null) {
                    onDateClickListener.onClick(date);
                }
            }

            @Override
            public void onLongClick(Date date) {
                if (onDateClickListener != null) {
                    onDateClickListener.onLongClick(date);
                }
            }
        });

        AppConstants.isShowMonth = false;
        AppConstants.isShowMonthWithBellowEvents = false;
        AppConstants.isShowWeek = false;
        AppConstants.isShowDay = false;
        AppConstants.isAgenda = true;

        ll_upper_part.setVisibility(View.VISIBLE);
        ll_month_view_below_events.setVisibility(View.GONE);
        ll_lower_part.setVisibility(View.VISIBLE);
        ll_blank_space.setVisibility(View.GONE);
        ll_hours.setVisibility(View.GONE);


        if (sign.equals("add")) {
            AppConstants.main_calendar.set(Calendar.DAY_OF_MONTH, (AppConstants.main_calendar.get(Calendar.DAY_OF_MONTH) + 7));
        } else if (sign.equals("sub")) {
            AppConstants.main_calendar.set(Calendar.DAY_OF_MONTH, (AppConstants.main_calendar.get(Calendar.DAY_OF_MONTH) - 7));
        }


        // set date start of month
        calendar.setTime(AppConstants.main_calendar.getTime());

        int monthBeginningCell = calendar.get(Calendar.DAY_OF_WEEK) - 2;
        calendar.add(Calendar.DAY_OF_MONTH, -monthBeginningCell);

        String weekStartDay = sdfWeekDay.format(calendar.getTime());

        dateModelList.clear();

        while (dateModelList.size() < 7) {
            DateModel model = new DateModel();
            model.setDates(calendar.getTime());
            model.setFlag("week");
            dateModelList.add(model);

            calendar.add(Calendar.DAY_OF_MONTH, 1);
        }

        dateListAdapter.notifyDataSetChanged();

        calendar.add(Calendar.DAY_OF_MONTH, -1);

        String weekEndDay = sdfWeekDay.format(calendar.getTime());

        tv_month_year.setText(String.format("%s - %s", weekStartDay, weekEndDay));


        recyclerView_show_events.setVisibility(GONE);

        dateListAdapter.setOnMonthBellowEventsClick(new OnMonthBellowEventsDateClickListener() {
            @Override
            public void onClick(Date date) {


                recyclerView_show_events.setVisibility(VISIBLE);

                eventModelList = new ArrayList<EventModel>();
                eventListAdapter = new EventListAdapter_extended(context, eventModelList, "month");

                LinearLayoutManager linearLayoutManager = new LinearLayoutManager(context);
                recyclerView_show_events.setLayoutManager(linearLayoutManager);

                recyclerView_show_events.setAdapter(eventListAdapter);

                for (int i = 0; i < AppConstants.eventList.size(); i++) {
                    if (AppConstants.eventList.get(i).getStrDate().equals(AppConstants.sdfDate.format(date))) {
                        eventModelList.add(new EventModel(AppConstants.eventList.get(i).getStrDate(), AppConstants.eventList.get(i).getStrStartTime(), AppConstants.eventList.get(i).getStrEndTime(), AppConstants.eventList.get(i).getStrName()));
                    }
                }

                eventListAdapter.notifyDataSetChanged();
            }
        });

    }






    @Override
    public void goToCurrentDate() {

        myCalendar1=Calendar.getInstance();
        final DatePickerDialog.OnDateSetListener date = new DatePickerDialog.OnDateSetListener() {

            @Override
            public void onDateSet(DatePicker view, int year, int monthOfYear,
                                  int dayOfMonth) {
                // TODO Auto-generated method stub
                myCalendar1.set(Calendar.YEAR, year);
                myCalendar1.set(Calendar.MONTH, monthOfYear);
                myCalendar1.set(Calendar.DAY_OF_MONTH, dayOfMonth);
                updateLabel();
            }

        };

        DatePickerDialog dialog=new DatePickerDialog(getContext(),date,myCalendar1
                .get(Calendar.YEAR), myCalendar1.get(Calendar.MONTH),myCalendar1.get(Calendar.DAY_OF_MONTH));
        Calendar l= Calendar.getInstance();
        Calendar l2=(Calendar) l.clone();
        l.add(Calendar.MONTH,6);
        l2.add(Calendar.MONTH,-1);
        dialog.getDatePicker().setMaxDate(l.getTimeInMillis());
        dialog.getDatePicker().setMinDate(l2.getTimeInMillis());
        dialog.show();

    }

    private void updateLabel() {

        String myFormat = "dd/MM/yyyy"; //In which you need put here
        SimpleDateFormat sdf = new SimpleDateFormat(myFormat, Locale.US);

        AppConstants.main_calendar=myCalendar1;
        if (AppConstants.isShowMonthWithBellowEvents) {
            setMonthViewWithBelowEvents("");
        } else if (AppConstants.isAgenda) {
            setAgendaView("");
        }
        else {
        showMonthViewWithBelowEvents();}

        //dateView.setText(sdf.format(myCalendar.getTime()));
    }
}
