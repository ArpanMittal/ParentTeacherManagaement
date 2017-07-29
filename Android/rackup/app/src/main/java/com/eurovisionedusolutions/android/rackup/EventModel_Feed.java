package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 5/22/2017.
 */


/**
 * Created by HCL on 02-10-2016.
 */
public class EventModel_Feed {

    private String type;
    private String title;
    private String time;
    private String description;
    private String image_url;
    private String id;
    private String date;


    public EventModel_Feed(String title, String time, String description,String image_url,String id,String type,String date) {
        this.title=title;
        this.time=time;
        this.description=description;
        this.image_url=image_url;
        this.id=id;
        this.type=type;
        this.date=date;
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

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getDescription() {
        return description;
    }
    public void setDescription(String description) {
        this.description = description;
    }

    public String getImage_url() {
        return image_url;
    }

    public void setImage_url(String image_url) {
        this.image_url = image_url;
    }
    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id=id;
    }
    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type=type;
    }
    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date=date;
    }

/*    public void getVisibleItemPosition(){this.visibleitemposition=visibleitemposition;}
    public int setVisibleitemposition(int visibleitemposition){return visibleitemposition;}*/


}

