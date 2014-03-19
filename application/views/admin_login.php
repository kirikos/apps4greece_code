<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Login Form</title>
  <link rel="stylesheet" href="/css/style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
  <section class="container">
    <div class="login">
      <h1>Login as Administator</h1>
      <form method="post" action="/thessaloniki_appsforgreece/admin_login">
        <p><input type="text" name="email" value="" placeholder="Email"></p>
        <p><input type="password" name="password" value="" placeholder="Password"></p>
        <p class="remember_me">
          <!--label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me on this computer
          </label-->
		  <?php
		  if($error_message!=1){
			echo '<label style="color:red;font-size: 12px;font-weight: bold;">'.$error_message.'</label>';
			}
			?>
		</p>
        <p class="submit"><input type="submit" name="commit" value="Login"></p>
      </form>
    </div>


  </section>


</body>
</html>
