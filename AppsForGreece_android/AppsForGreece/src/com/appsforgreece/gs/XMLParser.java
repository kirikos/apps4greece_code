package com.appsforgreece.gs;

import java.io.IOException;
import java.io.StringReader;
import java.io.UnsupportedEncodingException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.InputSource;
import org.xml.sax.SAXException;

import android.util.Log;

public class XMLParser {
	String xmlGet = null;
	String urlGet;
	// constructor
	public XMLParser() {

	}

	/**
	 * Getting XML from URL making HTTP request
	 * @param url string
	 * */
	public String getXmlFromUrl(String url) {
			urlGet=url;
		
            try {
        		DefaultHttpClient httpClient = new DefaultHttpClient();
        		HttpGet httpGet = new HttpGet(urlGet);
        		
        		HttpResponse httpResponse = httpClient.execute(httpGet);
        		HttpEntity httpEntity = httpResponse.getEntity();
        		xmlGet = EntityUtils.toString(httpEntity);
        		
       		} catch (UnsupportedEncodingException e) {
       			Log.e("UnsupportedEncodingException",e.getMessage().toString());
       			
       		} catch (ClientProtocolException e) {
       			Log.e("ClientProtocolException",e.getMessage().toString());
       			
       		} catch (IOException e) {
       			Log.e("IOException",e.getMessage().toString());
       			
       		} catch (Exception e) {
       			Log.e("Exception",e.getMessage().toString());
       		}
	             
		
		// return XML
		return xmlGet;
	}
	
	
	public Document getDomElement(String xml){
		Document doc = null;
		DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		try {

			DocumentBuilder db = dbf.newDocumentBuilder();

			InputSource is = new InputSource();
		        is.setCharacterStream(new StringReader(xml));
		        doc = db.parse(is); 

			} catch (ParserConfigurationException e) {
				return null;
			} catch (SAXException e) {
	            return null;
			} catch (IOException e) {
				return null;
			} catch (Exception e) {
				return null;
			}

	        return doc;
	}
	
	/** Getting node value
	  * @param elem element
	  */
	 public final String getElementValue( Node elem ) {
	     Node child;
	     if( elem != null){
	         if (elem.hasChildNodes()){
	             for( child = elem.getFirstChild(); child != null; child = child.getNextSibling() ){
	                 if( child.getNodeType() == Node.TEXT_NODE  ){
	                     return child.getNodeValue();
	                 }
	             }
	         }
	     }
	     return "";
	 }
	 
	 /**
	  * Getting node value
	  * @param Element node
	  * @param key string
	  * */
	 public String getValue(Element item, String str) {		
			NodeList n = item.getElementsByTagName(str);		
			return this.getElementValue(n.item(0));
		}
}
