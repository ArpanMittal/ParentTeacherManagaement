package com.eurovisionedusolutions.android.rackup;

public class EventModel_Feed {
    private String description;
    private String id;
    private String type;
    private String image_url;
    private String time;
    private String title;
    private String date;

    public EventModel_Feed(String title, String time, String description, String image_url, String id) {
        this.title = title;
        this.time = time;
        this.description = description;
        this.image_url = image_url;
        this.id = id;
    }

    public String getTitle() {
        return this.title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getTime() {
        return this.time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getDescription() {
        return this.description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getImage_url() {
        return this.image_url;
    }

    public void setImage_url(String image_url) {
        this.image_url = image_url;
    }

    public String getId() {
        return this.id;
    }

    public void setId(String id) {
        this.id = id;
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
}
