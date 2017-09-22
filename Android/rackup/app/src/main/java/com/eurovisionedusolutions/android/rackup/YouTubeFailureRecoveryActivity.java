package com.eurovisionedusolutions.android.rackup;

import android.content.Intent;
import android.widget.Toast;
import com.google.android.youtube.player.YouTubeBaseActivity;
import com.google.android.youtube.player.YouTubeInitializationResult;
import com.google.android.youtube.player.YouTubePlayer.OnInitializedListener;
import com.google.android.youtube.player.YouTubePlayer.Provider;

public abstract class YouTubeFailureRecoveryActivity extends YouTubeBaseActivity implements OnInitializedListener {
    private static final int RECOVERY_DIALOG_REQUEST = 1;

    protected abstract Provider getYouTubePlayerProvider();

    public void onInitializationFailure(Provider provider, YouTubeInitializationResult errorReason) {
        if (errorReason.isUserRecoverableError()) {
            errorReason.getErrorDialog(this, 1).show();
            return;
        }
        Toast.makeText(this, String.format(getString(R.string.error_player), new Object[]{errorReason.toString()}), 1).show();
    }

    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (requestCode == 1) {
            getYouTubePlayerProvider().initialize(DeveloperKey.DEVELOPER_KEY, this);
        }
    }
}
