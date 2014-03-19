package com.appsforgreece.gs;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.View;
import android.view.Window;
import android.view.inputmethod.EditorInfo;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

/**
 * Activity which displays a login screen to the user, offering registration as
 * well.
 */
public class Login extends Activity {
	
	
	public static final String EXTRA_EMAIL = "";
	private String xml;
	private SharedPreferences mPrefs;

	private XMLParser parser = new XMLParser();
	private NodeList nl;
	private Element e;

	
	private UserLoginTask mAuthTask = null;

	// Values for email and password at the time of the login attempt.
	private String mEmail;
	private String mPassword;
	private String userId;

	// UI references.
	private EditText mEmailView;
	private EditText mPasswordView;
	private View mLoginFormView;
	private View mLoginStatusView;
	private TextView mLoginStatusMessageView;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		getWindow().requestFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.login);
        mPrefs = this.getSharedPreferences("com.appsforgreece.gs", Context.MODE_PRIVATE);
		userId = mPrefs.getString("id", null);
		if(userId==null)
			userId="null";
		if(!userId.equals("null")){
			Intent i=new Intent(Login.this,TabsView.class);
			startActivity(i);
			finish();
		}
		
		// Set up the login form.
		mEmail = getIntent().getStringExtra(EXTRA_EMAIL);
		mEmailView = (EditText) findViewById(R.id.email);
		mEmailView.setText(mEmail);

		mPasswordView = (EditText) findViewById(R.id.password);
		mPasswordView
				.setOnEditorActionListener(new TextView.OnEditorActionListener() {
					@Override
					public boolean onEditorAction(TextView textView, int id,
							KeyEvent keyEvent) {
						if (id == R.id.login || id == EditorInfo.IME_NULL) {
							attemptLogin();
							return true;
						}
						return false;
					}
				});

		mLoginFormView = findViewById(R.id.login_form);
		mLoginStatusView = findViewById(R.id.login_status);
		mLoginStatusMessageView = (TextView) findViewById(R.id.login_status_message);

		findViewById(R.id.sign_in_button).setOnClickListener(
				new View.OnClickListener() {
					@Override
					public void onClick(View view) {
						InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
						imm.toggleSoftInput(InputMethodManager.SHOW_FORCED, 0);
						attemptLogin();
					}
				});
		
		findViewById(R.id.register_button).setOnClickListener(
				new View.OnClickListener() {
					@Override
					public void onClick(View view) {
						Intent i=new Intent(Login.this,Register.class);
						startActivity(i);
						InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
						imm.toggleSoftInput(InputMethodManager.SHOW_FORCED, 0);
					}
				});
	}

	
	public void attemptLogin() {
		if (mAuthTask != null) {
			return;
		}

		// Reset errors.
		mEmailView.setError(null);
		mPasswordView.setError(null);

		// Store values at the time of the login attempt.
		mEmail = mEmailView.getText().toString();
		mPassword = mPasswordView.getText().toString();

		boolean cancel = false;
		View focusView = null;

		// Check for a valid password.
		if (TextUtils.isEmpty(mPassword)) {
			mPasswordView.setError(getString(R.string.error_field_required));
			focusView = mPasswordView;
			cancel = true;
		} else if (mPassword.length() < 4) {
			mPasswordView.setError(getString(R.string.error_invalid_password));
			focusView = mPasswordView;
			cancel = true;
		}

		// Check for a valid email address.
		if (TextUtils.isEmpty(mEmail)) {
			mEmailView.setError(getString(R.string.error_field_required));
			focusView = mEmailView;
			cancel = true;
		} else if (!mEmail.contains("@")) {
			mEmailView.setError(getString(R.string.error_invalid_email));
			focusView = mEmailView;
			cancel = true;
		}

		if (cancel) {
			// There was an error; don't attempt login and focus the first
			// form field with an error.
			focusView.requestFocus();
		} else {
			// Show a progress spinner, and kick off a background task to
			// perform the user login attempt.
			mLoginStatusMessageView.setText(R.string.login_progress_signing_in);
			showProgress(true);
			mAuthTask = new UserLoginTask();
			mAuthTask.execute((Void) null);
		}
	}

	/**
	 * Shows the progress UI and hides the login form.
	 */
	@TargetApi(Build.VERSION_CODES.HONEYCOMB_MR2)
	private void showProgress(final boolean show) {
		if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.HONEYCOMB_MR2) {
			int shortAnimTime = getResources().getInteger(
					android.R.integer.config_shortAnimTime);

			mLoginStatusView.setVisibility(View.VISIBLE);
			mLoginStatusView.animate().setDuration(shortAnimTime)
					.alpha(show ? 1 : 0)
					.setListener(new AnimatorListenerAdapter() {
						@Override
						public void onAnimationEnd(Animator animation) {
							mLoginStatusView.setVisibility(show ? View.VISIBLE
									: View.GONE);
						}
					});

			mLoginFormView.setVisibility(View.VISIBLE);
			mLoginFormView.animate().setDuration(shortAnimTime)
					.alpha(show ? 0 : 1)
					.setListener(new AnimatorListenerAdapter() {
						@Override
						public void onAnimationEnd(Animator animation) {
							mLoginFormView.setVisibility(show ? View.GONE
									: View.VISIBLE);
						}
					});
		} else {
			mLoginStatusView.setVisibility(show ? View.VISIBLE : View.GONE);
			mLoginFormView.setVisibility(show ? View.GONE : View.VISIBLE);
		}
	}

	
	public class UserLoginTask extends AsyncTask<Void, Void, Boolean> {
		
		String LoginUrl="http://www.e-progress.gr/thessaloniki_appsforgreece/login";
		static final String KEY_ID = "id";
		static final String KEY_EMAIL = "email";
		static final String KEY_ROLE = "role";
		static final String KEY_VIOLATION = "violation";
		String id,email,role,violation="0";
		
		@Override
		protected Boolean doInBackground(Void... params) {
			try{
				LoginUrl+="?email="+mEmail+"&password="+mPassword+"&register=0";
				xml = parser.getXmlFromUrl(LoginUrl);
				if(xml!=null){
					Document doc = parser.getDomElement(xml);
					nl = doc.getElementsByTagName("login");
					e = (Element) nl.item(0);
					try{
						violation=parser.getValue(e, KEY_VIOLATION);
						if(violation.equals(""))
							violation="0";
						if(violation.equals("null"))
							violation="0";
						
					}catch(Exception e){
						violation="0";
					}
					if(!violation.equals("0")){
						Login.this.runOnUiThread(new Runnable() {

					        public void run() {
					            Toast.makeText(Login.this, "Τα στοιχεία που δώσατε είναι λάθος!", Toast.LENGTH_LONG).show();
					            mPasswordView.setText("");
					        }
					    });
						return false;
					}
					id=parser.getValue(e, KEY_ID);
					email=parser.getValue(e, KEY_EMAIL);
					role=parser.getValue(e, KEY_ROLE);
					Editor editor = mPrefs.edit();
					editor.putString("id",id);
					editor.putString("email",email);
					editor.putString("role",role);
					editor.putString(EXTRA_EMAIL,email);
			        editor.commit();
					return true;
				}else{
					id=null;
					Login.this.runOnUiThread(new Runnable() {

				        public void run() {
				            Toast.makeText(Login.this, "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε αργότερα!", Toast.LENGTH_LONG).show();
				            mPasswordView.setText("");
				        }
				    });
					return false;
				}
			}catch(Exception e){
				id=null;
				Log.e("Login exception","!");
				Login.this.runOnUiThread(new Runnable() {

			        public void run() {
			            Toast.makeText(Login.this, "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε αργότερα!", Toast.LENGTH_LONG).show();
			            mPasswordView.setText("");
			        }
			    });
				return false;
			}
		}

		@Override
		protected void onPostExecute(final Boolean success) {
			mAuthTask = null;
			showProgress(false);

			if (success) {
				if(id!=null){
					Intent i=new Intent(Login.this,TabsView.class);
					startActivity(i);
					finish();
				}
			}
		}

		@Override
		protected void onCancelled() {
			mAuthTask = null;
			showProgress(false);
		}
	}
}
