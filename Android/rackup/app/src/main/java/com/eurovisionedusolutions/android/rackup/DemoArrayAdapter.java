package com.eurovisionedusolutions.android.rackup;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.eurovisionedusolutions.android.rackup.DemoListViewItem;
import com.eurovisionedusolutions.android.rackup.R;
import java.util.List;

public final class DemoArrayAdapter extends ArrayAdapter<DemoListViewItem> {
    private final LayoutInflater inflater;

    public DemoArrayAdapter(Context context, int textViewResourceId, List<DemoListViewItem> objects) {
        super(context, textViewResourceId, objects);
        this.inflater = (LayoutInflater) context.getSystemService("layout_inflater");
    }

    public View getView(int position, View view, ViewGroup parent) {
        if (view == null) {
            view = this.inflater.inflate(R.layout.list_item, null);
        }
        TextView textView = (TextView) view.findViewById(R.id.list_item_text);
        textView.setText(((DemoListViewItem) getItem(position)).getTitle());
        TextView disabledText = (TextView) view.findViewById(R.id.list_item_disabled_text);
        disabledText.setText(((DemoListViewItem) getItem(position)).getDisabledText());
        if (isEnabled(position)) {
            disabledText.setVisibility(4);
            textView.setTextColor(-1);
        } else {
            disabledText.setVisibility(0);
            textView.setTextColor(-7829368);
        }
        return view;
    }

    public boolean areAllItemsEnabled() {
        return true;
    }

    public boolean isEnabled(int position) {
        return ((DemoListViewItem) getItem(position)).isEnabled();
    }

    public boolean anyDisabled() {
        for (int i = 0; i < getCount(); i++) {
            if (!isEnabled(i)) {
                return true;
            }
        }
        return false;
    }
}
