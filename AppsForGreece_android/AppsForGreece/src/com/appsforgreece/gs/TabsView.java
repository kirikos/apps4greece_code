package com.appsforgreece.gs;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.annotation.SuppressLint;
import android.support.v7.app.ActionBar;
import android.support.v7.app.ActionBar.Tab;
import android.support.v7.app.ActionBarActivity;
import android.app.AlarmManager;
import android.app.FragmentTransaction;
import android.app.PendingIntent;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.os.IBinder;
import android.provider.MediaStore;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.AbsListView;
import android.widget.AbsListView.OnScrollListener;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

public class TabsView extends ActionBarActivity implements ActionBar.TabListener {

	
	SectionsPagerAdapter mSectionsPagerAdapter;

	
	ViewPager mViewPager;
	private SharedPreferences mPrefs;
	private ConnectionDetector cd;
	private Boolean isInternetPresent,exitFlag=false;
	static ArrayList<HashMap<String, String>> allList = new ArrayList<HashMap<String, String>>();
	static ArrayList<HashMap<String, String>> koinoniaList = new ArrayList<HashMap<String, String>>();
	static ArrayList<HashMap<String, String>> oikonomiaList = new ArrayList<HashMap<String, String>>();
	static ArrayList<HashMap<String, String>> politismosList = new ArrayList<HashMap<String, String>>();
	static ArrayList<HashMap<String, String>> eventsList = new ArrayList<HashMap<String, String>>();
	public static final String KEY_ID = "id";
	public static final String KEY_PHOTO = "photo";
	public static final String KEY_TITLE = "title";
	public static final String KEY_DESCRIPTION = "description";
	public static final String KEY_CATEGORY = "category";
	public static final String KEY_DATE = "published_date";
	public static final String KEY_LAT = "lat";
	public static final String KEY_LON = "lon";
	public static LazyAdapter adapterAll,adapterKoinonia,adapterOikonomia,adapterPolitismos,adapterEvents;
	public static final int MAX_ITEMS_PER_PAGE = 6;
	public static int offsetAll=MAX_ITEMS_PER_PAGE,offsetKoinonia=MAX_ITEMS_PER_PAGE,offsetOikonomia=MAX_ITEMS_PER_PAGE,offsetPolitismos=MAX_ITEMS_PER_PAGE;
	public static int onThisItemGetMoreAll=MAX_ITEMS_PER_PAGE/2,onThisItemGetMoreKoinonia=MAX_ITEMS_PER_PAGE/2,onThisItemGetMoreOikonomia=MAX_ITEMS_PER_PAGE/2,onThisItemGetMorePolitismos=MAX_ITEMS_PER_PAGE/2;
	public static String userId;
    private NotificationCheck NotCheck;
    private android.support.v7.app.ActionBar actionBar;
    
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		getWindow().requestFeature(Window.FEATURE_ACTION_BAR);
		setContentView(R.layout.tabs_view);
        mPrefs = this.getSharedPreferences("com.appsforgreece.gs", Context.MODE_PRIVATE);
        userId=mPrefs.getString("id", null);
		cd = new ConnectionDetector(this);
		
