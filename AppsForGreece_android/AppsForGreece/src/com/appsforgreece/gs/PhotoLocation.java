package com.appsforgreece.gs;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import android.os.Bundle;
import android.annotation.SuppressLint;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.widget.ImageView;
import android.widget.Toast;

public class PhotoLocation extends FragmentActivity {
	
	private String lat,lon;
	private GoogleMap googleMap;
	private ImageView back;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.photo_location);
		back=(ImageView) findViewById(R.id.back);
		back.setOnClickListener(new View.OnClickListener() {

	        @Override
	        public void onClick(View v) {
	        	finish();
	        }
	    });
		try {
			lat=(String) getIntent().getSerializableExtra(Events.KEY_LAT);
			lon=(String) getIntent().getSerializableExtra(Events.KEY_LON);
            initilizeMap();
            googleMap.setMyLocationEnabled(true);
            googleMap.addMarker(new MarkerOptions()
				.position(new LatLng(Double.parseDouble(lat+""), Double.parseDouble(lon+"")))
				.icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_GREEN)));
    		googleMap.moveCamera(CameraUpdateFactory.newLatLngZoom(new LatLng(Double.parseDouble(lat), Double.parseDouble(lon)), 12));
        } 
        catch (NullPointerException e) {
			Toast.makeText(getApplicationContext(), "Something went wrong on loading. Please try later!", Toast.LENGTH_LONG).show();
            finish();
        }
        catch (Exception e) {
			Toast.makeText(getApplicationContext(), "Something went wrong on loading. Please try later!", Toast.LENGTH_LONG).show();
            finish();
        }
	}
	
	@Override
    protected void onResume() {
        super.onResume();
        initilizeMap();
    }
    
    @Override
    protected void onPause() {
      super.onPause();
    }
	
	@SuppressLint("NewApi")
	private void initilizeMap() {
        if (googleMap == null) {
        	googleMap = ((SupportMapFragment) this.getSupportFragmentManager()
        	                .findFragmentById(R.id.map)).getMap();
            if (googleMap == null) {
                Toast.makeText(getApplicationContext(),
                        "Sorry! unable to create maps", Toast.LENGTH_SHORT)
                        .show();
            }
        }
    }

	

}

