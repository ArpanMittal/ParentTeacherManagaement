package com.eurovisionedusolutions.android.rackup;

import java.io.Serializable;

public class EventModel_Feed implements Serializable {
    private String description;
    private String id;
    private String type;
    private String image_url;
    private String time;
    private String title;
    private String date;
    private String circle_time;
    private String first_meal;
    private String second_meal;
    private String thired_meal;
    private String others;
    private String activities;
    private String evening_activities;

    public EventModel_Feed(String title, String time, String description, String image_url, String id, String circle_time, String first_meal, String second_meal, String thired_meal, String others, String activities, String evening_activities) {
        this.title = title;
        this.time = time;
        this.description = description;
        this.image_url = image_url;
        this.id = id;
        this.circle_time = circle_time;
        this.first_meal =first_meal;
        this.second_meal = second_meal;
        this.thired_meal = thired_meal;
        this.others = others;
        this.activities = activities;
        this.evening_activities = evening_activities;
    }

    public EventModel_Feed(String title, String time, String description, String image_url, String id) {
        this.title = title;
        this.time = time;
        this.description = description;
        this.image_url = image_url;
        this.id = id;
    }

    public String getCircle_time() {
        return circle_time;
    }

    public String getFirst_meal() {
        return first_meal;
    }

    public String getSecond_meal() {
        return second_meal;
    }

    public String getThired_meal() {
        return thired_meal;
    }

    public String getOthers() {
        return others;
    }

    public String getActivities() {
        return activities;
    }

    public String getEvening_activities() {
        return evening_activities;
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