		actionBar = getSupportActionBar();
		if(actionBar==null){
			Toast.makeText(this, "Το κινητό δεν υποστηρίζει την εφαρμογή", Toast.LENGTH_LONG).show();
			finish();
		}
		try{
			actionBar.setDisplayShowTitleEnabled(false);
			actionBar.setDisplayShowHomeEnabled(false);
			actionBar.setNavigationMode(ActionBar.NAVIGATION_MODE_TABS);
			allList.clear();
			koinoniaList.clear();
			oikonomiaList.clear();
			politismosList.clear();
			eventsList.clear();
			adapterAll=new LazyAdapter(this,allList);
			adapterKoinonia=new LazyAdapter(this,koinoniaList);
			adapterOikonomia=new LazyAdapter(this,oikonomiaList);
			adapterPolitismos=new LazyAdapter(this,politismosList);
			adapterEvents=new LazyAdapter(this,eventsList);
			new GetArticles().execute("0");
			new GetArticles().execute("1");
			new GetArticles().execute("2");
			new GetArticles().execute("3");
			new GetEvents().execute();
			mSectionsPagerAdapter = new SectionsPagerAdapter(
					getSupportFragmentManager());
	
			mViewPager = (ViewPager) findViewById(R.id.pager);
			mViewPager.setAdapter(mSectionsPagerAdapter);
	
			mViewPager
					.setOnPageChangeListener(new ViewPager.SimpleOnPageChangeListener() {
						@Override
						public void onPageSelected(int position) {
							actionBar.setSelectedNavigationItem(position);
						}
					});
	
			for (int i = 0; i < mSectionsPagerAdapter.getCount(); i++) {
				if(i==0)
					actionBar.addTab(actionBar.newTab()
						.setCustomView(R.layout.gegonota_text)
						.setTabListener(this));
				else
					actionBar.addTab(actionBar.newTab()
							.setText(mSectionsPagerAdapter.getPageTitle(i))
							.setTabListener(this));
			}
			
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
			
			mViewPager.setCurrentItem(1);
			try{
				checkForNotifications();
			}catch(Exception e){
			}
			
			if(!cd.isConnectingToInternet()){
				Toast.makeText(this, "Δεν υπάρχει σύνδεση στο δίκτυο...Ανοίξτε τα δεδομένα ή το WIFI!", Toast.LENGTH_LONG).show();
				finish();
			}

		}catch(Exception ex){
			Toast.makeText(this, "Το κινητό δεν υποστηρίζει την εφαρμογή", Toast.LENGTH_LONG).show();
			finish();
		}
	}
	
	@Override
    protected void onPause() {
      super.onPause();
	  exitFlag=false;
	  try{
		  unbindService(mConnection);
	  }catch(Exception e){}
    }
	
	@Override
    protected void onResume() {
        super.onResume();
		exitFlag=false;
    }
    
    @Override
    public void onBackPressed() {
    	if(exitFlag==false){
    		exitFlag=true;
    		Toast.makeText(getApplicationContext(), "Πατήστε άλλη μία για να βγείτε!", Toast.LENGTH_SHORT).show();
    	}else{
    		try{
    			unbindService(mConnection);
    		}catch(Exception e){}
    		allList.clear();
    		koinoniaList.clear();
    		oikonomiaList.clear();
    		politismosList.clear();
    		eventsList.clear();
    		super.onBackPressed();
    	}
    }
	


	@Override
	public void onTabSelected(ActionBar.Tab tab,
			android.support.v4.app.FragmentTransaction fragmentTransaction) {
			mViewPager.setCurrentItem(tab.getPosition());
	}

	@Override
	public void onTabUnselected(ActionBar.Tab tab,
			android.support.v4.app.FragmentTransaction fragmentTransaction) {
	}

	@Override
	public void onTabReselected(ActionBar.Tab tab,
			android.support.v4.app.FragmentTransaction fragmentTransaction) {
	}

	/**
	 * A {@link FragmentPagerAdapter} that returns a fragment corresponding to
	 * one of the sections/tabs/pages.
	 */
	public class SectionsPagerAdapter extends FragmentPagerAdapter {

		public SectionsPagerAdapter(FragmentManager fm) {
			super(fm);
		}

		@Override
		public Fragment getItem(int position) {
			if(position==0){
				Fragment fragment= new Events();
				return fragment;
			}
			Fragment fragment = new DummySectionFragment();
			Bundle args = new Bundle();
			args.putInt(DummySectionFragment.ARG_SECTION_NUMBER, position);
			args.putString(DummySectionFragment.ARG_SECTION_USERID, mPrefs.getString("id", null));
			fragment.setArguments(args);
			return fragment;
		}

		@Override
		public int getCount() {
			return 5;
		}

		@Override
		public CharSequence getPageTitle(int position) {
			Locale l = Locale.getDefault();
			switch (position) {
			case 0:
				return getString(R.string.gegonota).toUpperCase(l);
			case 1:
				return getString(R.string.all).toUpperCase(l);
			case 2:
				return getString(R.string.koinonia).toUpperCase(l);
			case 3:
				return getString(R.string.oikonomia).toUpperCase(l);
			case 4:
				return getString(R.string.politismos).toUpperCase(l);
			}
			return null;
		}
	}

	
	public static class DummySectionFragment extends Fragment implements OnScrollListener {
		public static final String ARG_SECTION_NUMBER = "section_number";
		public static final String ARG_SECTION_LIST = "section_list";
		public static final String ARG_SECTION_USERID = "section_userid";
		int position;
		
	    LazyAdapter adapter;
	    ListView listV;
	    String userId;
		ProgressBar loading;
		
		public DummySectionFragment() {
		}

		@Override
		public View onCreateView(LayoutInflater inflater, ViewGroup container,
				Bundle savedInstanceState) {
			View rootView = inflater.inflate(R.layout.fragment_tabs_view_dummy,
					container, false);
				position=getArguments().getInt(ARG_SECTION_NUMBER);
				userId=getArguments().getString(ARG_SECTION_USERID);
				
				loading=(ProgressBar)rootView.findViewById(R.id.loading);
				listV= (ListView) rootView.findViewById(R.id.list);
				try{
					listV.bringToFront();       
			        
			        switch (position) {
						case 1:
							listV.setAdapter(adapterAll);
							break;
						case 2:
							listV.setAdapter(adapterKoinonia);
							break;
						case 3:
							listV.setAdapter(adapterOikonomia);
							break;
						case 4:
							listV.setAdapter(adapterPolitismos);
							break;
			        }
					listV.setOnScrollListener(this);
				}catch(Exception e){
					Log.e("DummyException","!");
				}
				
			return rootView;
		}
		
		@Override
		public void onScroll(AbsListView view, int firstVisibleItem,
				int visibleItemCount, int totalItemCount) {
			int onThis=0;
			
			switch (position) {
				case 1:
					onThis=onThisItemGetMoreAll;
					break;
				case 2:
					onThis=onThisItemGetMoreKoinonia;
					break;
				case 3:
					onThis=onThisItemGetMoreOikonomia;
					break;
				case 4:
					onThis=onThisItemGetMorePolitismos;
					break;
			}
			
		    if((firstVisibleItem == onThis)){
		    	switch (position) {
					case 1:
						onThisItemGetMoreAll+=MAX_ITEMS_PER_PAGE;
						break;
					case 2:
						onThisItemGetMoreKoinonia+=MAX_ITEMS_PER_PAGE;
						break;
					case 3:
						onThisItemGetMoreOikonomia+=MAX_ITEMS_PER_PAGE;
						break;
					case 4:
						onThisItemGetMorePolitismos+=MAX_ITEMS_PER_PAGE;
						break;
		    	}
		    	new GetMoreArticles().execute(position+"");
		    }
		}

		@Override
		public void onScrollStateChanged(AbsListView view, int scrollState) {
			
		}
		
		private class GetMoreArticles extends AsyncTask<String, Void, Void> {
			String pos;
			int offset;
			
		    @Override
		    protected void onPreExecute() {
		        super.onPreExecute();

		    }

		    @Override
		    protected Void doInBackground(String... arg0) {
	        	pos = arg0[0];
	        	pos=((Integer.parseInt(pos))-1)+"";
		    	String URL = "http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_get_more_news";
				XMLParser parser = new XMLParser();
				String xml;
				Document doc;
				NodeList nl;
				switch (Integer.parseInt(pos)) {
				case 0:
					offset=offsetAll;
					break;
				case 1:
					offset=offsetKoinonia;
					break;
				case 2:
					offset=offsetOikonomia;
					break;
				case 3:
					offset=offsetPolitismos;
					break;
	        }
		        try{
		            	URL+="?offset="+offset+"&userid="+userId+"&category="+pos;
			    		xml = parser.getXmlFromUrl(URL);
			    		doc = parser.getDomElement(xml);
			    		nl = doc.getElementsByTagName("article");
			    		for (int i = 0; i < nl.getLength(); i++) {
			    			
			    			// creating new HashMap
			    			HashMap<String, String> map = new HashMap<String, String>();
			    			Element e = (Element) nl.item(i);
			    			try{
			    				map.put(KEY_ID, parser.getValue(e, KEY_ID));
			    				map.put(KEY_PHOTO, "http://www.e-progress.gr/"+parser.getValue(e, KEY_PHOTO));
			    				map.put(KEY_TITLE, parser.getValue(e, KEY_TITLE));
			    				map.put(KEY_DESCRIPTION, parser.getValue(e, KEY_DESCRIPTION));
			    				map.put(KEY_CATEGORY, parser.getValue(e, KEY_CATEGORY));
			    				map.put(KEY_LAT,"0");
			    				map.put(KEY_LON,"0");
			    				map.put(KEY_DATE, parser.getValue(e, KEY_DATE));
			    				switch (Integer.parseInt(pos)) {
				    				case 0:
				    					allList.add(map);
				    					break;
				    				case 1:
				    					koinoniaList.add(map);
				    					break;
				    				case 2:
				    					oikonomiaList.add(map);
				    					break;
				    				case 3:
				    					politismosList.add(map);
				    					break;
			    				}
			    				
			    			}catch(NullPointerException ex){
			    				ex.printStackTrace();
			    			}catch(Exception ex){
			    				ex.printStackTrace();
			    			}
			    		}
		        }catch(Exception e){
		        	Log.e("data exception","!");
		        }
				

		        return null;
		    }

		    @Override
		    protected void onPostExecute(Void result) {
		        super.onPostExecute(result);
		        switch (Integer.parseInt(pos)) {
					case 0:
						adapterAll.notifyDataSetChanged();
						offsetAll=offsetAll+MAX_ITEMS_PER_PAGE;
						break;
					case 1:
						adapterKoinonia.notifyDataSetChanged();
						offsetKoinonia=offsetKoinonia+MAX_ITEMS_PER_PAGE;
						break;
					case 2:
						adapterOikonomia.notifyDataSetChanged();
						offsetOikonomia=offsetOikonomia+MAX_ITEMS_PER_PAGE;
						break;
					case 3:
						adapterPolitismos.notifyDataSetChanged();
						offsetPolitismos=offsetPolitismos+MAX_ITEMS_PER_PAGE;
						break;
		        }
		   		
		    }

		}
		
			}
	
	private class GetArticles extends AsyncTask<String, Void, Void> {
		
		String pos;
    	
        @Override
        protected void onPreExecute() {
            super.onPreExecute();         
 
        }
 
        @Override
        protected Void doInBackground(String... arg0) {
        	pos = arg0[0];
        	String URL = "http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_get_news";
    		XMLParser parser = new XMLParser();
    		String xml;
    		Document doc;
    		NodeList nl;
    		int i;
    		Element e;
            try{
            	URL+="?userid="+mPrefs.getString("id", null)+"&category="+pos;
		    		xml = parser.getXmlFromUrl(URL);
		    		doc = parser.getDomElement(xml);
		    		nl = doc.getElementsByTagName("article");
		    		for ( i= 0; i < nl.getLength(); i++) {
		    			
		    			// creating new HashMap
		    			HashMap<String, String> map = new HashMap<String, String>();
		    			e = (Element) nl.item(i);
		    			try{
		    				map.put(KEY_ID, parser.getValue(e, KEY_ID));
		    				map.put(KEY_PHOTO, "http://www.e-progress.gr/"+parser.getValue(e, KEY_PHOTO));
		    				map.put(KEY_TITLE, parser.getValue(e, KEY_TITLE));
		    				map.put(KEY_DESCRIPTION, parser.getValue(e, KEY_DESCRIPTION));
		    				map.put(KEY_CATEGORY, parser.getValue(e, KEY_CATEGORY));
		    				map.put(KEY_LAT,"0");
		    				map.put(KEY_LON,"0");
		    				map.put(KEY_DATE, parser.getValue(e, KEY_DATE));
		    				switch (Integer.parseInt(pos)) {
			    				case 0:
			    					allList.add(map);
			    					break;
			    				case 1:
			    					koinoniaList.add(map);
			    					break;
			    				case 2:
			    					oikonomiaList.add(map);
			    					break;
			    				case 3:
			    					politismosList.add(map);
			    					break;
		    				}
		    				
		    			}catch(NullPointerException ex){
		    				ex.printStackTrace();
		    			}catch(Exception ex){
		    				ex.printStackTrace();
		    			}
		    		}
            }catch(Exception ex){
            	Log.e("data exception","!");
            }
    		
 
            return null;
        }
 
        @Override
        protected void onPostExecute(Void result) {
            super.onPostExecute(result);
            
            switch (Integer.parseInt(pos)) {
				case 0:
					adapterAll.notifyDataSetChanged();
					break;
				case 1:
					adapterKoinonia.notifyDataSetChanged();
					break;
				case 2:
					adapterOikonomia.notifyDataSetChanged();
					break;
				case 3:
					adapterPolitismos.notifyDataSetChanged();
					break;
            }
            
        }

		
 
    }
	
