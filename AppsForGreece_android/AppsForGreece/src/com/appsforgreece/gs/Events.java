package com.appsforgreece.gs;

import java.util.ArrayList;
import java.util.HashMap;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.AbsListView;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.AbsListView.OnScrollListener;
import android.widget.TextView;
import android.widget.Toast;

public class Events extends Fragment implements OnScrollListener {
	
	ListView listV;
	ProgressBar loading;
	private SharedPreferences mPrefs;
	public static final String KEY_ID = "id";
	public static final String KEY_PHOTO = "photo";
	public static final String KEY_DESCRIPTION = "description";
	public static final String KEY_LAT = "lat";
	public static final String KEY_LON = "lon";
	public static final String KEY_DATE = "published_date";
	public final int MAX_ITEMS_PER_PAGE = 6;
	private int offset=MAX_ITEMS_PER_PAGE;
	private int onThisItemGetMore=MAX_ITEMS_PER_PAGE/2;

	private static ArrayList<HashMap<String, String>> eventsList = new ArrayList<HashMap<String, String>>();
		
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container,
			Bundle savedInstanceState) {
		View rootView = inflater.inflate(R.layout.events,
				container, false);
		
			loading=(ProgressBar) rootView.findViewById(R.id.loading);
			listV= (ListView) rootView.findViewById(R.id.list);
			listV.setAdapter(TabsView.adapterEvents);
			listV.setOnScrollListener(this);
			listV.bringToFront(); 
			
			
			
		return rootView;
	}

	@Override
	public void onScroll(AbsListView view, int firstVisibleItem,
			int visibleItemCount, int totalItemCount) {
		
		if((firstVisibleItem == onThisItemGetMore)){
			onThisItemGetMore+=MAX_ITEMS_PER_PAGE;
			new GetMoreEvents().execute();
		}
	}

	@Override
	public void onScrollStateChanged(AbsListView arg0, int arg1) {
		
	}
	
	
	
	private class GetMoreEvents extends AsyncTask<Void, Void, Void> {
		
	    @Override
	    protected void onPreExecute() {
	        super.onPreExecute();    
	        Log.e("get more events",offset+"!");
	    }

	    @Override
	    protected Void doInBackground(Void... arg0) {
	    	String URL = "http://www.e-progress.gr/thessaloniki_appsforgreece/mobile_more_events/";

			XMLParser parser = new XMLParser();
			String xml;
			Document doc;
			NodeList nl;
	        try{
	            	URL+="?offset="+offset+"&userid="+TabsView.userId;
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
		    				TabsView.eventsList.add(map);
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
	        TabsView.adapterEvents.notifyDataSetChanged();
	        offset=offset+MAX_ITEMS_PER_PAGE;
	    }

	}
	
	public void openMap(View v){
		try{
			View parentView = (View) v.getParent();
			TextView imageLat = (TextView) parentView.findViewById(R.id.lat);
	        TextView imageLon = (TextView) parentView.findViewById(R.id.lon);
	
	        String imageLatV= imageLat.getText().toString();
	        String imageLonV= imageLon.getText().toString();
	        Intent i=new Intent(getActivity(),PhotoLocation.class);
	        i.putExtra(KEY_LAT, imageLatV);
	        i.putExtra(KEY_LON, imageLonV);
	        startActivity(i);
		}catch(Exception e){
			Toast.makeText(getActivity(), "Κάτι πήγε στραβά,  Παρακαλώ δοκιμάστε αργότερα!", Toast.LENGTH_LONG).show();
		}
	}

	

}
