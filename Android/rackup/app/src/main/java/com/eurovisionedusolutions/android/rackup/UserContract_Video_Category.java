package com.eurovisionedusolutions.android.rackup;

import android.content.ContentResolver;
import android.content.ContentUris;
import android.net.Uri;
import android.provider.BaseColumns;

/**
 * Created by Punit Chhajer on 10-08-2016.
 */
public class UserContract_Video_Category {
    // The "Content authority" is a name for the entire content provider, similar to the
    // relationship between a domain name and its website.  A convenient string to use for the
    // content authority is the package name for the app, which is guaranteed to be unique on the
    // device.
    public static final String CONTENT_AUTHORITY = "com.eurovisionedusolutions.android.rackup";

    // Use CONTENT_AUTHORITY to create the base of all URI's which apps will use to contact
    // the content provider.
    public static final Uri BASE_CONTENT_URI = Uri.parse("content://" + CONTENT_AUTHORITY);


    // Possible paths (appended to base content URI for possible URI's)
    // For instance, content://com.example.android.sunshine.app/weather/ is a valid path for
    // looking at weather data. content://com.example.android.sunshine.app/givemeroot/ will fail,
    // as the ContentProvider hasn't been given any information on what to do with "givemeroot".
    // At least, let's hope not.  Don't be that dev, reader.  Don't be that dev.
    public static final String PATH_USER_DETAIL = "VideoCategory";
    public static final Uri BASE_CONTENT_URI_Full = Uri.parse("content://" + CONTENT_AUTHORITY + "/" + PATH_USER_DETAIL);

    /* Inner class that defines the table contents of the login table */
    public static final class UserDetailEntry implements BaseColumns {

        public static final Uri CONTENT_URI =
                BASE_CONTENT_URI.buildUpon().appendPath(PATH_USER_DETAIL).build();

        public static final String CONTENT_TYPE =
                ContentResolver.CURSOR_DIR_BASE_TYPE + "/" + CONTENT_AUTHORITY + "/" + PATH_USER_DETAIL;
        public static final String CONTENT_ITEM_TYPE =
                ContentResolver.CURSOR_ITEM_BASE_TYPE + "/" + CONTENT_AUTHORITY + "/" + PATH_USER_DETAIL;

        // Table name

        public static final String TABLE_NAME="VideoCategory";


        // user detail column name

        public static final String ID= "id1";

        public static final String Category = "category";


        //function to build User detail uri for content provider
        public static Uri buildUserDetailUri(long id) {
            return ContentUris.withAppendedId(CONTENT_URI, id);
        }

        public static int getIdFromUri(Uri uri) {
            return Integer.parseInt(uri.getPathSegments().get(1));
        }
    }


    public static String getIdFromUri(Uri uri) {
        return uri.getPathSegments().get(1);
    }


    /* Inner class that defines the table contents of the TestDetail table */


    /* Inner class that defines the table contents of the package table */


    //function to build User detail uri for content provider


}
