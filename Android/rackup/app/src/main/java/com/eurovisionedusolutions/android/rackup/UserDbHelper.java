package com.eurovisionedusolutions.android.rackup;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import com.eurovisionedusolutions.android.rackup.UserContract.UserDetailEntry;

public class UserDbHelper extends SQLiteOpenHelper {
    static final String DATABASE_NAME = "user.db";
    private static final int DATABASE_VERSION = 2;

    public UserDbHelper(Context context) {
        super(context, DATABASE_NAME, null, 2);
    }

    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        String SQL_CREATE_VIDEO_DETAIL_TABLE = "CREATE TABLE VideoCategory (id1 INTEGER,category TEXT  );";
        String SQL_CREATE_VIDEO_URL_TABLE = "CREATE TABLE VideoDetail (foreign_key INTEGER,name TEXT, url TEXT, faculty TEXT  );";
        sqLiteDatabase.execSQL("CREATE TABLE userdetail1 (_id INTEGER PRIMARY KEY,email TEXT NOT NULL, name TEXT, date_of_birth TEXT, phone_number TEXT, " + UserDetailEntry.CoLUMN_PASSWORD + " TEXT, " + UserDetailEntry.CoLUMN_TOKEN + " TEXT, " + UserDetailEntry.CoLUMN_ADDRESS + " TEXT, " + UserDetailEntry.CoLUMN_STUDENT_NAME + " TEXT  );");
        sqLiteDatabase.execSQL("CREATE TABLE VideoCategory (id1 INTEGER,category TEXT  );");
        sqLiteDatabase.execSQL("CREATE TABLE VideoDetail (foreign_key INTEGER,name TEXT, url TEXT, faculty TEXT  );");
    }

    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int oldVersion, int newVersion) {
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS userdetail1");
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS VideoCategory");
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS VideoDetail");
        onCreate(sqLiteDatabase);
    }
}
