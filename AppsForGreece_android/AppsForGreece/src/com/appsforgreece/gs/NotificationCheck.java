package com.appsforgreece.gs;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.media.RingtoneManager;
import android.net.ParseException;
import android.net.Uri;
import android.os.Binder;
import android.os.IBinder;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.TaskStackBuilder;
import android.util.Log;

public class NotificationCheck extends Service {
	
	  private String KEY_ID="id";
	  private String KEY_PUBLISHED="published_date";
	  private String KEY_EVENTID="photo_reportaz_id";
	  private String KEY_DESCRIPTION="description";
	  private String URLnotifications="",xml;
	  private SharedPreferences mPrefs;
	  private XMLParser parser = new XMLParser();
	  private NodeList nl;
	  private Element e;
	  private String id,eventId,description;
	  private final IBinder mBinder = new MyBinder();
	  private Date date,lastDate;
	  GPSTracker gps;
	  String notId;
	  
	  @Override
	  public int onStartCommand(Intent intent, int flags, int startId) {
	    mPrefs = this.getSharedPreferences("com.appsforgreece.gs", Context.MODE_PRIVATE);
		gps = new GPSTracker(this);
		gps.getLocation();
	    
	    try{
		    String userId=mPrefs.getString("id", null);
		    String role=mPrefs.getString("role", null);
		    try{
				lastDate= stringToDate(mPrefs.getString("notId", null));
				if(lastDate==null)
					lastDate=stringToDate("2000-01-01 00:00:00");
			}catch(Exception e){
					lastDate=stringToDate("2000-01-01 00:00:00");
			}
		    if(role.equals("0")){
			    URLnotifications="http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_citizens_notifications?&userid="+userId+"&lon="+gps.getLongitude()+"&lat="+gps.getLatitude();    	
		    }else if(role.equals("1")){
			    URLnotifications="http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_officials_notifications?&userid="+userId+"&lon="+gps.getLongitude()+"&lat="+gps.getLatitude(); 
		    }
		    if(URLnotifications.length()>1){
		    	getNotifications();
		    }
	    }catch(Exception e){
	    	Log.e("Exception service",e.toString()+"!");
	    }
	    return Service.START_NOT_STICKY;
		  
	  }

	  @Override
	  public IBinder onBind(Intent intent) {
		  return mBinder;
	  }
	  
	  public class MyBinder extends Binder {
		  NotificationCheck getService() {
		      return NotificationCheck.this;
		    }
		  }
	  
	  public void getNotifications(){
		 Thread thread=new Thread()
			{
			    @Override
			    public void run() {
					
					int mId=0;
					try{
					  xml = parser.getXmlFromUrl(URLnotifications);
				    	if(xml!=null){
							Document doc = parser.getDomElement(xml);
								nl = doc.getElementsByTagName("article");
								e = (Element) nl.item((nl.getLength()-1));
								id=parser.getValue(e, KEY_ID);
								date= stringToDate(parser.getValue(e, KEY_PUBLISHED));
								eventId=parser.getValue(e, KEY_EVENTID);
								description=parser.getValue(e, KEY_DESCRIPTION);
								mId=Integer.parseInt(id);
								if(id.length()>0){
									
									if(date.after(lastDate)){
										Editor editor = mPrefs.edit();
										editor.putString("notId",parser.getValue(e, KEY_PUBLISHED));
										editor.commit();	
										Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
										NotificationCompat.Builder mBuilder =
										        new NotificationCompat.Builder(NotificationCheck.this)
										        .setSmallIcon(R.drawable.notification_icon)
										        .setContentTitle(description);
										mBuilder.setAutoCancel(true);
										mBuilder.setSound(alarmSound);
										Intent resultIntent = new Intent(NotificationCheck.this, EventNotification.class);
										resultIntent.putExtra("event_id", eventId);
										TaskStackBuilder stackBuilder = TaskStackBuilder.create(NotificationCheck.this);
										stackBuilder.addParentStack(Login.class);
										stackBuilder.addNextIntent(resultIntent);
										PendingIntent resultPendingIntent =
										        stackBuilder.getPendingIntent(
										            0,
										            PendingIntent.FLAG_UPDATE_CURRENT
										        );
										mBuilder.setContentIntent(resultPendingIntent);
										NotificationManager mNotificationManager =
										    (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
										mNotificationManager.notify(mId, mBuilder.build());
									}
								}
				    	}
			    	}catch(NullPointerException e){
			    		
					}catch(Exception e){
						
					}
			    }
		 };
		 thread.start();
	  }
	  
	  
	  private Date stringToDate(String s){  
		  SimpleDateFormat  format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");  
		  try {  
		      Date date = format.parse(s);
			  return date;
		  } catch (Exception e) {  
		      e.printStackTrace(); 
			  return new Date("2000-01-01 00:00:00"); 
		  }
	  }
	}