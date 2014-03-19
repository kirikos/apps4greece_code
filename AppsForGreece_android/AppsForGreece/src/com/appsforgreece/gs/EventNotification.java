package com.appsforgreece.gs;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.util.Log;
import android.view.Menu;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

public class EventNotification extends Activity {
	

	public static final String KEY_ID = "id";
	public static final String KEY_PHOTO = "photo";
	public static final String KEY_TITLE = "title";
	public static final String KEY_DESCRIPTION = "description";
	public static final String KEY_CATEGORY = "category";
	public static final String KEY_DATE = "published_date";
	public static final String KEY_LAT = "lat";
	public static final String KEY_LON = "lon";
	private String event_id;
	private SharedPreferences mPrefs;
	private String userId;
	TextView description,date,lat,lon;
	ImageView image,map;
    private ImageLoader imageLoader;
	ProgressBar loading;
	XMLParser parser;
	Element e;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.event_notification);
		
	        description = (TextView)findViewById(R.id.description);
	        date = (TextView)findViewById(R.id.date);
	        lat = (TextView)findViewById(R.id.lat);
	        lon = (TextView)findViewById(R.id.lon);
	        image=(ImageView)findViewById(R.id.image);
	        map=(ImageView)findViewById(R.id.map);
			loading=(ProgressBar)findViewById(R.id.loading);

	        mPrefs = this.getSharedPreferences("com.appsforgreece.gs", Context.MODE_PRIVATE);
	        userId=mPrefs.getString("id", null);
	        imageLoader=new ImageLoader(this);
	        event_id=getIntent().getExtras().getString("event_id");
	        
	        findViewById(R.id.camera).setOnClickListener(
					new View.OnClickListener() {
						@Override
						public void onClick(View view) {
							camera();
						}
					});
			
			findViewById(R.id.logout).setOnClickListener(
					new View.OnClickListener() {
						@Override
						public void onClick(View view) {
							logout();
						}
					});
			
			findViewById(R.id.back).setOnClickListener(
					new View.OnClickListener() {
						@Override
						public void onClick(View view) {
							Intent i=new Intent(EventNotification.this,Login.class);
							startActivity(i);
							finish();
						}
					});

	        new GetOneEvent().execute();
	}
	
	@Override
    public void onBackPressed() {
    	Intent i=new Intent(this,Login.class);
    	startActivity(i);
    	finish();
    }
	
private class GetOneEvent extends AsyncTask<Void, Void, Void> {
		int error=0;
	    @Override
	    protected void onPreExecute() {
	        super.onPreExecute();    
	        Log.e("getEvent","!");

	    }

	    @Override
	    protected Void doInBackground(Void... arg0) {
	    	String URL = "http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_get_event";
	    	
			parser = new XMLParser();
			String xml;
			Document doc;
			NodeList nl;
	        try{
	            	URL+="?userid="+userId+"&event_id="+event_id;
	            	Log.e("url",URL);
		    		xml = parser.getXmlFromUrl(URL);
		    		doc = parser.getDomElement(xml);
		    		nl = doc.getElementsByTagName("event");
		    		e = (Element) nl.item(0);
		    			try{
		    			 	runOnUiThread(new Runnable() {

		                        @Override
		                        public void run() {
		                        	imageLoader.DisplayImage("http://www.e-progress.gr/"+parser.getValue(e, KEY_PHOTO), image);
		                        }
		                    });
		    		        if(nl.getLength()>0){
			    				description.setText(parser.getValue(e, KEY_DESCRIPTION));
			    				lat.setText(parser.getValue(e, KEY_LAT));
			    				lon.setText(parser.getValue(e, KEY_LON));
			    				date.setText(parser.getValue(e, KEY_DATE));
		    		        }else{
		    		        	error=1;
		    		        }
		    			}catch(NullPointerException ex){
		    				Log.e("NullPointerException","!");
		    				ex.printStackTrace();
		    				error=1;
		    			}catch(Exception ex){
		    				Log.e("Exception","!");
		    				ex.printStackTrace();
		    				error=1;
		    			}
	        }catch(Exception e){
	        	Log.e("GetOneEvent exception","!");
	        	error=1;
	        }
			

	        return null;
	    }

	    @Override
	    protected void onPostExecute(Void result) {
	        super.onPostExecute(result);
	        if(error==1){
	        	Toast.makeText(EventNotification.this, "Κάτι πήγε στραβά,  Παρακαλώ δοκιμάστε αργότερα!",Toast.LENGTH_LONG).show();
	        	finish();
	        }
	        loading.setVisibility(View.GONE);
	        image.setVisibility(View.VISIBLE);
	        description.setVisibility(View.VISIBLE);
	        date.setVisibility(View.VISIBLE);
	        map.setVisibility(View.VISIBLE);
	    }

	}

	public void openMap(View v){
		try{
			View parentView = (View) v.getParent();
			TextView imageLat = (TextView) parentView.findViewById(R.id.lat);
	        TextView imageLon = (TextView) parentView.findViewById(R.id.lon);
	
	        String imageLatV= imageLat.getText().toString();
	        String imageLonV= imageLon.getText().toString();
	        Intent i=new Intent(this,PhotoLocation.class);
	        i.putExtra(KEY_LAT, imageLatV);
	        i.putExtra(KEY_LON, imageLonV);
	        startActivity(i);
		}catch(Exception e){
			Toast.makeText(this, "Κάτι πήγε στραβά,  Παρακαλώ δοκιμάστε αργότερα!", Toast.LENGTH_LONG).show();
		}
	}
	
	private void camera(){
		final String dir = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_PICTURES) + "/AppsFroGreece/"; 
	    File newdir = new File(dir); 
	    newdir.mkdirs();
	    String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss",
	        Locale.getDefault()).format(new Date());
	    String file = dir+timeStamp+".jpg";
	    File newfile = new File(file);
	    Editor editor = mPrefs.edit();
	    editor.putString("pathImage",file);
	    editor.commit();
	    try {
	        newfile.createNewFile();
	    } catch (IOException e) {
	    	
	    }       
        Uri outputFileUri = Uri.fromFile(newfile);
        Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE); 
        cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT, outputFileUri);	
        startActivityForResult(cameraIntent, 0);
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
	    super.onActivityResult(requestCode, resultCode, data);
	    
	    switch(requestCode) { 
	    	case 0:
	    		if(resultCode == RESULT_OK){  
	    			Intent upload_intent = new Intent(this, UploadImage.class);
	    			String path=mPrefs.getString("pathImage", null);
	    			upload_intent.putExtra("path", path);
					startActivity(upload_intent);
	    		}
	    		break;
	    }
	    
	}
	
	private void logout(){
		Editor editor = mPrefs.edit();
		String email=mPrefs.getString(Login.EXTRA_EMAIL, null);
		String notId;
		try{
			notId=mPrefs.getString("notId", null);
			if(notId==null)
				notId="-1";
		}catch(Exception e){
			notId="-1";
		}
		editor.clear();
		editor.putString(Login.EXTRA_EMAIL,email);
		editor.putString("notId",notId);
		editor.commit();
		finish();
	}

}
