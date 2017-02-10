package com.eurovisionedusolutions.android.rackup;

/**
 * Created by sushant on 2/9/2017.
 */

import android.content.Context;
import android.content.ContextWrapper;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;



import android.content.Context;
import android.content.ContextWrapper;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;

import com.eurovisionedusolutions.android.rackup.LogHelper;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;

/**
 * Created by punit on 19-01-2017.
 */

public class Image {
    private Context context;
    private Bitmap image;
    private File directory;
    private String name;

    public Image(Context context, Bitmap image, String name) {
        this.context = context;
        this.image = image;
        this.name = name;
        ContextWrapper cw = new ContextWrapper(context.getApplicationContext());
        // path to /data/data/myrapp/app_data/image
        this.directory = cw.getDir("image", Context.MODE_PRIVATE);
    }

    public Image(Context context, String name) throws FileNotFoundException {
        this.context = context;
        this.name = name;

        ContextWrapper cw = new ContextWrapper(context.getApplicationContext());
        this.directory = cw.getDir("image", Context.MODE_PRIVATE);

        File f=new File(this.directory, name);
        this.image = BitmapFactory.decodeStream(new FileInputStream(f));
        save();
    }

    public void save() {
        File mypath=new File(directory,name);
        try {
            FileOutputStream fos = new FileOutputStream(mypath);
            // Use the compress method on the BitMap object to write image to the OutputStream
            image.compress(Bitmap.CompressFormat.PNG, 100, fos);
            fos.close();
        } catch (Exception e) {
            new LogHelper(e);
        }
    }

    public void delete() {
        try {
            File file = new File(directory, name);
            file.delete();
        }catch (Exception e){
            new LogHelper(e);
        }
    }

    public Bitmap getImage(){
        return this.image;
    }

    public void setImage(Bitmap image){
        this.image = image;
    }
}
