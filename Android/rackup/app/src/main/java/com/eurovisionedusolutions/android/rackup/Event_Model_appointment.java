package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 5/25/2017.
 */



/**
 * Created by sushant on 5/22/2017.
 */


/**
 * Created by HCL on 02-10-2016.
 */
public class Event_Model_appointment{

    private String title;
    private String eventId;
    private String startTime;
    private String endTime;
    private String date;
    private String status;
    private String teacherName;
    private String teacherContact;
    private String requestby;
    private String reason;


    public Event_Model_appointment(String title,String eventId ,String startTime, String endTime,String date,String status,String teacherName
                                 , String teacherContact, String requestby, String reason) {
        this.title=title;
        this.eventId=eventId;
        this.startTime=startTime;
        this.endTime=endTime;
        this.date=date;
        this.status=status;
        this.teacherName=teacherName;
        this.teacherContact=teacherContact;
        this.requestby=requestby;
        this.reason=reason;


    }

  /*  public EventModel(String strDate, String strStartTime, String strEndTime, String strName, int image) {
        this.strDate = strDate;
        this.strStartTime = strStartTime;
        this.strEndTime = strEndTime;
        this.strName = strName;
        this.image = image;
    }*/

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getStartTime() {
        return startTime;
    }

    public void setStartTime(String startTime) {
        this.startTime=startTime;
    }

    public String getEndTime() {
        return endTime;
    }
    public void setEndTime(String endTime) {
        this.endTime=endTime;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date=date;
    }

    public String getStatus() {
        return status;
    }
    public void setStatus(String status) {
        this.status=status;
    }

    public String getTeacherName() {
        return teacherName;
    }
    public void setTeacherName(String teacherName) {
        this.teacherName=teacherName;
    }
    public String getTeacherContact() {
        return teacherContact;
    }
    public void setTeacherContact(String teacherContact) {
        this.teacherContact=teacherContact;
    }
    public String getRequestby() {
        return requestby;
    }

    public void setRequestby(String requestby) {
        this.requestby=requestby;
    }
    public String getReason() {
        return reason;
    }
    public void setReason(String reason) {
        this.reason=reason;
    }





/*    public void getVisibleItemPosition(){this.visibleitemposition=visibleitemposition;}
    public int setVisibleitemposition(int visibleitemposition){return visibleitemposition;}*/


}