private class GetEvents extends AsyncTask<Void, Void, Void> {
		
	    @Override
	    protected void onPreExecute() {
	        super.onPreExecute();    

	    }

	    @Override
	    protected Void doInBackground(Void... arg0) {
	    	String URL = "http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_events";
	    	
			XMLParser parser = new XMLParser();
			String xml;
			Document doc;
			NodeList nl;
	        try{
	            	URL+="?&userid="+userId;
		    		xml = parser.getXmlFromUrl(URL);
		    		doc = parser.getDomElement(xml);
		    		nl = doc.getElementsByTagName("article");
		    		for (int i = 0; i < nl.getLength(); i++) {
		    			
		    			// creating new HashMap
		    			HashMap<String, String> map = new HashMap<String, String>();
		    			Element e = (Element) nl.item(i);
		    			try{
		    				map.put(KEY_ID, parser.getValue(e, KEY_ID));
		    				map.put(KEY_PHOTO, "http://www.e-progress.gr/"+parser.getValue(e, KEY_PHOTO));
		    				map.put(KEY_DESCRIPTION, parser.getValue(e, KEY_DESCRIPTION));
		    				map.put(KEY_LAT, parser.getValue(e, KEY_LAT));
		    				map.put(KEY_LON, parser.getValue(e, KEY_LON));
		    				map.put(KEY_DATE, parser.getValue(e, KEY_DATE));
		    				eventsList.add(map);
		    			}catch(NullPointerException ex){
		    				ex.printStackTrace();
		    			}catch(Exception ex){
		    				ex.printStackTrace();
		    			}
		    		}
	        }catch(Exception e){
	        	Log.e("data exception","!");
	        }
			

	        return null;
	    }

