package com.appsforgreece.gs;


import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;

public class NotificationsReceiver extends BroadcastReceiver {

  @Override
  public void onReceive(Context context, Intent intent) {
	  
    Intent service = new Intent(context, NotificationCheck.class);
    context.startService(service);
  }
} 