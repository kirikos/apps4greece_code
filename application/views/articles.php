<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Articles</title>
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
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC91LqwgiYemryx2QBqRplDD9TpAkLfRm0&amp;sensor=true">
    </script>
	<script type="text/javascript" src="/script/jquery-1.9.js"> </script>
   
</head>

<body>
<div style="background: url('http://www.e-progress.gr/images/topbar.png') repeat-x scroll 0 0 transparent;
height: 55px;
font-weight: bold;
font-size: 1.1em;
border-bottom: 1px solid #FFF;"><div style="width:375px; margin:0 auto;"><div class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/admin_console">Γεγονότα</a></div><div class="header_menu"><a style="color:white;" href="http://www.e-progress.gr/thessaloniki_appsforgreece/show_articles">Άρθρα</a></div><div class="header_menu"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/add_article">Προσθήκη Άρθρου</a></div>

</div>
<div class="header_menu" style="margin:0 auto; float:right;"><a href="http://www.e-progress.gr/thessaloniki_appsforgreece/admin_logout">Αποσύνδεση</a></div>
</div>

<?php
$count_articles = count($articles);
$i=0;
echo '<div id="post_container">';
while($i<$count_articles){
echo '<section class="container articles" style="margin: 30px auto; width:700px;">
    <div class="login" style="width:581px;">
      <div style="height:643px; margin: -20px -20px 0px;
line-height: 40px;
font-size: 15px;
font-weight: bold;
color: #555;
text-align: center;
text-shadow: 0 1px white;
background: #f3f3f3;
border-bottom: 1px solid #cfcfcf;
border-radius: 3px 3px 0 0;
background-image: -webkit-linear-gradient(top, whiteffd, #eef2f5);
background-image: -moz-linear-gradient(top, whiteffd, #eef2f5);
background-image: -o-linear-gradient(top, whiteffd, #eef2f5);
background-image: linear-gradient(to bottom, whiteffd, #eef2f5);
-webkit-box-shadow: 0 1px whitesmoke;
box-shadow: 0 1px whitesmoke;">
<div style="float:left;"><img id="p_'.$i.'" style="width:241px; float:left;" src="/'.$articles[$i]['photo'].'" />


<div style="float: right;">
<div style="width: 360px;margin-left: 5px;color: black; word-wrap: break-word;line-height: 2;">'.$articles[$i]['title'].'</div>
<div style="float: right;width: 360px;word-wrap: break-word;text-align: left;height: 553px;margin-left: 5px;font-size: 14px;line-height: 1;">'.$articles[$i]['description'].'</div>

</div><div style="color: rgb(41, 49, 139);">';

if($articles[$i]['category']==1){
	echo 'Κοινωνία';
}else if($articles[$i]['category']==2){
	echo 'Οικονομία';
}else{
	echo 'Πολιτισμός';
}

echo '</div>
</div>
';


echo '<div>
<input onclick="delete_photo('.$articles[$i]['id'].')" style="color:red;" type="submit" name="commit" value="Delete">
</div>
</div>



  </section>';
  $i++;

}
echo '</div>';
echo '<div id="bmore" onclick="more_articles()" style="width: 44%;
border: 1px solid silver;
border-radius: 5px;
margin: 20px auto;
cursor: pointer;
cursor: hand;
clear: left;
height: 30px;
text-align: center;
background-color: #eee;">
						<div style="background: url(\'../images/more.png\') no-repeat scroll 0 4px transparent;
display: inline-block;
float: none;
font-size: 15px;
height: 26px;
padding-left: 30px;
padding-top: 5px;
width: auto;
margin: 0 auto;">
						Προβολή περισσοτέρων
						</div>
					</div>';
?>



</body>

<script>

function more_articles(){
	
var offs = $('.articles').length;
	$.ajax({
		type: "POST",
        url: "/thessaloniki_appsforgreece/more_articles",
		data: {
			offset: offs
        }
		
    })
	.error(function(response){
	console.log('Requesting error. Please try again.')
	})
    .success (function(response) {  
		if(response == 'No Data.'){
			$('#bmore').remove();
			
		}
		else if(response == 'problem'){
			console.log('Requesting error. Please try again.');
		}
		else{
			document.getElementById('post_container').innerHTML += response;
		}
	});
}

function delete_photo(photo){
	
	
	$.ajax({
		type: "POST",
        url: "/thessaloniki_appsforgreece/delete_aricle",
		data: {
			id: photo
        }
		
		})
		.error(function(response){
			console.log('Requesting error. Please try again.');
		})
		.success (function(response) {
		
			if(response!='problem'){
				window.location = "http://www.e-progress.gr/thessaloniki_appsforgreece/show_articles";
			}else{
				console.log('Requesting error. Please try again.');
			}

		});
	
}

</script>
</html>