	    @Override
	    protected void onPostExecute(Void result) {
	        super.onPostExecute(result);
	        adapterEvents.notifyDataSetChanged();
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
		allList.clear();
		koinoniaList.clear();
		oikonomiaList.clear();
		politismosList.clear();
		eventsList.clear();
		finish();
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
			Toast.makeText(this, "Something went wrong. Please try later!", Toast.LENGTH_LONG).show();
		}
	}
	
	private void checkForNotifications(){
    	Intent i= new Intent(this, NotificationCheck.class);
    	PendingIntent pintent = PendingIntent.getService(this, 0, i, 0);
        Calendar cal = Calendar.getInstance();
        AlarmManager alarm = (AlarmManager)getSystemService(Context.ALARM_SERVICE);
        alarm.setRepeating(AlarmManager.RTC_WAKEUP, cal.getTimeInMillis(), 30*1000, pintent);
        bindService(i, mConnection,
                Context.BIND_AUTO_CREATE);
    }
	
	private ServiceConnection mConnection = new ServiceConnection() {
   	 
    	
        public void onServiceConnected(ComponentName className, 
            IBinder binder) {
          NotificationCheck.MyBinder b = (NotificationCheck.MyBinder) binder;
          NotCheck = b.getService();
        }

        public void onServiceDisconnected(ComponentName className) {
        	NotCheck = null;
        }
        
      };

}
