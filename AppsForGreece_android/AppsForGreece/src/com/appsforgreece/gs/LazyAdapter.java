package com.appsforgreece.gs;

import java.util.ArrayList;
import java.util.HashMap;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class LazyAdapter extends BaseAdapter {
    
    //private Activity activity;
    private ArrayList<HashMap<String, String>> data;
    private static LayoutInflater inflater=null;
    public ImageLoader imageLoader;
    private String category=null;
    
    public LazyAdapter(Context context, ArrayList<HashMap<String, String>> d) {
        //activity = a;
        data=d;
        inflater = (LayoutInflater)context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        imageLoader=new ImageLoader(context.getApplicationContext());
    }

    

	public int getCount() {
		if(data==null)
			return 0;
        return data.size();
    }

    public Object getItem(int position) {
        return position;
    }

    public long getItemId(int position) {
        return position;
    }
    
    public View getView(int position, View convertView, ViewGroup parent) {
    	
        View vi=convertView;
        if(convertView==null)
            vi = inflater.inflate(R.layout.list_row,parent, false);
        
        TextView titlos = (TextView)vi.findViewById(R.id.titlos);
        TextView description = (TextView)vi.findViewById(R.id.description);
        TextView date = (TextView)vi.findViewById(R.id.date);
        TextView lat = (TextView)vi.findViewById(R.id.lat);
        TextView lon = (TextView)vi.findViewById(R.id.lon);
        ImageView image=(ImageView)vi.findViewById(R.id.image);
        ImageView map=(ImageView)vi.findViewById(R.id.map);
        
        HashMap<String, String> news_map = new HashMap<String, String>();
        try{
        	news_map = data.get(position);
        	if(news_map.get(TabsView.KEY_LAT).equals("0")){
        		map.setVisibility(View.GONE);
        	}else{
    	        lat.setText(news_map.get(TabsView.KEY_LAT));
    	        lon.setText(news_map.get(TabsView.KEY_LON));
        	}
	        date.setText(news_map.get(TabsView.KEY_DATE));
	        titlos.setText(news_map.get(TabsView.KEY_TITLE));
	        description.setText(news_map.get(TabsView.KEY_DESCRIPTION));
	        imageLoader.DisplayImage(news_map.get(TabsView.KEY_PHOTO), image);
        }catch(NullPointerException e){
        	e.printStackTrace();
        }
       
        return vi;
    }
}