<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Add Article</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
  .header_menu a{
	color: #BFBFBF;
display: block;
font-size: 16px;
font-weight: bold;
letter-spacing: 0.02em;
line-height: 19px;
padding: 10px 15px 0 12px;
text-decoration: none;
float: left;
cursor:pointer;
  }
    .header_menu a:hover{
	color: white;
  }
  </style>
  <script src="/script/jquery-1.9.js"></script>
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<div style="background: url('http://www.e-progress.gr/images/topbar.png') repeat-x scroll 0 0 transparent;
height: 55px;
font-weight: bold;
font-size: 1.1em;
border-bottom: 1px solid #FFF;"><div style="width:375px; margin:0 auto;"><div class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/admin_console">Γεγονότα</a></div><div class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/show_articles">Άρθρα</a></div><div class="header_menu"><a style="color:white;" href="http://www.e-progress.gr/thessaloniki_appsforgreece/add_article">Προσθήκη Άρθρου</a></div>

</div>
<div style="float:right; margin: 0 auto;" class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/admin_logout">Αποσύνδεση</a></div>
</div>
  <section style="width:900px;" class="container">
    <div style="width:800px;" class="login">
      <h1>Προσθήκη Άρθρου</h1>
      <form method="post" action="/thessaloniki_appsforgreece/save_article" enctype="multipart/form-data">
		<p><input style="
margin: 5px;
padding: 0 10px;
width: 200px;
height: 34px;
color: #404040;
background: white;
border: 1px solid;
border-color: #c4c4c4 #d1d1d1 #d4d4d4;
border-radius: 2px;
outline: 5px solid #eff4f7;
-moz-outline-radius: 3px;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);" type="file" name="userfile" id="file"/></p>
        <p><input type="text" name="title" value="" placeholder="Τίτλος" style="width: 592px;"></p>
		<p><textarea name="description" value="" placeholder="Περιγραφή" style="
		min-width:592px;
		max-width:620px;
		min-height:150px;
		max-height:900px;
margin: 5px;
padding: 0 10px;
width: 200px;
height: 34px;
color: #404040;
background: white;
border: 1px solid;
border-color: #c4c4c4 #d1d1d1 #d4d4d4;
border-radius: 2px;
outline: 5px solid #eff4f7;
-moz-outline-radius: 3px;
-webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);
box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.12);"></textarea></p>
<p style="font-family: 'Lucida Grande', Tahoma, Verdana, sans-serif;
font-size: 14px;"><input type="radio" name="category" value="1" checked>Κοινωνία
<input type="radio" name="category" value="2">Οικονομία<input type="radio" name="category" value="3">Πολιτισμός</p>
        <p class="remember_me">
          <!--label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me on this computer
          </label-->
		  <?php
			if(isset($error_message)){
				echo '<label style="color:red;font-size: 12px;font-weight: bold;">'.$error_message.'</label>';
			}else if(isset($save_message)){
				echo '<label style="color:green;font-size: 12px;font-weight: bold;">'.$save_message.'</label>';
			}
		  ?>
		  
		</p>
        <p class="submit"><input type="submit" name="commit" value="Δημοσίευση"></p>
      </form>
    </div>


  </section>
<script>
$('#file').bind('change', function() {
   
   var ext = $('#file').val().split('.').pop().toLowerCase();
	if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
		flag_size=1;
		alert('invalid extension!');
	}

});
</script>

</body>
</html>
