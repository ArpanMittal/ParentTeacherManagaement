package com.eurovisionedusolutions.android.rackup;

import java.io.Serializable;
import java.util.List;

import static android.R.attr.id;

/**
 * Created by arpan on 10/26/2017.
 */

public class Custom_Location implements Serializable {

    private String id ;
    private List<String> custom_lists;

    public String getId() {
        return id;
    }

    public List<String> getCustom_lists() {
        return custom_lists;
    }

    public Custom_Location(String id, List<String> custom_lists) {

        this.id = id;
        this.custom_lists = custom_lists;
    }
}
