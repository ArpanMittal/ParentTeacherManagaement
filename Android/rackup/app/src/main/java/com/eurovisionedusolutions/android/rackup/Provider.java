package com.eurovisionedusolutions.android.rackup;

import android.annotation.TargetApi;
import android.content.ContentProvider;
import android.content.ContentValues;
import android.content.UriMatcher;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.net.Uri;
import com.eurovisionedusolutions.android.rackup.UserContract.UserDetailEntry;

public class Provider extends ContentProvider {
    static final int CATEGORY = 200;
    static final int CONTENT_WITH_ID = 201;
    static final int Details = 300;
    static final int PACKAGE = 400;
    static final int USER = 100;
    static final int USER_WITH_ID = 101;
    private static final UriMatcher sUriMatcher = buildUriMatcher();
    private UserDbHelper mOpenUserHelper;

    static UriMatcher buildUriMatcher() {
        UriMatcher matcher = new UriMatcher(-1);
        String authority = "com.eurovisionedusolutions.android.rackup";
        matcher.addURI("com.eurovisionedusolutions.android.rackup", "userdetail1", 100);
        matcher.addURI("com.eurovisionedusolutions.android.rackup", "userdetail1/#", 101);
        matcher.addURI("com.eurovisionedusolutions.android.rackup", "VideoCategory", 200);
        matcher.addURI("com.eurovisionedusolutions.android.rackup", UserContract_Video_Details.PATH_USER_DETAIL, Details);
        return matcher;
    }

    public boolean onCreate() {
        this.mOpenUserHelper = new UserDbHelper(getContext());
        return true;
    }

    public String getType(Uri uri) {
        switch (sUriMatcher.match(uri)) {
            case 100:
                return UserDetailEntry.CONTENT_TYPE;
            case 101:
                return UserDetailEntry.CONTENT_ITEM_TYPE;
            default:
                throw new UnsupportedOperationException("Unknown uri: " + uri);
        }
    }

    public Cursor query(Uri uri, String[] projection, String selection, String[] selectionArgs, String sortOrder) {
        Cursor retCursor;
        switch (sUriMatcher.match(uri)) {
            case 100:
                retCursor = this.mOpenUserHelper.getReadableDatabase().query("userdetail1", projection, selection, selectionArgs, null, null, sortOrder);
                break;
            case 101:
                int id = UserDetailEntry.getIdFromUri(uri);
                String[] strArr = projection;
                retCursor = this.mOpenUserHelper.getReadableDatabase().query("userdetail1", strArr, "_id = ?", new String[]{String.valueOf(id)}, null, null, sortOrder);
                break;
            case 200:
                retCursor = this.mOpenUserHelper.getReadableDatabase().query("VideoCategory", projection, selection, selectionArgs, null, null, sortOrder);
                break;
            case Details /*300*/:
                retCursor = this.mOpenUserHelper.getReadableDatabase().query(UserContract_Video_Details.UserDetailEntry.TABLE_NAME, projection, selection, selectionArgs, null, null, sortOrder);
                break;
            default:
                throw new UnsupportedOperationException("Unknown uri: " + uri);
        }
        retCursor.setNotificationUri(getContext().getContentResolver(), uri);
        return retCursor;
    }

    public Uri insert(Uri uri, ContentValues values) {
        Uri returnUri;
        SQLiteDatabase db = this.mOpenUserHelper.getWritableDatabase();
        long _id;
        switch (sUriMatcher.match(uri)) {
            case 100:
                _id = db.insert("userdetail1", null, values);
                if (_id > 0) {
                    returnUri = UserDetailEntry.buildUserDetailUri(_id);
                    break;
                }
                throw new SQLException("Failed to insert row into " + uri);
            case 200:
                _id = db.insert("VideoCategory", null, values);
                if (_id > 0) {
                    returnUri = UserContract_Video_Category.UserDetailEntry.buildUserDetailUri(_id);
                    break;
                }
                throw new SQLException("Failed to insert row into " + uri);
            case Details /*300*/:
                _id = db.insert(UserContract_Video_Details.UserDetailEntry.TABLE_NAME, null, values);
                if (_id > 0) {
                    returnUri = UserContract_Video_Details.UserDetailEntry.buildUserDetailUri(_id);
                    break;
                }
                throw new SQLException("Failed to insert row into " + uri);
            default:
                throw new UnsupportedOperationException("Unknown uri: " + uri);
        }
        getContext().getContentResolver().notifyChange(uri, null);
        return returnUri;
    }

