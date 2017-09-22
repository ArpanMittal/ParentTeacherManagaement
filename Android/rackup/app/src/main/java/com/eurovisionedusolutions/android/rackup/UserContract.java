package com.eurovisionedusolutions.android.rackup;

import android.content.ContentUris;
import android.net.Uri;
import android.provider.BaseColumns;

public class UserContract {
    public static final Uri BASE_CONTENT_URI = Uri.parse("content://com.eurovisionedusolutions.android.rackup");
    public static final Uri BASE_CONTENT_URI_Full = Uri.parse("content://com.eurovisionedusolutions.android.rackup/userdetail1");
    public static final String CONTENT_AUTHORITY = "com.eurovisionedusolutions.android.rackup";
    public static final String PATH_USER_DETAIL = "userdetail1";

    public static final class UserDetailEntry implements BaseColumns {
        public static final String COLUMN_ID = "_id";
        public static final String CONTENT_ITEM_TYPE = "vnd.android.cursor.item/com.eurovisionedusolutions.android.rackup/userdetail1";
        public static final String CONTENT_TYPE = "vnd.android.cursor.dir/com.eurovisionedusolutions.android.rackup/userdetail1";
        public static final Uri CONTENT_URI = UserContract.BASE_CONTENT_URI.buildUpon().appendPath("userdetail1").build();
        public static String CoLUMN_ADDRESS = "address";
        public static final String CoLUMN_CITY = "city";
        public static final String CoLUMN_COUNTRY = "country";
        public static final String CoLUMN_DATE_OF_BIRTH = "date_of_birth";
        public static final String CoLUMN_EMAIL = "email";
        public static final String CoLUMN_NAME = "name";
        public static String CoLUMN_PASSWORD = GlobalConstants.PASSWORD_GRANTTYPE;
        public static final String CoLUMN_PHONE_NUMBER = "phone_number";
        public static final String CoLUMN_PHOTO_PATH = "photo_path";
        public static final String CoLUMN_STATE = "state";
        public static String CoLUMN_STUDENT_NAME = "student_name";
        public static String CoLUMN_TOKEN = DBHelper.token;
        public static final String CoLUMN_VERIFIED = "verified";
        public static final String TABLE_NAME = "userdetail1";

        public static Uri buildUserDetailUri(long id) {
            return ContentUris.withAppendedId(CONTENT_URI, id);
        }

        public static int getIdFromUri(Uri uri) {
            return Integer.parseInt((String) uri.getPathSegments().get(1));
        }
    }

    public static String getIdFromUri(Uri uri) {
        return (String) uri.getPathSegments().get(1);
    }
}
