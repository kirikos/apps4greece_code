package com.appsforgreece.gs;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;

import android.media.ExifInterface;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.app.Activity;
import android.content.ContentValues;
import android.content.Context;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.graphics.Point;
import android.util.Base64;
import android.util.Log;
import android.view.Display;
import android.view.View; 
import android.view.Window;
import android.view.animation.AlphaAnimation;

public class UploadImage extends Activity {
	
	ImageView myImage,backButton;
	String fullPath,response,id,descriptionValue;
	Button uploadButton;
	Bundle extras;
	Bitmap imageForShow,imageForUpload;
	EditText description;
	int widthU=600,heightU=450;
	int widthS=450;
	GPSTracker gps;
	private SharedPreferences mPrefs;
	File imgFile;
	ConnectionDetector cd;
	Boolean isInternetPresent;
	TextView loading;
	RelativeLayout loadingLayout;
	byte[] titleB,descriptionB;
	int destHeight,destHeightShow,rotation,rotationInDegrees;
	Matrix matrix;
	
	
    @SuppressWarnings("deprecation")
	@Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
		getWindow().requestFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.upload_image);
        
        try{
	        cd = new ConnectionDetector(getApplicationContext());
	        mPrefs = this.getSharedPreferences("com.appsforgreece.gs", Context.MODE_PRIVATE);
	        myImage = (ImageView) findViewById(R.id.image);
			uploadButton = (Button) findViewById(R.id.UploadButton);
			backButton = (ImageView) findViewById(R.id.back);
			loading = (TextView) findViewById(R.id.loading);
			loadingLayout = (RelativeLayout) findViewById(R.id.loading_layout);
			description = (EditText) findViewById(R.id.description);
	        myImage.setScaleType(ImageView.ScaleType.CENTER_INSIDE);
			myImage.setAdjustViewBounds(true);
			gps = new GPSTracker(this);
			description.setSelection(0);
			extras = getIntent().getExtras();
			fullPath=extras.getString("path");
			
			Display display = getWindowManager().getDefaultDisplay();
			widthS = (display.getWidth())/2;
			Log.e("width",widthS+"");

			try {
				ExifInterface exif = new ExifInterface(fullPath);
				rotation = exif.getAttributeInt(ExifInterface.TAG_ORIENTATION, ExifInterface.ORIENTATION_NORMAL);
				rotationInDegrees = exifToDegrees(rotation);
				matrix = new Matrix();
				if (rotation != 0f) {matrix.preRotate(rotationInDegrees);}
			} catch (IOException e1) {
				e1.printStackTrace();
			}

			id=mPrefs.getString("id", null);
			if(savedInstanceState==null){
				try{
					imgFile = new  File(fullPath);
					if(imgFile.exists()){
						BitmapFactory.Options options = new BitmapFactory.Options();
						options.inPreferredConfig = Bitmap.Config.ARGB_8888;
						Bitmap bitmap = decodeFile(imgFile);
						Bitmap cbitmap=Bitmap.createBitmap(bitmap, 0, 0, bitmap.getWidth(),bitmap.getHeight(),matrix,false);
						if(cbitmap.getWidth()>cbitmap.getHeight()){
							imageForUpload =Bitmap.createScaledBitmap(cbitmap,widthU,heightU, true);
							imageForShow=Bitmap.createScaledBitmap(cbitmap,widthU,heightU, true);
						}else{ 
							destHeight = cbitmap.getHeight()/(cbitmap.getWidth() / widthU );
//							destHeightShow = cbitmap.getHeight()/(cbitmap.getWidth() / widthS );
							destHeightShow=widthU * cbitmap.getHeight() / cbitmap.getWidth();
							imageForUpload =Bitmap.createScaledBitmap(cbitmap,widthU,destHeight, false);
							imageForShow=Bitmap.createScaledBitmap(cbitmap,widthS,destHeightShow, false);
						}
						myImage.setImageBitmap(imageForShow);
					}else{
						Toast.makeText(this, "Something went wrong. Please try later", Toast.LENGTH_SHORT).show();
						Log.e("No correct path",fullPath);
						finish();
					}
				}catch(NullPointerException e){
					finish();
				}catch(OutOfMemoryError e){		
		        	finish();
				}
			}
			
			 backButton.setOnClickListener(new View.OnClickListener() {
		            @Override
		            public void onClick(View v) {
		            	finish();
		            }
			 });
	        
	        
	        uploadButton.setOnClickListener(new View.OnClickListener() {
	            @Override
	            public void onClick(View v) {
	            	if(gps.canGetLocation()){
	            		isInternetPresent = cd.isConnectingToInternet();
	                	if(isInternetPresent==true){
	                		
	                    	descriptionValue=description.getText().toString();
	                    	if(descriptionValue==null)
	                    		descriptionValue=" ";
	                    	else{
	                    		try {
	                    			descriptionValue=URLEncoder.encode(descriptionValue, "UTF-8");
								} catch (UnsupportedEncodingException e) {
						        	
								}
	                    	}
	                    	if(descriptionValue.length()<8)
	                    		Toast.makeText(getApplicationContext(), "A description is required!", Toast.LENGTH_SHORT).show();
	                    	else{
	                    		AlphaAnimation alpha=new AlphaAnimation(0.5F,0.5F);
	                    		alpha.setFillAfter(true);
	                    		alpha.setDuration(0);
	                        	loadingLayout.setVisibility(View.VISIBLE);
	                        	loadingLayout.bringToFront();
	                        	loadingLayout.setAnimation(alpha);
	                        	loading.setVisibility(View.VISIBLE);
	                        	loading.bringToFront();
	                        	description.setEnabled(false);
	                        	uploadButton.setEnabled(false);
	                        	new Thread(new Runnable() {
	                        		
	                                @Override
	                 	            public void run() {
	                                    uploadImage();
	                 	             }
	                 	        }).start();	 
	                           
	                    	}
	                	}else{
	                		Toast.makeText(getApplicationContext(), "No internet connection", Toast.LENGTH_SHORT).show();
	                	}
	            	}else{
	            		gps.showSettingsAlert();
	            	}
	            }
	        });
			description.setSelection(0);
        }catch(Exception e){
			Toast.makeText(getApplicationContext(), "Something went wrong on image loading. Please try later!", Toast.LENGTH_LONG).show();
            finish();
        	
        } 
    }
    
    private Bitmap decodeFile(File f){

    	try {
    	    BitmapFactory.Options o = new BitmapFactory.Options();
    	    o.inJustDecodeBounds = true;
    	    BitmapFactory.decodeStream(new FileInputStream(f),null,o);

    	    final int REQUIRED_SIZE=widthU;

    	    int scale=1;
    	    while(o.outWidth/scale/2>=REQUIRED_SIZE && o.outHeight/scale/2>=REQUIRED_SIZE)
    	        scale*=2;

    	    BitmapFactory.Options o2 = new BitmapFactory.Options();
    	    o2.inSampleSize=scale;
    	    return BitmapFactory.decodeStream(new FileInputStream(f), null, o2);
    	} catch (FileNotFoundException e) {}
    	return null;
    	}
    
    private static int exifToDegrees(int exifOrientation) {        
        if (exifOrientation == ExifInterface.ORIENTATION_ROTATE_90) { return 90; } 
        else if (exifOrientation == ExifInterface.ORIENTATION_ROTATE_180) {  return 180; } 
        else if (exifOrientation == ExifInterface.ORIENTATION_ROTATE_270) {  return 270; }            
        return 0;    
     }
    
    public static Uri getImageContentUri(Context context, File imageFile) {
        String filePath = imageFile.getAbsolutePath();
        Cursor cursor = context.getContentResolver().query(
                MediaStore.Images.Media.EXTERNAL_CONTENT_URI,
                new String[] { MediaStore.Images.Media._ID },
                MediaStore.Images.Media.DATA + "=? ",
                new String[] { filePath }, null);
        if (cursor != null && cursor.moveToFirst()) {
            int id = cursor.getInt(cursor
                    .getColumnIndex(MediaStore.MediaColumns._ID));
            Uri baseUri = Uri.parse("content://media/external/images/media");
            return Uri.withAppendedPath(baseUri, "" + id);
        } else {
            if (imageFile.exists()) {
                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.DATA, filePath);
                return context.getContentResolver().insert(
                        MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
            } else {
                return null;
            }
        }
    }
    
    private void uploadImage(){
		gps.getLocation();
    	HttpClient httpClient = new DefaultHttpClient();
		HttpPost httpPost = new HttpPost("http://www.e-progress.gr/thessaloniki_appsforgreece/upload");
		List<NameValuePair> nameValuePair = new ArrayList<NameValuePair>(2);
		try{
			nameValuePair.add(new BasicNameValuePair("userid", id));
			nameValuePair.add(new BasicNameValuePair("image", encodeTobase64(imageForUpload)));
			nameValuePair.add(new BasicNameValuePair("long", gps.getLongitude()+""));
			nameValuePair.add(new BasicNameValuePair("lat", gps.getLatitude()+""));
			nameValuePair.add(new BasicNameValuePair("description",descriptionValue));
		} catch(NullPointerException e){
		}
		try {
		    httpPost.setEntity(new UrlEncodedFormEntity(nameValuePair));
		}
		catch (UnsupportedEncodingException e) {
		    UploadImage.this.runOnUiThread(new Runnable() {
				
				public void run() {
					Toast.makeText(getApplicationContext(), "Something went wrong. Please try later!", Toast.LENGTH_LONG).show();
				    UploadImage.this.finish();
				}
			});
		    
		}
		
		try {
		    HttpResponse httpresponse = httpClient.execute(httpPost);
		    HttpEntity responseEntity = httpresponse.getEntity();
		    if(responseEntity!=null) {
		        response = EntityUtils.toString(responseEntity);
		        
		        Log.e("RESPONSE",response+"!");
		        if(response.equals("1")){
		        	UploadImage.this.runOnUiThread(new Runnable() {
						
						public void run() {
							Toast.makeText(getApplicationContext(), "Upload Done!", Toast.LENGTH_LONG).show();
						}
					});
		        }else{UploadImage.this.runOnUiThread(new Runnable() {
					
					public void run() {
						Toast.makeText(getApplicationContext(), "Something went wrong. Please try later!", Toast.LENGTH_LONG).show();
					}
				});
		        }
		        UploadImage.this.finish();
		    }
		 
		} catch (ClientProtocolException e) {
			UploadImage.this.runOnUiThread(new Runnable() {
				
				public void run() {
					Toast.makeText(getApplicationContext(), "Something went wrong. Please try later!", Toast.LENGTH_LONG).show();
				}
			});
		    UploadImage.this.finish();
		         
		} catch (IOException e) {
			UploadImage.this.runOnUiThread(new Runnable() {
				
				public void run() {
					Toast.makeText(getApplicationContext(), "Something went wrong. Please try later!", Toast.LENGTH_LONG).show();
				}
			});
		    UploadImage.this.finish();
		
		} catch (IllegalStateException e) {
			UploadImage.this.runOnUiThread(new Runnable() {
				
				public void run() {
					Toast.makeText(getApplicationContext(), "Something went wrong. Please try later!", Toast.LENGTH_LONG).show();
				}
			});
		    UploadImage.this.finish();
		}
		
    }
    
    public static String encodeTobase64(Bitmap image)
    {
        Bitmap immagex=image;
        ByteArrayOutputStream baos = new ByteArrayOutputStream();  
        immagex.compress(Bitmap.CompressFormat.JPEG, 100, baos);
        byte[] b = baos.toByteArray();
        String imageEncoded = Base64.encodeToString(b,Base64.DEFAULT);
        return imageEncoded;
    }
    
   
    
    public Bitmap getResizedBitmap(Bitmap bm, int newHeight, int newWidth) {
    	 
    	int width = bm.getWidth();
    	 
    	int height = bm.getHeight();
    	 
    	float scaleWidth = ((float) newWidth) / width;
    	 
    	float scaleHeight = ((float) newHeight) / height;
    	 
    	Matrix matrix = new Matrix();
    	 
    	matrix.postScale(scaleWidth, scaleHeight);
    	 
    	Bitmap resizedBitmap = Bitmap.createBitmap(bm, 0, 0, width, height, matrix, false);
    	 
    	return resizedBitmap;
    	 
    	}
    
    
    
}