    public Uri insert_1(Uri uri, ContentValues values) {
        SQLiteDatabase db = this.mOpenUserHelper.getWritableDatabase();
        switch (sUriMatcher.match(uri)) {
            case 100:
                long _id = db.insert("VideoCategory", null, values);
                if (_id > 0) {
                    Uri returnUri = UserContract_Video_Category.UserDetailEntry.buildUserDetailUri(_id);
                    getContext().getContentResolver().notifyChange(uri, null);
                    return returnUri;
                }
                throw new SQLException("Failed to insert row into " + uri);
            default:
                throw new UnsupportedOperationException("Unknown uri: " + uri);
        }
    }

    public int delete(Uri uri, String selection, String[] selectionArgs) {
        int rowsDeleted;
        SQLiteDatabase db = this.mOpenUserHelper.getWritableDatabase();
        int match = sUriMatcher.match(uri);
        if (selection == null) {
            selection = "1";
        }
        switch (match) {
            case 100:
                rowsDeleted = db.delete("userdetail1", selection, selectionArgs);
                break;
            case 101:
                rowsDeleted = db.delete("userdetail1", "_id = ?", new String[]{String.valueOf(UserDetailEntry.getIdFromUri(uri))});
                break;
            case 200:
                rowsDeleted = db.delete("VideoCategory", selection, selectionArgs);
                break;
            case Details /*300*/:
                rowsDeleted = db.delete(UserContract_Video_Details.UserDetailEntry.TABLE_NAME, selection, selectionArgs);
                break;
            default:
                throw new UnsupportedOperationException("Unknown uri: " + uri);
        }
        if (rowsDeleted != 0) {
            getContext().getContentResolver().notifyChange(uri, null);
        }
        return rowsDeleted;
    }

    public int update(Uri uri, ContentValues values, String selection, String[] selectionArgs) {
        int rowsUpdated;
        SQLiteDatabase db = this.mOpenUserHelper.getWritableDatabase();
        switch (sUriMatcher.match(uri)) {
            case 100:
                rowsUpdated = db.update("userdetail1", values, selection, selectionArgs);
                break;
            case 101:
                rowsUpdated = db.update("userdetail1", values, "_id = ?", new String[]{String.valueOf(UserDetailEntry.getIdFromUri(uri))});
                break;
            default:
                throw new UnsupportedOperationException("Unknown uri: " + uri);
        }
        if (rowsUpdated != 0) {
            getContext().getContentResolver().notifyChange(uri, null);
        }
        return rowsUpdated;
    }

    public int bulkInsert(Uri uri, ContentValues[] values) {
        SQLiteDatabase db = this.mOpenUserHelper.getWritableDatabase();
        switch (sUriMatcher.match(uri)) {
            case 100:
                db.beginTransaction();
                int returnCount = 0;
                try {
                    for (ContentValues value : values) {
                        if (db.insert("userdetail1", null, value) != -1) {
                            returnCount++;
                        }
                    }
                    db.setTransactionSuccessful();
                    getContext().getContentResolver().notifyChange(uri, null);
                    return returnCount;
                } finally {
                    db.endTransaction();
                }
            default:
                return super.bulkInsert(uri, values);
        }
    }

    @TargetApi(11)
    public void shutdown() {
        this.mOpenUserHelper.close();
        super.shutdown();
    }
}
